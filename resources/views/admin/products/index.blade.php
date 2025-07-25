@extends('admin.layouts.app')

@section('title', 'المنتجات')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">المنتجات</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة منتج جديد
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-box"></i> قائمة المنتجات</h5>
    </div>
    <div class="card-body">
        @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>الفئة</th>
                        <th>السعر</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                            <img src="{{ asset('public/storage/' . $product->image) }}"
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
                            <span class="badge bg-info">{{ $product->category->name }}</span>
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
                                <form action="{{ route('admin.products.destroy', $product) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
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
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-box fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد منتجات</h5>
            <p class="text-muted">ابدأ بإضافة منتج جديد</p>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة منتج جديد
            </a>
        </div>
        @endif
    </div>
</div>
@endsection