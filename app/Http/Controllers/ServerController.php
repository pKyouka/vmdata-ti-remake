<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servers = Server::withCount('vms')->paginate(10);
        return view('servers.index', compact('servers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'local_network' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,maintenance,inactive',
        ]);

        Server::create($validated);

        return redirect()->route('servers.index')
            ->with('success', 'Server created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server)
    {
        $server->load('vms');
        return view('servers.show', compact('server'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Server $server)
    {
        return view('servers.edit', compact('server'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'local_network' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,maintenance,inactive',
        ]);

        $server->update($validated);

        return redirect()->route('servers.index')
            ->with('success', 'Server updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        // Check if server has VMs
        if ($server->vms()->exists()) {
            return redirect()->route('servers.index')
                ->with('error', 'Cannot delete server with existing VMs. Please remove or reassign VMs first.');
        }

        $server->delete();

        return redirect()->route('servers.index')
            ->with('success', 'Server deleted successfully!');
    }
}
