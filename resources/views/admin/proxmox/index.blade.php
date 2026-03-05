@extends('layouts.app')

@section('title', 'Proxmox & Ansible Integration - VMDATA TI')
@section('page-title', 'System Integration Status')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Proxmox Status Card -->
        <div class="glass rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-server fa-4x"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-network-wired text-psti-navy mr-3"></i> Proxmox Node
            </h3>

            @if($proxmoxStatus)
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                        <span class="text-green-700 font-semibold"><i class="fas fa-check-circle mr-2"></i> Connected</span>
                        <span class="text-sm text-green-600">v{{ $proxmoxStatus['pveversion'] ?? 'Unknown' }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="text-xs text-gray-500 uppercase">CPU Usage</span>
                            <div class="text-lg font-bold text-gray-700">
                                {{ number_format(($proxmoxStatus['cpu'] ?? 0) * 100, 1) }}%</div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="text-xs text-gray-500 uppercase">Memory</span>
                            <div class="text-lg font-bold text-gray-700">
                                {{ number_format(($proxmoxStatus['memory']['used'] ?? 0) / 1024 / 1024 / 1024, 2) }} GB
                            </div>
                            <div class="text-xs text-gray-400">of
                                {{ number_format(($proxmoxStatus['memory']['total'] ?? 0) / 1024 / 1024 / 1024, 2) }} GB</div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="text-xs text-gray-500 uppercase">Uptime</span>
                            <div class="text-lg font-bold text-gray-700">
                                {{ gmdate("H:i:s", $proxmoxStatus['uptime'] ?? 0) }}
                            </div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="text-xs text-gray-500 uppercase">Load Avg</span>
                            <div class="text-lg font-bold text-gray-700">
                                {{ implode(', ', $proxmoxStatus['loadavg'] ?? []) }}
                            </div>
                        </div>
                    </div>

                    @if($terminalUrl)
                        <div class="mt-6">
                            <a href="{{ $terminalUrl }}" target="_blank"
                                class="btn-primary-custom w-full flex justify-center items-center py-3 rounded-lg shadow-md hover:shadow-lg transition-all">
                                <i class="fas fa-terminal mr-2"></i> Open Node Console
                            </a>
                            <p class="text-xs text-gray-500 text-center mt-2">Opens external Proxmox console</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-4 bg-red-50 rounded-lg border border-red-100 text-center">
                    <i class="fas fa-times-circle text-red-500 text-3xl mb-2"></i>
                    <p class="text-red-700 font-semibold">Connection Failed</p>
                    <p class="text-sm text-red-600 mt-1">Check credentials and network config.</p>
                </div>
            @endif
        </div>

        <!-- Ansible Status Card -->
        <div class="glass rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-cogs fa-4x"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-code-branch text-psti-orange mr-3"></i> Ansible Controller
            </h3>

            @if($ansibleStatus)
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                        <span class="text-green-700 font-semibold"><i class="fas fa-check-circle mr-2"></i> Installed</span>
                    </div>

                    <div class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-xs overflow-x-auto">
                        <pre>{{ $ansibleStatus }}</pre>
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2"><i class="fas fa-info-circle mr-1"></i> Integration Ready</p>
                        <p class="text-xs text-gray-500">Ansible is detected and ready to execute playbooks for VM provisioning.
                        </p>
                    </div>
                </div>
            @else
                <div class="p-4 bg-orange-50 rounded-lg border border-orange-100 text-center">
                    <i class="fas fa-exclamation-triangle text-orange-500 text-3xl mb-2"></i>
                    <p class="text-orange-700 font-semibold">Ansible Not Found</p>
                    <p class="text-sm text-orange-600 mt-1">Ensure Ansible is installed on the host server.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Integration Settings / Info -->
    <div class="glass rounded-xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-lg font-bold text-gray-700 mb-4">Configuration Status</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Proxmox Host</label>
                <div class="bg-gray-50 px-3 py-2 rounded border border-gray-200 text-gray-600 truncate">
                    {{ config('services.proxmox.host') ?? 'Not Configured' }}
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ansible Playbook Path</label>
                <div class="bg-gray-50 px-3 py-2 rounded border border-gray-200 text-gray-600 truncate">
                    {{ config('services.ansible.playbook_path', 'default') }}
                </div>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <a href="{{ route('admin.proxmox.index') }}"
                class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                <i class="fas fa-sync-alt mr-2"></i> Refresh Status
            </a>
        </div>
    </div>
@endsection