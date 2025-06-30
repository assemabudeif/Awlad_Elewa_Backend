@extends('admin.layouts.app')

@section('title', 'عرض الفئة')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">عرض الفئة</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> تفاصيل الفئة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2">اسم الفئة</h6>
                        <p class="mb-3">{{ $category->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2">تاريخ الإنشاء</h6>
                        <p class="mb-3">{{ $category->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2">عدد المنتجات</h6>
                        <p class="mb-3">
                            <span class="badge bg-info fs-6">{{ $category->products_count }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2">آخر تحديث</h6>
                        <p class="mb-3">{{ $category->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-image"></i> أيقونة الفئة</h5>
            </div>
            <div class="card-body text-center">
                @if($category->hasIcon())
                <img src="{{ $category->icon_url }}"
                    alt="{{ $category->name }}"
                    class="img-fluid rounded"
                    style="max-width: 200px; max-height: 200px; object-fit: cover;">
                @else
                <div class="py-5">
                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                    <p class="text-muted">لا توجد صورة</p>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> الإجراءات</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل الفئة
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                            <i class="fas fa-trash"></i> حذف الفئة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($category->products->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-box"></i> منتجات الفئة</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>السعر</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="img-thumbnail"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->description)
                            <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <strong class="text-success">{{ number_format($product->price, 2) }} ج.م</strong>
                        </td>
                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.show', $product) }}"
                                    class="btn btn-sm btn-info"
                                    title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="btn btn-sm btn-warning"
                                    title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection