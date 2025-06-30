@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تفاصيل الطلب</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning ms-2">
            <i class="fas fa-edit"></i> تعديل الحالة
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">بيانات الطلب</h5>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>رقم الطلب:</strong> {{ $order->id }}
            </div>
            <div class="col-md-6">
                <strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>الحالة:</strong>
                @php
                $statusColors = [
                'pending' => 'warning',
                'processing' => 'info',
                'completed' => 'success',
                'cancelled' => 'danger',
                'shipped' => 'primary'
                ];
                $statusText = [
                'pending' => 'قيد الانتظار',
                'processing' => 'قيد المعالجة',
                'completed' => 'مكتمل',
                'cancelled' => 'ملغي',
                'shipped' => 'تم الشحن'
                ];
                @endphp
                <span class="badge bg-{{ $statusColors[$order->status] }}">{{ $statusText[$order->status] }}</span>
            </div>
            <div class="col-md-6">
                <strong>طريقة الدفع:</strong> {{ $order->payment_method }}
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>العنوان:</strong> {{ $order->address ?? '-' }}
            </div>
            <div class="col-md-6">
                <strong>رقم الهاتف:</strong> {{ $order->phone1 }} {{ $order->phone2 ? ' / ' . $order->phone2 : '' }}
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>إجمالي المبلغ:</strong> <span class="text-success">{{ number_format($order->total_price, 2) }} ج.م</span>
            </div>
            <div class="col-md-6">
                <strong>الإجمالي المحسوب:</strong>
                <span class="text-info">{{ number_format($order->calculated_total, 2) }} ج.م</span>
                @if($order->total_discrepancy > 0.01)
                <span class="badge bg-warning">فرق: {{ number_format($order->total_discrepancy, 2) }} ج.م</span>
                @endif
            </div>
        </div>
        <hr>
        <h5 class="mb-3">بيانات العميل</h5>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>الاسم:</strong> {{ $order->user->name }}
            </div>
            <div class="col-md-6">
                <strong>البريد الإلكتروني:</strong> {{ $order->user->email }}
            </div>
        </div>
        <hr>
        <h5 class="mb-3">عناصر الطلب</h5>
        @if($order->orderItems && $order->orderItems->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>
                            <a href="{{ route('admin.products.show', $item->product) }}">{{ $item->product->name }}</a>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                        <td>{{ number_format($item->price, 2) }} ج.م</td>
                    </tr>
                    @endforeach
                    <tr class="table-secondary">
                        <td colspan="4" class="text-end"><strong>الإجمالي:</strong></td>
                        <td><strong>{{ number_format($order->orderItems->sum('price'), 2) }} ج.م</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-3">
            <span class="text-muted">لا توجد عناصر في هذا الطلب</span>
        </div>
        @endif
    </div>
</div>
@endsection