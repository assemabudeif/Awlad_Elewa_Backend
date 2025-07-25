@extends('admin.layouts.app')

@section('title', 'إدارة الإشعارات')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    النظام
                </div>
                <h2 class="page-title">
                    إدارة الإشعارات
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none" />
                            <path d="m12 5l0 14" />
                            <path d="m5 12l14 0" />
                        </svg>
                        إنشاء إشعار جديد
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="m0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="m0 0h24v24H0z" fill="none" />
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <path d="m12 9l0 4" />
                        <path d="m12 17l.01 0" />
                    </svg>
                </div>
                <div>
                    {{ session('error') }}
                </div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">جميع الإشعارات</h3>
                    </div>

                    @if($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>العنوان</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>عدد المرسل إليهم</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>تاريخ الإرسال</th>
                                    <th class="w-1">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                <tr>
                                    <td>
                                        <div class="d-flex py-1 align-items-center">
                                            @if($notification->image)
                                            <span class="avatar me-2" style="background-image: url('{{ asset('public/storage/' . $notification->image) }}')"></span>
                                            @endif
                                            <div class="flex-fill">
                                                <div class="font-weight-medium">{{ $notification->title }}</div>
                                                <div class="text-muted">{{ Str::limit($notification->body, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($notification->type === 'all_users')
                                        <span class="badge bg-blue">جميع المستخدمين</span>
                                        @elseif($notification->type === 'specific_users')
                                        <span class="badge bg-green">مستخدمون محددون</span>
                                        @else
                                        <span class="badge bg-yellow">متابعي الفئة</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification->status === 'draft')
                                        <span class="badge bg-secondary">مسودة</span>
                                        @elseif($notification->status === 'scheduled')
                                        <span class="badge bg-warning">مجدول</span>
                                        @elseif($notification->status === 'sent')
                                        <span class="badge bg-success">تم الإرسال</span>
                                        @else
                                        <span class="badge bg-danger">فشل</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $notification->sent_count }}
                                    </td>
                                    <td>
                                        {{ $notification->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $notification->formatted_sent_at }}
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="{{ route('admin.notifications.show', $notification) }}" class="btn btn-white btn-sm">
                                                عرض
                                            </a>

                                            @if($notification->status !== 'sent')
                                            <a href="{{ route('admin.notifications.edit', $notification) }}" class="btn btn-white btn-sm">
                                                تعديل
                                            </a>

                                            @if($notification->status === 'draft')
                                            <form action="{{ route('admin.notifications.send', $notification) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('هل أنت متأكد من إرسال هذا الإشعار؟')">
                                                    إرسال
                                                </button>
                                            </form>
                                            @endif

                                            <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                                                    حذف
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex align-items-center">
                        {{ $notifications->links() }}
                    </div>
                    @else
                    <div class="card-body">
                        <div class="empty">
                            <div class="empty-img"><img src="{{ asset('images/undraw_printing_invoices_5r4r.svg') }}" height="128" alt="">
                            </div>
                            <p class="empty-title">لا توجد إشعارات حتى الآن</p>
                            <p class="empty-subtitle text-muted">
                                قم بإنشاء أول إشعار لك لبدء التواصل مع المستخدمين
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                                    إنشاء إشعار جديد
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection