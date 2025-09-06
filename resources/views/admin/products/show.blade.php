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
                <p class="card-text mb-2"><strong>عدد الصور:</strong> <span class="badge bg-secondary">{{ $product->images->count() + ($product->image ? 1 : 0) }}</span></p>
                <p class="card-text"><small class="text-muted">تاريخ الإضافة: {{ $product->created_at->format('Y-m-d H:i') }}</small></p>
            </div>
        </div>
    </div>
</div>

@if($product->images->count() > 0)
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-images"></i> الصور الإضافية ({{ $product->images->count() }})</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($product->images as $image)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="position-relative">
                    <img src="{{ asset('public/storage/' . $image->image_path) }}"
                        alt="صورة المنتج"
                        class="img-fluid rounded shadow"
                        style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#imageModal{{ $image->id }}">
                    <div class="position-absolute top-0 end-0 p-1">
                        <span class="badge bg-dark">{{ $image->sort_order + 1 }}</span>
                    </div>
                </div>
            </div>

            <!-- Image Modal -->
            <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">صورة المنتج - {{ $image->sort_order + 1 }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('public/storage/' . $image->image_path) }}"
                                alt="صورة المنتج"
                                class="img-fluid rounded">
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('admin.products.remove-image', ['product' => $product, 'image' => $image]) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> حذف الصورة
                                </button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Add Images Form -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-plus"></i> إضافة صور جديدة</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.add-images', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="images" class="form-label">اختر الصور</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror"
                            id="images" name="images[]" accept="image/*" multiple required>
                        <div class="form-text">يمكنك اختيار عدة صور في نفس الوقت</div>
                        @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <div id="imagesPreview" class="d-none">
                            <h6>معاينة الصور:</h6>
                            <div id="previewContainer" class="row g-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة الصور
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Multiple images preview for add images form
    document.getElementById('images').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const previewDiv = document.getElementById('imagesPreview');
        const container = document.getElementById('previewContainer');

        // Clear previous previews
        container.innerHTML = '';

        if (files.length > 0) {
            previewDiv.classList.remove('d-none');

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-md-4';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" alt="معاينة ${index + 1}" 
                                 class="img-fluid rounded" style="height: 80px; width: 100%; object-fit: cover;">
                            <small class="text-muted d-block text-center mt-1">صورة ${index + 1}</small>
                        </div>
                    `;
                    container.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        } else {
            previewDiv.classList.add('d-none');
        }
    });
</script>
@endpush