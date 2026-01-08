<x-app-layout>
    <x-slot name="title">Daftar Karyawan</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">Daftar Karyawan</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola karyawan dan hak akses outlet</p>
        </div>
        <x-ui.button href="{{ route('employees.create') }}" variant="primary">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Karyawan
        </x-ui.button>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <x-ui.alert variant="success">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </x-ui.alert>
    @endif

    @if (session('error'))
        <x-ui.alert variant="error">
            <span class="font-medium">Error!</span> {{ session('error') }}
        </x-ui.alert>
    @endif

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('employees.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <x-ui.input name="search" label="Cari" placeholder="Nama, email, atau telepon..." :value="request('search')" />
            </div>
            <div class="w-40">
                <x-ui.select name="role" label="Role" :value="request('role')" placeholder="Semua Role">
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="waiter" {{ request('role') == 'waiter' ? 'selected' : '' }}>Waiter</option>
                    <option value="kitchen" {{ request('role') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                </x-ui.select>
            </div>
            <div class="w-36">
                <x-ui.select name="status" label="Status" :value="request('status')" placeholder="Semua Status">
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </x-ui.select>
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </x-ui.button>
                @if (request('search') || request('role') || request('status'))
                    <x-ui.button href="{{ route('employees.index') }}" variant="outline">
                        Reset
                    </x-ui.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Employees Grid -->
    @if ($employees->isEmpty())
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col items-center justify-center py-16 px-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-base font-semibold text-gray-800 dark:text-white/90">Belum ada karyawan</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan karyawan pertama Anda.</p>
                <x-ui.button href="{{ route('employees.create') }}" variant="primary" class="mt-6">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Karyawan
                </x-ui.button>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($employees as $employee)
                @php
                    $role = $employee->pivot->role ?? 'cashier';
                    $roleColors = [
                        'owner' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400',
                        'manager' => 'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400',
                        'cashier' => 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-400',
                        'waiter' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400',
                        'kitchen' => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400',
                    ];
                    $roleColor = $roleColors[$role] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                @endphp
                <div class="rounded-xl border border-gray-200 bg-white overflow-hidden transition-shadow hover:shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">
                    <!-- Avatar -->
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 relative flex items-center justify-center">
                        @if ($employee->avatar)
                            <img src="{{ Storage::url($employee->avatar) }}" alt="{{ $employee->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-brand-500 to-brand-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-4xl font-bold text-white">{{ substr($employee->name, 0, 1) }}</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        @if (!$employee->is_active)
                            <div class="absolute top-3 right-3">
                                <x-ui.badge variant="error">Nonaktif</x-ui.badge>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <div class="mb-3">
                            <h3 class="font-semibold text-gray-800 dark:text-white/90 truncate">{{ $employee->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $employee->email }}</p>
                            @if($employee->phone)
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $employee->phone }}</p>
                            @endif
                        </div>

                        <!-- Role Badge -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $roleColor }}">
                                {{ ucfirst($role) }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex gap-2">
                                <a href="{{ route('employees.edit', $employee) }}"
                                    class="flex-1 flex items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-800 transition-colors">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('employees.toggle-status', $employee) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-1 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $employee->is_active 
                                            ? 'bg-warning-50 text-warning-700 hover:bg-warning-100 dark:bg-warning-500/10 dark:text-warning-400 dark:hover:bg-warning-500/20' 
                                            : 'bg-success-50 text-success-700 hover:bg-success-100 dark:bg-success-500/10 dark:text-success-400 dark:hover:bg-success-500/20' }}">
                                        {{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('employees.reset-pin', $employee) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-brand-600 border border-gray-200 rounded-lg hover:bg-brand-50 dark:text-brand-400 dark:border-gray-700 dark:hover:bg-brand-500/10 transition-colors"
                                        onclick="return confirm('Reset PIN untuk {{ $employee->name }}?')">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                        </svg>
                                        Reset PIN
                                    </button>
                                </form>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="flex-1"
                                    onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-1 px-3 py-2 text-sm font-medium text-error-600 border border-gray-200 rounded-lg hover:bg-error-50 dark:text-error-400 dark:border-gray-700 dark:hover:bg-error-500/10 transition-colors">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $employees->links() }}
        </div>
    @endif
</x-app-layout>
