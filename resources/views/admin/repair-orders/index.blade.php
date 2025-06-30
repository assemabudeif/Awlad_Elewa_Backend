@extends('admin.layouts.app')

@section('title', 'طلبات الإصلاح')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">طلبات الإصلاح</h1>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-tools"></i> قائمة طلبات الإصلاح</h5>
    </div>
    <div class="card-body">
        @if($repairOrders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>تاريخ الطلب</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repairOrders as $repairOrder)
                    <tr>
                        <td>{{ $repairOrder->id }}</td>
                        <td>{{ $repairOrder->user->name }}</td>
                        <td>{{ Str::limit($repairOrder->description, 40) }}</td>
                        <td>
                            @php
                            $statusColors = [
                            'pending' => 'warning',
                            'in_progress' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                            ];
                            $statusText = [
                            'pending' => 'قيد الانتظار',
                            'in_progress' => 'قيد التنفيذ',
                            'completed' => 'مكتمل',
                            'cancelled' => 'ملغي',
                            ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$repairOrder->status] }}">{{ $statusText[$repairOrder->status] }}</span>
                        </td>
                        <td>{{ $repairOrder->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.repair-orders.show', $repairOrder) }}"
                                    class="btn btn-sm btn-info"
                                    title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.repair-orders.edit', $repairOrder) }}"
                                    class="btn btn-sm btn-warning"
                                    title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.repair-orders.destroy', $repairOrder) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('هل أنت متأكد من حذف طلب الإصلاح هذا؟')">
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
        <div class="d-flex justify-content-center mt-4">
            {{ $repairOrders->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد طلبات إصلاح</h5>
        </div>
        @endif
    </div>
</div>
@endsection