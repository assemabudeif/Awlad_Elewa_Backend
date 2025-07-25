<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of admins
     */
    public function index()
    {
        // Only super admin can access admin management
        if (!auth()->guard('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'ليس لديك صلاحية للوصول إلى إدارة المديرين');
        }

        $admins = Admin::latest()->paginate(10);
        return view('admin.admin-management.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        // Only super admin can create new admins
        if (!auth()->guard('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'ليس لديك صلاحية لإنشاء مديرين جدد');
        }

        return view('admin.admin-management.create');
    }

    /**
     * Store a newly created admin
     */
    public function store(Request $request)
    {
        // Only super admin can create new admins
        if (!auth()->guard('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'ليس لديك صلاحية لإنشاء مديرين جدد');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'تم إنشاء المدير بنجاح');
    }

    /**
     * Show the form for editing the specified admin
     */
    public function edit(Admin $admin)
    {
        // Only super admin can edit admins
        if (!auth()->guard('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'ليس لديك صلاحية لتعديل المديرين');
        }

        return view('admin.admin-management.edit', compact('admin'));
    }

    /**
     * Update the specified admin
     */
    public function update(Request $request, Admin $admin)
    {
        // Only super admin can update admins
        if (!auth()->guard('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'ليس لديك صلاحية لتعديل المديرين');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'تم تحديث المدير بنجاح');
    }

    /**
     * Remove the specified admin
     */
    public function destroy(Admin $admin)
    {
        // Only super admin can delete admins
        if (!auth()->guard('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'ليس لديك صلاحية لحذف المديرين');
        }

        // Prevent deleting the super admin (first admin)
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.admin-management.index')
                ->with('error', 'لا يمكن حذف المدير الرئيسي');
        }

        // Prevent deleting the current logged-in admin
        if ($admin->id === auth()->guard('admin')->id()) {
            return redirect()->route('admin.admin-management.index')
                ->with('error', 'لا يمكنك حذف حسابك الحالي');
        }

        $admin->delete();

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'تم حذف المدير بنجاح');
    }

    /**
     * Show admin details
     */
    public function show(Admin $admin)
    {
        // Only super admin can view admin details
        if (!auth()->guard('admin')->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'ليس لديك صلاحية لعرض تفاصيل المديرين');
        }

        return view('admin.admin-management.show', compact('admin'));
    }
}
