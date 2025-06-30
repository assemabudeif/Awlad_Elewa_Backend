@extends('admin.layouts.app')

@section('title', 'تعديل حالة الطلب')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تعديل حالة الطلب</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info ms-2">
            <i class="fas fa-eye"></i> تفاصيل الطلب
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-edit"></i> تحديث حالة الطلب</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection