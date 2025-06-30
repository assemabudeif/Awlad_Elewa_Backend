@extends('admin.layouts.app')

@section('title', 'الطلبات')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">الطلبات</h1>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> قائمة الطلبات</h5>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>إجمالي المبلغ</th>
                        <th>الحالة</th>
                        <th>طريقة الدفع</th>
                        <th>تاريخ الطلب</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            <strong>{{ $order->user->name }}</strong>
                            <br><small class="text-muted">{{ $order->user->email }}</small>
                            <br><small class="text-muted">{{ $order->phone1 }}</small>
                        </td>
                        <td>
                            <strong class="text-success">{{ number_format($order->total_price, 2) }} ج.م</strong>
                        </td>
                        <td>
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
                            <span class="badge bg-{{ $statusColors[$order->status] }}">
                                {{ $statusText[$order->status] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $order->payment_method }}</span>
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="btn btn-sm btn-info"
                                    title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order) }}"
                                    class="btn btn-sm btn-warning"
                                    title="تعديل الحالة">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
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
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد طلبات</h5>
            <p class="text-muted">لم يتم إنشاء أي طلبات بعد</p>
        </div>
        @endif
    </div>
</div>
@endsection