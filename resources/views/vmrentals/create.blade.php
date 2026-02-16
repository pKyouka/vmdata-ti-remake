@extends('layouts.app')

@section('title', 'Buat Permintaan Sewa - VMDATA TI')
@section('page-title', 'Buat Permintaan Sewa VM')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="glass rounded-xl shadow-lg border border-gray-100 p-8 relative overflow-hidden">
            <!-- Decorative bg -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-100 rounded-full opacity-50 blur-xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-purple-100 rounded-full opacity-50 blur-xl">
            </div>

            <form method="POST" action="{{ route('vmrentals.store') }}" class="relative z-10">
                @csrf

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-red-800">Terdapat kesalahan pada inputan Anda:</p>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-server text-psti-navy mr-2"></i> Pilih VM (Opsional)
                </h3>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gunakan VM yang Tersedia</label>
                    <div class="relative">
                        <select name="vm_id"
                            class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-psti-navy focus:border-psti-navy sm:text-sm rounded-lg shadow-sm bg-white/50 backdrop-blur-sm transition-all duration-200">
                            <option value="">-- Tentukan Spesifikasi Manual (Custom) --</option>
                            @foreach($vms as $vm)
                                <option value="{{ $vm->id }}" {{ old('vm_id') == $vm->id ? 'selected' : '' }}>
                                    {{ $vm->name }} - {{ optional($vm->specification)->name ?? 'Custom' }}
                                    ({{ $vm->ip_address ?? 'No IP' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Pilih VM jika Anda ingin menyewa unit spesifik yang sedang
                        tersedia (Available). Jika kosong, isi spesifikasi di bawah.
                    </p>
                </div>

                <div class="border-t border-gray-200 my-6"></div>

                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sliders-h text-psti-navy mr-2"></i> Spesifikasi yang Dibutuhkan
                </h3>

                <!-- Package Selection -->
                <div class="mb-6">
                    <span class="block text-sm font-medium text-gray-700 mb-3">Pilih Paket Rekomendasi</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="package-container">
                        <!-- Paket Starter -->
                        <div onclick="selectPackage('starter')" id="pkg-starter"
                            class="cursor-pointer border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-blue-400 transition-all duration-200 bg-white group relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="fas fa-paper-plane text-4xl text-blue-500"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-lg">Starter</h4>
                            <p class="text-xs text-gray-500 mb-3">Untuk kebutuhan ringan</p>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p><i class="fas fa-microchip w-5 text-center text-blue-400"></i> 1 vCPU</p>
                                <p><i class="fas fa-memory w-5 text-center text-purple-400"></i> 2 GB RAM</p>
                                <p><i class="fas fa-hdd w-5 text-center text-green-400"></i> 5 GB Storage</p>
                            </div>
                        </div>

                        <!-- Paket Basic -->
                        <div onclick="selectPackage('basic')" id="pkg-basic"
                            class="cursor-pointer border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-blue-400 transition-all duration-200 bg-white group relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="fas fa-rocket text-4xl text-blue-500"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-lg">Basic</h4>
                            <p class="text-xs text-gray-500 mb-3">Standar operasional</p>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p><i class="fas fa-microchip w-5 text-center text-blue-400"></i> 2 vCPU</p>
                                <p><i class="fas fa-memory w-5 text-center text-purple-400"></i> 4 GB RAM</p>
                                <p><i class="fas fa-hdd w-5 text-center text-green-400"></i> 10 GB Storage</p>
                            </div>
                        </div>

                        <!-- Paket Pro -->
                        <div onclick="selectPackage('pro')" id="pkg-pro"
                            class="cursor-pointer border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-blue-400 transition-all duration-200 bg-white group relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="fas fa-meteor text-4xl text-blue-500"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-lg">Pro</h4>
                            <p class="text-xs text-gray-500 mb-3">Performa tinggi</p>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p><i class="fas fa-microchip w-5 text-center text-blue-400"></i> 4 vCPU</p>
                                <p><i class="fas fa-memory w-5 text-center text-purple-400"></i> 8 GB RAM</p>
                                <p><i class="fas fa-hdd w-5 text-center text-green-400"></i> 25 GB Storage</p>
                            </div>
                        </div>

                        <!-- Custom -->
                        <div onclick="selectPackage('custom')" id="pkg-custom"
                            class="cursor-pointer border-2 border-psti-navy bg-blue-50 rounded-xl p-4 hover:shadow-md transition-all duration-200 group relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="fas fa-sliders-h text-4xl text-psti-navy"></i>
                            </div>
                            <h4 class="font-bold text-psti-navy text-lg">Custom</h4>
                            <p class="text-xs text-gray-500 mb-3">Spesifikasi manual</p>
                            <p class="text-sm text-gray-600 mt-2">Sesuaikan resource dengan kebutuhan spesifik Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 transition-all duration-300" id="manual-specs">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CPU (vCPU)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-microchip text-gray-400"></i>
                            </div>
                            <input type="number" name="cpu" id="input-cpu" min="1" max="64"
                                class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-lg focus:ring-psti-navy focus:border-psti-navy sm:text-sm shadow-sm transition-colors"
                                placeholder="Contoh: 2" value="{{ old('cpu', 1) }}" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RAM (GB)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-memory text-gray-400"></i>
                            </div>
                            <input type="number" name="ram" id="input-ram" min="1"
                                class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-lg focus:ring-psti-navy focus:border-psti-navy sm:text-sm shadow-sm transition-colors"
                                placeholder="Contoh: 4" value="{{ old('ram') }}" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Storage (GB)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hdd text-gray-400"></i>
                            </div>
                            <input type="number" name="storage" id="input-storage" min="1"
                                class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-lg focus:ring-psti-navy focus:border-psti-navy sm:text-sm shadow-sm transition-colors"
                                placeholder="Contoh: 10" value="{{ old('storage') }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Sistem Operasi (OS)</label>
                    <input type="hidden" name="operating_system" id="operating_system"
                        value="{{ old('operating_system') }}">

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 mb-4">
                        <!-- Ubuntu -->
                        <div onclick="selectOS('ubuntu')" id="os-ubuntu"
                            class="cursor-pointer border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-orange-400 transition-all duration-200 bg-white group flex flex-col items-center justify-center text-center h-32">
                            <i
                                class="fab fa-ubuntu text-4xl text-orange-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-gray-700 text-sm">Ubuntu</span>
                        </div>

                        <!-- Debian -->
                        <div onclick="selectOS('debian')" id="os-debian"
                            class="cursor-pointer border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-red-500 transition-all duration-200 bg-white group flex flex-col items-center justify-center text-center h-32">
                            <i
                                class="fab fa-linux text-4xl text-red-600 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-gray-700 text-sm">Debian</span>
                        </div>

                        <!-- CentOS/Rocky -->
                        <div onclick="selectOS('rocky')" id="os-rocky"
                            class="cursor-pointer border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-green-500 transition-all duration-200 bg-white group flex flex-col items-center justify-center text-center h-32">
                            <i
                                class="fas fa-server text-4xl text-green-600 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-gray-700 text-sm">Rocky / CentOS</span>
                        </div>

                        <!-- Windows -->
                        <div onclick="selectOS('windows')" id="os-windows"
                            class="cursor-pointer border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-blue-500 transition-all duration-200 bg-white group flex flex-col items-center justify-center text-center h-32">
                            <i
                                class="fab fa-windows text-4xl text-blue-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-gray-700 text-sm">Windows Server</span>
                        </div>

                        <!-- Other -->
                        <div onclick="selectOS('other')" id="os-other"
                            class="cursor-pointer border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-gray-500 transition-all duration-200 bg-white group flex flex-col items-center justify-center text-center h-32">
                            <i
                                class="fas fa-compact-disc text-4xl text-gray-500 mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="font-bold text-gray-700 text-sm">Custom / Lainnya</span>
                        </div>
                    </div>

                    <!-- Version Selection (Hidden by default) -->
                    <div id="os-version-container"
                        class="hidden bg-gray-50 rounded-lg p-4 border border-gray-200 animate-fade-in-up">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2" id="os-version-label">Pilih
                            Versi</label>
                        <select id="os-version-select" onchange="updateOSInput()"
                            class="block w-full text-base border-gray-300 focus:outline-none focus:ring-psti-navy focus:border-psti-navy sm:text-sm rounded-lg shadow-sm">
                            <!-- Options populated by JS -->
                        </select>
                        <input type="text" id="os-custom-input" oninput="updateOSInput()"
                            class="hidden mt-2 block w-full text-base border-gray-300 focus:outline-none focus:ring-psti-navy focus:border-psti-navy sm:text-sm rounded-lg shadow-sm"
                            placeholder="Tuliskan nama dan versi OS secara lengkap...">
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center mt-8">
                    <i class="fas fa-calendar-alt text-psti-navy mr-2"></i> Durasi Sewa
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_time"
                            class="block w-full py-3 px-4 border-gray-300 rounded-lg focus:ring-psti-navy focus:border-psti-navy shadow-sm sm:text-sm"
                            required
                            value="{{ old('start_time') ? \Carbon\Carbon::parse(old('start_time'))->format('Y-m-d') : date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai (Opsional)</label>
                        <input type="date" name="end_time"
                            class="block w-full py-3 px-4 border-gray-300 rounded-lg focus:ring-psti-navy focus:border-psti-navy shadow-sm sm:text-sm"
                            value="{{ old('end_time') ? \Carbon\Carbon::parse(old('end_time'))->format('Y-m-d') : '' }}">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk durasi default (1 jam)</p>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Penggunaan</label>
                    <textarea name="purpose" rows="3"
                        class="block w-full py-3 px-4 border-gray-300 rounded-lg focus:ring-psti-navy focus:border-psti-navy shadow-sm sm:text-sm"
                        placeholder="Jelaskan kebutuhan Anda secara singkat...">{{ old('purpose') }}</textarea>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('user.dashboard') }}"
                        class="px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-psti-navy transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-psti-navy hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-psti-navy transition-all transform hover:scale-105">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectPackage(pkg) {
            // Packages definition
            const packages = {
                'starter': { cpu: 1, ram: 2, storage: 5 },
                'basic': { cpu: 2, ram: 4, storage: 10 },
                'pro': { cpu: 4, ram: 8, storage: 25 }
            };

            const inputCpu = document.getElementById('input-cpu');
            const inputRam = document.getElementById('input-ram');
            const inputStorage = document.getElementById('input-storage');
            const manualSpecs = document.getElementById('manual-specs');

            // Reset all cards styling
            ['starter', 'basic', 'pro', 'custom'].forEach(p => {
                const card = document.getElementById('pkg-' + p);
                card.classList.remove('border-psti-navy', 'bg-blue-50', 'border-2');
                card.classList.add('border-gray-200', 'bg-white');

                // Reset text colors inside cards if needed (optional refinement)
                // For valid Package cards, reset title color
                if (p !== 'custom') {
                    card.querySelector('h4').classList.remove('text-psti-navy');
                    card.querySelector('h4').classList.add('text-gray-800');
                }
            });

            // Highlight selected card
            const selected = document.getElementById('pkg-' + pkg);
            selected.classList.remove('border-gray-200', 'bg-white');
            selected.classList.add('border-psti-navy', 'bg-blue-50', 'border-2');

            if (pkg !== 'custom') {
                selected.querySelector('h4').classList.remove('text-gray-800');
                selected.querySelector('h4').classList.add('text-psti-navy');
            }

            // Logic for inputs
            if (pkg === 'custom') {
                // Enable inputs
                inputCpu.readOnly = false;
                inputRam.readOnly = false;
                inputStorage.readOnly = false;

                // Visual cue for inputs being active
                manualSpecs.classList.remove('opacity-50', 'pointer-events-none');

                // Optional: clear values if they match a package? No, keep them for easier editing.
            } else {
                // Fill inputs
                const specs = packages[pkg];
                inputCpu.value = specs.cpu;
                inputRam.value = specs.ram;
                inputStorage.value = specs.storage;

                // Disable inputs (make readonly) to enforce package specs
                inputCpu.readOnly = true;
                inputRam.readOnly = true;
                inputStorage.readOnly = true;

                // Visual cue for inputs being locked
                // manualSpecs.classList.add('opacity-50', 'pointer-events-none'); 
                // Don't fully hide, just show they are filled.
            }
        }

        // Initialize: check if inputs match a package or default to custom
        document.addEventListener('DOMContentLoaded', () => {
            // Default to custom so user sees the form is editable, or select Custom card by default
            selectPackage('custom');
        });

        // OS Selection Logic
        const osData = {
            'ubuntu': {
                name: 'Ubuntu',
                versions: ['Ubuntu 24.04 LTS', 'Ubuntu 22.04 LTS', 'Ubuntu 20.04 LTS']
            },
            'debian': {
                name: 'Debian',
                versions: ['Debian 12 (Bookworm)', 'Debian 11 (Bullseye)', 'Debian 10 (Buster)']
            },
            'rocky': {
                name: 'Rocky/CentOS',
                versions: ['Rocky Linux 9', 'Rocky Linux 8', 'CentOS Stream 9', 'CentOS Stream 8']
            },
            'windows': {
                name: 'Windows Server',
                versions: ['Windows Server 2022', 'Windows Server 2019', 'Windows Server 2016']
            },
            'other': {
                name: 'Custom',
                versions: [] // Empty means show text input
            }
        };

        function selectOS(osKey) {
            const osInput = document.getElementById('operating_system');
            const versionContainer = document.getElementById('os-version-container');
            const versionSelect = document.getElementById('os-version-select');
            const customInput = document.getElementById('os-custom-input');
            const versionLabel = document.getElementById('os-version-label');

            // Reset cards highlight
            Object.keys(osData).forEach(key => {
                const card = document.getElementById('os-' + key);
                if (card) {
                    card.classList.remove('border-psti-navy', 'bg-blue-50', 'border-2');
                    card.classList.add('border-gray-200', 'bg-white');
                }

                // Reset icon/text colors if needed (optional)
            });

            // Highlight selected
            const selected = document.getElementById('os-' + osKey);
            if (selected) {
                selected.classList.remove('border-gray-200', 'bg-white');
                selected.classList.add('border-psti-navy', 'bg-blue-50', 'border-2');
            }

            // Show version container
            versionContainer.classList.remove('hidden');

            if (osKey === 'other') {
                versionSelect.classList.add('hidden');
                customInput.classList.remove('hidden');
                customInput.focus();
                versionLabel.innerText = 'Tuliskan OS dan Versi';

                // If switching to Custom, don't clear immediate, let user type
                if (!customInput.value) {
                    osInput.value = customInput.value;
                } else {
                    osInput.value = customInput.value;
                }
            } else {
                versionSelect.classList.remove('hidden');
                customInput.classList.add('hidden');
                versionLabel.innerText = 'Pilih Versi ' + osData[osKey].name;

                // Populate Select
                versionSelect.innerHTML = '<option value="">-- Pilih Versi --</option>';
                osData[osKey].versions.forEach(ver => {
                    const opt = document.createElement('option');
                    opt.value = ver;
                    opt.innerText = ver;
                    versionSelect.appendChild(opt);
                });

                // Clear hidden input until version selected
                osInput.value = '';
            }
        }

        function updateOSInput() {
            const osInput = document.getElementById('operating_system');
            const versionSelect = document.getElementById('os-version-select');
            const customInput = document.getElementById('os-custom-input');

            if (!versionSelect.classList.contains('hidden')) {
                osInput.value = versionSelect.value;
            } else {
                osInput.value = customInput.value;
            }
        }
    </script>
@endsection