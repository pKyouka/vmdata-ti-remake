<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVMRentalRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'vm_id' => ['nullable', 'exists:vms,id'],
            'cpu' => ['required', 'integer', 'min:1', 'max:64'],
            'ram' => ['required', 'integer', 'min:1'],
            'storage' => ['required', 'integer', 'min:1'],
            'start_time' => ['required', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
            'purpose' => ['nullable', 'string', 'max:1000'],
            'operating_system' => ['nullable', 'string', 'max:255'],
        ];
    }
}
