@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold text-primary mb-1">لوحة التحكم</h1>
        <p class="text-muted mb-0">مرحباً بك في لوحة إدارة أولاد عليوة</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="badge bg-light text-dark px-3 py-2">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ now()->format('Y-m-d') }}
            </span>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ number_format($stats['total_users']) }}</div>
                    <div class="stats-label">إجمالي المستخدمين</div>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ number_format($stats['total_products']) }}</div>
                    <div class="stats-label">إجمالي المنتجات</div>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ number_format($stats['total_orders']) }}</div>
                    <div class="stats-label">إجمالي الطلبات</div>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ number_format($stats['total_repairs']) }}</div>
                    <div class="stats-label">طلبات الإصلاح</div>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> إحصائيات الطلبات</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end border-2">
                            <h4 class="text-warning fw-bold">{{ $stats['pending_orders'] }}</h4>
                            <small class="text-muted fw-semibold">قيد الانتظار</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end border-2">
                            <h4 class="text-success fw-bold">{{ $stats['completed_orders'] }}</h4>
                            <small class="text-muted fw-semibold">مكتملة</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div>
                            <h4 class="text-primary fw-bold">{{ number_format($stats['total_revenue'], 2) }} ج.م</h4>
                            <small class="text-muted fw-semibold">إجمالي الإيرادات</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i> الطلبات الحديثة</h5>
            </div>
            <div class="card-body">
                @if($stats['recent_orders']->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($stats['recent_orders'] as $order)
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                        <div>
                            <h6 class="mb-1 fw-semibold">{{ $order->user->name }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $order->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'primary') }} rounded-pill px-3 py-2">
                            {{ $order->status === 'completed' ? 'مكتمل' : ($order->status === 'pending' ? 'قيد الانتظار' : 'قيد المعالجة') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">لا توجد طلبات حديثة</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-plus me-2"></i> إضافة منتج جديد
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-success w-100 py-3">
                            <i class="fas fa-plus me-2"></i> إضافة فئة جديدة
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.banners.create') }}" class="btn btn-warning w-100 py-3">
                            <i class="fas fa-plus me-2"></i> إضافة بانر جديد
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-info w-100 py-3">
                            <i class="fas fa-eye me-2"></i> عرض جميع الطلبات
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection