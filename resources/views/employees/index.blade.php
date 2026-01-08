<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Karyawan') }}
            </h2>
            <a href="{{ route('employees.create') }}"
               class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Karyawan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-secondary-50 p-4 text-sm text-secondary-800 border border-secondary-200" role="alert">
                    <span class="font-medium">Berhasil!</span> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-primary-50 p-4 text-sm text-primary-800 border border-primary-200" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            @endif

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4">
                    <form action="{{ route('employees.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Nama, email, atau telepon..."
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div class="w-48">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" id="role"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Semua Role</option>
                                <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                                <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                <option value="waiter" {{ request('role') == 'waiter' ? 'selected' : '' }}>Waiter</option>
                                <option value="kitchen" {{ request('role') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                            </select>
                        </div>
                        <div class="w-40">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                                Filter
                            </button>
                            @if (request('search') || request('role') || request('status'))
                                <a href="{{ route('employees.index') }}"
                                   class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($employees->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum ada karyawan</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan karyawan pertama Anda.</p>
                            <div class="mt-6">
                                <a href="{{ route('employees.create') }}"
                                   class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Karyawan
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($employees as $employee)
                                @php
                                    $role = $employee->pivot->role ?? 'cashier';
                                    $roleColors = [
                                        'owner' => 'bg-purple-100 text-purple-800',
                                        'manager' => 'bg-blue-100 text-blue-800',
                                        'cashier' => 'bg-green-100 text-green-800',
                                        'waiter' => 'bg-yellow-100 text-yellow-800',
                                        'kitchen' => 'bg-orange-100 text-orange-800',
                                    ];
                                    $roleColor = $roleColors[$role] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <div class="bg-white border rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                    {{-- Avatar --}}
                                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 relative flex items-center justify-center">
                                        @if ($employee->avatar)
                                            <img src="{{ Storage::url($employee->avatar) }}" alt="{{ $employee->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                                                <span class="text-4xl font-bold text-white">{{ substr($employee->name, 0, 1) }}</span>
                                            </div>
                                        @endif

                                        {{-- Status Badge --}}
                                        @if (!$employee->is_active)
                                            <div class="absolute top-2 right-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Nonaktif
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="p-4">
                                        <div class="mb-3">
                                            <h3 class="font-semibold text-gray-900 truncate">{{ $employee->name }}</h3>
                                            <p class="text-sm text-gray-500 truncate">{{ $employee->email }}</p>
                                            @if($employee->phone)
                                                <p class="text-sm text-gray-500 truncate">{{ $employee->phone }}</p>
                                            @endif
                                        </div>

                                        {{-- Role Badge --}}
                                        <div class="mb-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColor }}">
                                                {{ ucfirst($role) }}
                                            </span>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex flex-col gap-2 pt-3 border-t">
                                            <div class="flex gap-2">
                                                <a href="{{ route('employees.edit', $employee) }}"
                                                   class="flex-1 text-center px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 border rounded-lg hover:bg-gray-50">
                                                    Edit
                                                </a>
                                                <form action="{{ route('employees.toggle-status', $employee) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full text-center px-3 py-1.5 text-sm rounded-lg {{ $employee->is_active ? 'bg-warning-50 text-warning-700 hover:bg-warning-100' : 'bg-secondary-50 text-secondary-700 hover:bg-secondary-100' }}">
                                                        {{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="flex gap-2">
                                                <form action="{{ route('employees.reset-pin', $employee) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full text-center px-3 py-1.5 text-sm text-blue-600 hover:text-blue-900 border rounded-lg hover:bg-blue-50"
                                                            onclick="return confirm('Reset PIN untuk {{ $employee->name }}?')">
                                                        Reset PIN
                                                    </button>
                                                </form>
                                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="flex-1"
                                                      onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="w-full text-center px-3 py-1.5 text-sm text-red-600 hover:text-red-900 border rounded-lg hover:bg-red-50">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $employees->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
