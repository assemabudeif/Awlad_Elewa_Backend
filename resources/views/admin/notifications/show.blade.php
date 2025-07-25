@extends('admin.layouts.app')

@section('title', 'عرض الإشعار')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    النظام
                </div>
                <h2 class="page-title">
                    عرض الإشعار
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none" />
                            <path d="M5 12l14 0" />
                            <path d="m12 5l-7 7l7 7" />
                        </svg>
                        العودة للقائمة
                    </a>

                    @if($notification->status !== 'sent')
                    <a href="{{ route('admin.notifications.edit', $notification) }}" class="btn btn-primary d-none d-sm-inline-block">
                        تعديل الإشعار
                    </a>

                    @if($notification->status === 'draft')
                    <form action="{{ route('admin.notifications.send', $notification) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success d-none d-sm-inline-block" onclick="return confirm('هل أنت متأكد من إرسال هذا الإشعار؟')">
                            إرسال الإشعار
                        </button>
                    </form>
                    @endif
                    @endif
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
                <div>{{ session('success') }}</div>
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
                <div>{{ session('error') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">تفاصيل الإشعار</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">عنوان الإشعار:</div>
                            <div class="col-sm-9">{{ $notification->title }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">محتوى الإشعار:</div>
                            <div class="col-sm-9">{{ $notification->body }}</div>
                        </div>

                        @if($notification->image)
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">صورة الإشعار:</div>
                            <div class="col-sm-9">
                                <img src="{{ asset('public/storage/' . $notification->image) }}" alt="صورة الإشعار" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">نوع الإرسال:</div>
                            <div class="col-sm-9">
                                @if($notification->type === 'all_users')
                                <span class="badge bg-blue">جميع المستخدمين</span>
                                @elseif($notification->type === 'specific_users')
                                <span class="badge bg-green">مستخدمون محددون</span>
                                @else
                                <span class="badge bg-yellow">متابعي الفئة</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">الحالة:</div>
                            <div class="col-sm-9">
                                @if($notification->status === 'draft')
                                <span class="badge bg-secondary">مسودة</span>
                                @elseif($notification->status === 'scheduled')
                                <span class="badge bg-warning">مجدول</span>
                                @elseif($notification->status === 'sent')
                                <span class="badge bg-success">تم الإرسال</span>
                                @else
                                <span class="badge bg-danger">فشل</span>
                                @endif
                            </div>
                        </div>

                        @if($notification->scheduled_at)
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">موعد الإرسال المجدول:</div>
                            <div class="col-sm-9">{{ $notification->formatted_scheduled_at }}</div>
                        </div>
                        @endif

                        @if($notification->sent_at)
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">تاريخ الإرسال:</div>
                            <div class="col-sm-9">{{ $notification->formatted_sent_at }}</div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">عدد المرسل إليهم:</div>
                            <div class="col-sm-9">{{ $notification->sent_count }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">تاريخ الإنشاء:</div>
                            <div class="col-sm-9">{{ $notification->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">آخر تحديث:</div>
                            <div class="col-sm-9">{{ $notification->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($notification->type === 'specific_users' && $notification->sent_to)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">المستخدمون المحددون</h3>
                    </div>
                    <div class="card-body">
                        @php
                        $userIds = json_decode($notification->sent_to, true) ?? [];
                        $selectedUsers = \App\Models\User::whereIn('id', $userIds)->get();
                        @endphp

                        @if($selectedUsers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($selectedUsers as $user)
                            <div class="list-group-item d-flex align-items-center px-0">
                                <div class="avatar avatar-sm me-3">{{ substr($user->name, 0, 2) }}</div>
                                <div>
                                    <div class="text-body">{{ $user->name }}</div>
                                    <div class="text-muted">{{ $user->email }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted">لا توجد بيانات للمستخدمين</p>
                        @endif
                    </div>
                </div>
                @endif

                @if($notification->type === 'category_followers' && $notification->sent_to)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">الفئات المحددة</h3>
                    </div>
                    <div class="card-body">
                        @php
                        $categoryIds = json_decode($notification->sent_to, true) ?? [];
                        $selectedCategories = \App\Models\Category::whereIn('id', $categoryIds)->get();
                        @endphp

                        @if($selectedCategories->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($selectedCategories as $category)
                            <div class="list-group-item px-0">
                                <div class="text-body">{{ $category->name }}</div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted">لا توجد بيانات للفئات</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection