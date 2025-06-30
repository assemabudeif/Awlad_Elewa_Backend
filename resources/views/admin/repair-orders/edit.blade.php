@extends('admin.layouts.app')

@section('title', 'تعديل حالة طلب الإصلاح')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تعديل حالة طلب الإصلاح</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.repair-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('admin.repair-orders.show', $repairOrder) }}" class="btn btn-info ms-2">
            <i class="fas fa-eye"></i> تفاصيل الطلب
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-edit"></i> تحديث حالة الطلب</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.repair-orders.update', $repairOrder) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="pending" {{ old('status', $repairOrder->status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="in_progress" {{ old('status', $repairOrder->status) == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                    <option value="completed" {{ old('status', $repairOrder->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ old('status', $repairOrder->status) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="estimated_cost" class="form-label">التكلفة المقدرة (ج.م)</label>
                        <input type="number" step="0.01" min="0" max="999999.99" class="form-control @error('estimated_cost') is-invalid @enderror"
                            id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $repairOrder->estimated_cost) }}"
                            placeholder="0.00">
                        @error('estimated_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="final_cost" class="form-label">التكلفة النهائية (ج.م)</label>
                        <input type="number" step="0.01" min="0" max="999999.99" class="form-control @error('final_cost') is-invalid @enderror"
                            id="final_cost" name="final_cost" value="{{ old('final_cost', $repairOrder->final_cost) }}"
                            placeholder="0.00">
                        @error('final_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات إضافية</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                    rows="3" maxlength="1000" placeholder="أي ملاحظات إضافية حول الطلب...">{{ old('notes', $repairOrder->notes) }}</textarea>
                @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">الحد الأقصى 1000 حرف</div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.repair-orders.show', $repairOrder) }}" class="btn btn-secondary">
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