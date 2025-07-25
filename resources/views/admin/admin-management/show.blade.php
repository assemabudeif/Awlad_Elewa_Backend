@extends('admin.layouts.app')

@section('title', 'تفاصيل المدير')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">تفاصيل المدير</h3>
                    <div>
                        <a href="{{ route('admin.admin-management.edit', ['admin' => $admin]) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <a href="{{ route('admin.admin-management.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">الرقم:</th>
                                    <td>{{ $admin->id }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم:</th>
                                    <td>{{ $admin->name }}</td>
                                </tr>
                                <tr>
                                    <th>البريد الإلكتروني:</th>
                                    <td>{{ $admin->email }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $admin->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $admin->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        <span class="badge {{ $admin->getRoleBadgeClass() }}">{{ $admin->getRoleDisplayName() }}</span>
                                        <small class="text-muted d-block mt-1">
                                            @if($admin->isSuperAdmin())
                                                لديه صلاحيات كاملة لإدارة النظام
                                            @else
                                                مدير عادي
                                            @endif
                                        </small>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">إحصائيات المدير</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>نوع الحساب:</strong>
                                        @if($admin->isSuperAdmin())
                                        مدير رئيسي (صلاحيات كاملة)
                                        @else
                                        مدير عادي
                                        @endif
                                    </p>

                                    @if($admin->id === auth()->guard('admin')->id())
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        هذا هو حسابك الحالي
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection