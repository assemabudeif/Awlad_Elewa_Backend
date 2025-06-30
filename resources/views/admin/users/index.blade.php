@extends('admin.layouts.app')

@section('title', 'المستخدمين')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">المستخدمين</h1>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-users"></i> قائمة المستخدمين</h5>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                        </td>
                        <td>
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                {{ $user->email }}
                            </a>
                            @if($user->email_verified_at)
                            <br><small class="text-success"><i class="fas fa-check-circle"></i> مؤكد</small>
                            @else
                            <br><small class="text-warning"><i class="fas fa-exclamation-circle"></i> غير مؤكد</small>
                            @endif
                        </td>
                        <td>
                            <a href="tel:{{ $user->phone1 }}" class="text-decoration-none">
                                {{ $user->phone1 }}
                            </a>
                            @if($user->phone2)
                            <br><small class="text-muted">{{ $user->phone2 }}</small>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                    class="btn btn-sm btn-info"
                                    title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="btn btn-sm btn-warning"
                                    title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا يوجد مستخدمين</h5>
            <p class="text-muted">لم يتم تسجيل أي مستخدمين بعد</p>
        </div>
        @endif
    </div>
</div>
@endsection