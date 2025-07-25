@extends('admin.layouts.app')

@section('title', 'إدارة المديرين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">إدارة المديرين</h3>
                        <small class="text-white-50">فقط المدير الرئيسي يمكنه إدارة المديرين الآخرين</small>
                    </div>
                    <a href="{{ route('admin.admin-management.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة مدير جديد
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>
                                            <span class="badge {{ $admin->getRoleBadgeClass() }}">{{ $admin->getRoleDisplayName() }}</span>
                                        </td>
                                        <td>{{ $admin->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                                                                            <a href="{{ route('admin.admin-management.show', ['admin' => $admin]) }}" 
                                                   class="btn btn-sm btn-info" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.admin-management.edit', ['admin' => $admin]) }}" 
                                                   class="btn btn-sm btn-warning" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                                                                @if(!$admin->isSuperAdmin() && $admin->id !== auth()->guard('admin')->id())
                                                    <form action="{{ route('admin.admin-management.destroy', ['admin' => $admin]) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المدير؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد مديرين</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($admins->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $admins->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection