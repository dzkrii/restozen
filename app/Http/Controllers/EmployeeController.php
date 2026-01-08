<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees for current outlet.
     */
    public function index(Request $request)
    {
        $outlet = Auth::user()->current_outlet;

        if (!$outlet) {
            abort(403, 'Tidak ada outlet yang dipilih.');
        }

        // Get employees for current outlet
        $query = $outlet->users()->with('outlets');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->wherePivot('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $employees = $query->orderBy('name', 'asc')->paginate(12);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $outlet = Auth::user()->current_outlet;

        if (!$outlet) {
            abort(403, 'Tidak ada outlet yang dipilih.');
        }

        return view('employees.create');
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        $outlet = Auth::user()->current_outlet;

        if (!$outlet) {
            abort(403, 'Tidak ada outlet yang dipilih.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['owner', 'manager', 'cashier', 'waiter', 'kitchen'])],
            'pin' => 'nullable|digits:6',
            'avatar' => 'nullable|image|max:2048',
            'is_default' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Create user
            $user = User::create([
                'company_id' => $outlet->company_id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'pin' => $validated['pin'] ?? null,
                'is_active' => true,
            ]);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->update(['avatar' => $avatarPath]);
            }

            // Attach to outlet with role
            $user->outlets()->attach($outlet->id, [
                'role' => $validated['role'],
                'is_default' => $request->boolean('is_default', false),
            ]);

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success', 'Karyawan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(User $employee)
    {
        $outlet = Auth::user()->current_outlet;

        if (!$outlet || !$employee->hasAccessTo($outlet)) {
            abort(403);
        }

        // Get employee's role at current outlet
        $currentRole = $employee->roleAt($outlet);

        return view('employees.edit', compact('employee', 'currentRole'));
    }

    /**
     * Update the specified employee.
     */
    public function update(Request $request, User $employee)
    {
        $outlet = Auth::user()->current_outlet;

        if (!$outlet || !$employee->hasAccessTo($outlet)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['owner', 'manager', 'cashier', 'waiter', 'kitchen'])],
            'password' => 'nullable|string|min:8',
            'pin' => 'nullable|digits:6',
            'avatar' => 'nullable|image|max:2048',
            'remove_avatar' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            // Update user details
            $updateData = [
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
            ];

            // Update password if provided
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Update PIN if provided
            if (isset($validated['pin'])) {
                $updateData['pin'] = $validated['pin'];
            }

            $employee->update($updateData);

            // Handle avatar removal
            if ($request->boolean('remove_avatar') && $employee->avatar) {
                \Storage::disk('public')->delete($employee->avatar);
                $employee->update(['avatar' => null]);
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($employee->avatar) {
                    \Storage::disk('public')->delete($employee->avatar);
                }
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $employee->update(['avatar' => $avatarPath]);
            }

            // Update role at outlet
            $employee->outlets()->updateExistingPivot($outlet->id, [
                'role' => $validated['role'],
            ]);

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success', 'Karyawan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal memperbarui karyawan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle employee active status.
     */
    public function toggleStatus(User $employee)
    {
        $outlet = Auth::user()->current_outlet;

        if (!$outlet || !$employee->hasAccessTo($outlet)) {
            abort(403);
        }

        $employee->update([
            'is_active' => !$employee->is_active
        ]);

        $status = $employee->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Karyawan berhasil {$status}.");
    }

    /**
     * Reset employee PIN.
     */
    public function resetPin(User $employee)
    {
        $outlet = Auth::user()->current_outlet;

        if (!$outlet || !$employee->hasAccessTo($outlet)) {
            abort(403);
        }

        // Generate random 6-digit PIN
        $newPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $employee->update(['pin' => $newPin]);

        return back()->with('success', "PIN berhasil direset menjadi: {$newPin}");
    }

    /**
     * Remove the specified employee (soft delete).
     */
    public function destroy(User $employee)
    {
        $outlet = Auth::user()->current_outlet;

        if (!$outlet || !$employee->hasAccessTo($outlet)) {
            abort(403);
        }

        // Detach from outlet
        $employee->outlets()->detach($outlet->id);

        // If employee has no more outlets, soft delete the user
        if ($employee->outlets()->count() === 0) {
            $employee->delete();
        }

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}
