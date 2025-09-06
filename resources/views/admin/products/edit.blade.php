@extends('admin.layouts.app')

@section('title', 'تعديل المنتج')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تعديل المنتج</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-edit"></i> بيانات المنتج</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">وصف المنتج <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">السعر <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                    <span class="input-group-text">ج.م</span>
                                </div>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">الفئة <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">اختر الفئة</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">الصورة الرئيسية</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <div class="form-text">الأبعاد الموصى بها: 800x600 بكسل</div>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div id="imagePreview" class="{{ $product->image ? '' : 'd-none' }}">
                            <img id="preview" src="{{ $product->image ? asset('public/storage/' . $product->image) : '' }}" alt="معاينة الصورة" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">صور إضافية جديدة</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror"
                            id="images" name="images[]" accept="image/*" multiple>
                        <div class="form-text">يمكنك اختيار عدة صور في نفس الوقت (ستستبدل الصور الحالية)</div>
                        @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div id="imagesPreview" class="d-none">
                            <h6>معاينة الصور الجديدة:</h6>
                            <div id="previewContainer" class="row g-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            @if($product->images->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-images"></i> الصور الحالية</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($product->images as $image)
                                <div class="col-md-3 col-sm-4 col-6">
                                    <div class="position-relative">
                                        <img src="{{ asset('public/storage/' . $image->image_path) }}"
                                            alt="صورة المنتج"
                                            class="img-fluid rounded"
                                            style="height: 120px; width: 100%; object-fit: cover;">
                                        <div class="position-absolute top-0 end-0 p-1">
                                            <form action="{{ route('admin.products.remove-image', ['product' => $product, 'image' => $image]) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <small class="text-muted d-block text-center mt-1">ترتيب: {{ $image->sort_order + 1 }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
    // Main image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        const previewDiv = document.getElementById('imagePreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewDiv.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            previewDiv.classList.add('d-none');
        }
    });

    // Multiple images preview
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
                                 class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: cover;">
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