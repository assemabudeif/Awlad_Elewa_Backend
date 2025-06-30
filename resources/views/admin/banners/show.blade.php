@extends('admin.layouts.app')

@section('title', 'تفاصيل البانر')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تفاصيل البانر</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-warning ms-2">
            <i class="fas fa-edit"></i> تعديل
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">صورة البانر</h5>
        <div class="text-center mb-3">
            <img src="{{ asset('storage/' . $banner->image) }}" alt="بانر" class="img-fluid rounded shadow" style="max-height: 400px;">
        </div>
        <div class="row">
            <div class="col-md-6">
                <strong>الرابط:</strong>
                @if($banner->link)
                <a href="{{ $banner->link }}" target="_blank">{{ $banner->link }}</a>
                @else
                <span class="text-muted">لا يوجد</span>
                @endif
            </div>
            <div class="col-md-6">
                <strong>تاريخ الإضافة:</strong> {{ $banner->created_at->format('Y-m-d H:i') }}
            </div>
        </div>
    </div>
</div>
@endsection