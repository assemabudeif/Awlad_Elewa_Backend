@extends('admin.layouts.app')

@section('title', 'تفاصيل طلب الإصلاح')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تفاصيل طلب الإصلاح</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.repair-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('admin.repair-orders.edit', $repairOrder) }}" class="btn btn-warning ms-2">
            <i class="fas fa-edit"></i> تعديل الحالة
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">بيانات الطلب</h5>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>رقم الطلب:</strong> {{ $repairOrder->id }}
            </div>
            <div class="col-md-6">
                <strong>تاريخ الطلب:</strong> {{ $repairOrder->created_at->format('Y-m-d H:i') }}
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>الحالة:</strong>
                <span class="badge bg-{{ $repairOrder->status_color }}">{{ $repairOrder->status_text }}</span>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <strong>الوصف:</strong> {{ $repairOrder->description }}
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>رقم الهاتف 1:</strong> {{ $repairOrder->phone1 }}
            </div>
            <div class="col-md-6">
                <strong>رقم الهاتف 2:</strong> {{ $repairOrder->phone2 ?? '-' }}
            </div>
        </div>
        @if($repairOrder->estimated_cost || $repairOrder->final_cost)
        <div class="row mb-2">
            @if($repairOrder->estimated_cost)
            <div class="col-md-6">
                <strong>التكلفة المقدرة:</strong>
                <span class="text-info">{{ number_format($repairOrder->estimated_cost, 2) }} ج.م</span>
            </div>
            @endif
            @if($repairOrder->final_cost)
            <div class="col-md-6">
                <strong>التكلفة النهائية:</strong>
                <span class="text-success">{{ number_format($repairOrder->final_cost, 2) }} ج.م</span>
            </div>
            @endif
        </div>
        @endif
        @if($repairOrder->notes)
        <div class="row mb-2">
            <div class="col-md-12">
                <strong>ملاحظات:</strong> {{ $repairOrder->notes }}
            </div>
        </div>
        @endif
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>صورة:</strong>
                @if($repairOrder->hasPhoto())
                <div class="mt-2">
                    <img src="{{ $repairOrder->photo_url }}" alt="صورة الطلب" class="img-fluid rounded shadow" style="max-height: 200px;">
                    <div class="mt-1">
                        <a href="{{ $repairOrder->photo_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-expand"></i> عرض بالحجم الكامل
                        </a>
                    </div>
                </div>
                @else
                <span class="text-muted">لا توجد صورة</span>
                @endif
            </div>
            <div class="col-md-6">
                <strong>تسجيل صوتي:</strong>
                @if($repairOrder->hasAudio())
                <div class="mt-2">
                    <audio controls class="w-100">
                        <source src="{{ $repairOrder->audio_url }}" type="audio/mpeg">
                        <source src="{{ $repairOrder->audio_url }}" type="audio/wav">
                        <source src="{{ $repairOrder->audio_url }}" type="audio/ogg">
                        متصفحك لا يدعم تشغيل الصوت.
                    </audio>
                    <div class="mt-1">
                        <a href="{{ $repairOrder->audio_url }}" download class="btn btn-sm btn-outline-success">
                            <i class="fas fa-download"></i> تحميل الملف الصوتي
                        </a>
                    </div>
                </div>
                @else
                <span class="text-muted">لا يوجد تسجيل صوتي</span>
                @endif
            </div>
        </div>
        <hr>
        <h5 class="mb-3">بيانات العميل</h5>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>الاسم:</strong> {{ $repairOrder->user->name }}
            </div>
            <div class="col-md-6">
                <strong>البريد الإلكتروني:</strong> {{ $repairOrder->user->email }}
            </div>
        </div>
    </div>
</div>
@endsection