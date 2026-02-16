<?php
namespace App\Http\Controllers;

use App\Models\VM;
use App\Models\Category;
use App\Models\VMSpecification;
use App\Models\Server;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = VM::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });

            // also search related category name
            $query->orWhereHas('category', function ($cq) use ($search) {
                $cq->where('name', 'like', "%{$search}%");
            });
        }
        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $vms = $query->paginate(12)->appends($request->query());

        // Load servers with their VMs + related category & specification to avoid N+1
        try {
            // specification relation was removed from VM, only eager-load vms and their category
            $servers = Server::with(['vms' => function ($q) {
                $q->orderBy('created_at', 'asc'); }, 'vms.category'])->get();
        } catch (\Exception $e) {
            $servers = collect([]);
        }

        return view('vms.index', compact('vms', 'servers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $specifications = VMSpecification::all();
        // pass servers so user can choose server to attach VM to (optional)
        $servers = Server::all();
        $selectedServerId = request()->get('server_id');

        // If admin was redirected from a rental approval, prefill using rental
        $rental = null;
        $rentalId = request()->get('rental_id');
        if ($rentalId) {
            $rental = \App\Models\VMRental::with('user')->find($rentalId);
        }

        return view('vms.create', compact('categories', 'specifications', 'servers', 'selectedServerId', 'rental'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // accept either a numeric id or a slug string for category
            'category_id' => 'required',
            'access_username' => 'nullable|string|max:255',
            'access_password' => 'nullable|string|max:255',
            'ram' => 'required|integer|min:1',
            'cpu' => 'required|integer|min:1|max:64',
            // server_id is required: VMs must be attached to a server to avoid orphan records
            'server_id' => 'required|exists:servers,id',
            'ip_address' => 'nullable|string|max:255',
            'storage' => 'required|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in(['available', 'rented', 'maintenance', 'offline'])],
        ]);

        // Resolve category: allow slug (string) or id (numeric)
        $categoryInput = $request->input('category_id');
        if (is_numeric($categoryInput)) {
            $category = Category::find($categoryInput);
        } else {
            // only attempt slug lookup if the column exists; otherwise fallback to name
            if (Schema::hasColumn('categories', 'slug')) {
                $category = Category::where('slug', $categoryInput)->first();
            } else {
                // try to match by name (case-insensitive)
                $category = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryInput)])->first();
            }
        }

        if (!$category) {
            return back()->withInput()->withErrors(['category_id' => 'Selected category is invalid.']);
        }

        $validated['category_id'] = $category->id;

        // attach current user as owner
        $validated['user_id'] = auth()->id();

        // Only include access_password if provided (mutator will encrypt)
        $createData = $validated;
        if ($request->filled('access_password')) {
            $createData['access_password'] = $request->input('access_password');
        }
        if ($request->filled('access_username')) {
            $createData['access_username'] = $request->input('access_username');
        }

        $vm = VM::create($createData);

        // If this VM creation is in response to a rental request, link them
        if ($request->filled('rental_id')) {
            $rental = \App\Models\VMRental::find($request->input('rental_id'));
            if ($rental) {
                $rental->vm_id = $vm->id;
                $rental->status = 'active';
                $rental->save();

                // mark VM as rented
                $vm->status = 'rented';
                $vm->save();

                // notify the requesting user that their rental is approved and VM created
                try {
                    $rental->user->notify(new \App\Notifications\VMRentalStatusUpdated($rental, 'approve', auth()->user()));
                } catch (\Exception $e) {
                    // don't break the flow on notify failure, just log
                    \Illuminate\Support\Facades\Log::error('Failed to notify user after VM creation', ['error' => $e->getMessage(), 'rental_id' => $rental->id]);
                }
            }
        }

        return redirect()->route('vms.index')
            ->with('success', 'Virtual Machine created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(VM $vm)
    {
        $vm->load(['category', 'specification', 'rentals.user']);

        return view('vms.show', compact('vm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VM $vm)
    {
        $categories = Category::all();
        $specifications = VMSpecification::all();
        // pass servers as well so edit form can select server
        $servers = Server::all();

        return view('vms.edit', compact('vm', 'categories', 'specifications', 'servers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VM $vm)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'access_username' => 'nullable|string|max:255',
            'access_password' => 'nullable|string|max:255',
            'ram' => 'required|integer|min:1',
            'cpu' => 'required|integer|min:1|max:64',
            // require server on update as well to ensure VMs remain assigned
            'server_id' => 'required|exists:servers,id',
            'ip_address' => 'nullable|string|max:255',
            'storage' => 'required|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in(['available', 'rented', 'maintenance', 'offline'])],
        ]);

        $categoryInput = $request->input('category_id');
        if (is_numeric($categoryInput)) {
            $category = Category::find($categoryInput);
        } else {
            if (Schema::hasColumn('categories', 'slug')) {
                $category = Category::where('slug', $categoryInput)->first();
            } else {
                $category = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryInput)])->first();
            }
        }

        if (!$category) {
            return back()->withInput()->withErrors(['category_id' => 'Selected category is invalid.']);
        }

        $validated['category_id'] = $category->id;

        // Only update password when provided
        $updateData = $validated;
        if ($request->filled('access_password')) {
            $updateData['access_password'] = $request->input('access_password');
        } else {
            // prevent overwriting with empty value
            unset($updateData['access_password']);
        }
        if ($request->filled('access_username')) {
            $updateData['access_username'] = $request->input('access_username');
        }

        $vm->update($updateData);

        return redirect()->route('vms.index')
            ->with('success', 'Virtual Machine updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VM $vm)
    {
        // Prevent deleting a VM that has any associated rentals to avoid FK constraint failures.
        // Previously we only checked for active rentals — but other statuses (completed/cancelled/pending)
        // still have fk rows and will block DELETE. Check for any rental reference instead.
        if ($vm->rentals()->exists()) {
            $rentalIds = $vm->rentals()->pluck('id')->take(10)->toArray();
            $message = 'Cannot delete VM because it has related rental records. Please remove or reassign those rentals first.';
            return redirect()->route('vms.show', $vm->id)
                ->with('error', $message)
                ->with('blocking_rental_ids', $rentalIds);
        }

        try {
            $vm->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            // Friendly fallback if DB still rejects the delete for any reason (e.g., unexpected FK)
            \Log::warning('Failed to delete VM: ' . $e->getMessage(), ['vm_id' => $vm->id]);
            return redirect()->route('vms.index')
                ->with('error', 'Unable to delete VM due to related records. Please check rentals or database constraints.');
        }

        return redirect()->route('vms.index')
            ->with('success', 'Virtual Machine deleted successfully!');
    }

    /**
     * Toggle VM status (admin only)
     */
    public function toggleStatus(VM $vm)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $newStatus = $vm->status === 'available' ? 'maintenance' : 'available';
        $vm->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'VM status changed to ' . $newStatus);
    }

    /**
     * Update VM status via AJAX (admin only)
     */
    public function updateStatus(Request $request, VM $vm)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['available', 'rented', 'maintenance', 'offline'])],
        ]);

        $old = $vm->status;
        $vm->status = $validated['status'];
        $vm->save();

        return response()->json([
            'success' => true,
            'old' => $old,
            'status' => $vm->status,
            'message' => 'Status updated to ' . $vm->status,
        ]);
    }

    /**
     * Generate the next available IP address
     */
    private function generateNextIP()
    {
        // Base IP range (contoh: 192.168.1.x)
        $baseIP = '192.168.1.';
        $startRange = 10;  // Mulai dari 192.168.1.10
        $endRange = 254;   // Sampai 192.168.1.254

        // Ambil semua IP yang sudah digunakan
        $usedIPs = VM::whereNotNull('ip_address')
            ->where('ip_address', 'like', $baseIP . '%')
            ->pluck('ip_address')
            ->toArray();

        // Cari IP yang tersedia
        for ($i = $startRange; $i <= $endRange; $i++) {
            $candidateIP = $baseIP . $i;

            if (!in_array($candidateIP, $usedIPs)) {
                return $candidateIP;
            }
        }

        // Jika tidak ada IP yang tersedia dalam range, throw exception
        throw new \Exception('No available IP addresses in the range.');
    }
}
