@extends('admin.layouts.app')

@section('title', 'تفاصيل المنتج')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تفاصيل المنتج</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning ms-2">
            <i class="fas fa-edit"></i> تعديل
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="row g-0">
        <div class="col-md-4 d-flex align-items-center justify-content-center p-4">
            @if($product->image)
            <img src="{{ asset('public/storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow" style="max-height: 300px;">
            @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                <i class="fas fa-image fa-3x text-muted"></i>
            </div>
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h3 class="card-title mb-3">{{ $product->name }}</h3>
                <p class="card-text mb-2"><strong>الوصف:</strong> {{ $product->description }}</p>
                <p class="card-text mb-2"><strong>الفئة:</strong> <span class="badge bg-info">{{ $product->category->name }}</span></p>
                <p class="card-text mb-2"><strong>السعر:</strong> <span class="text-success">{{ number_format($product->price, 2) }} ج.م</span></p>
                <p class="card-text"><small class="text-muted">تاريخ الإضافة: {{ $product->created_at->format('Y-m-d H:i') }}</small></p>
            </div>
        </div>
    </div>
</div>
@endsection