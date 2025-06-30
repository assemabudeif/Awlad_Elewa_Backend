@extends('admin.layouts.app')

@section('title', 'تعديل الفئة')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تعديل الفئة</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-edit"></i> بيانات الفئة</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">اسم الفئة <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="icon" class="form-label">صورة الفئة</label>

                @if($category->hasIcon())
                <div class="mb-3">
                    <label class="form-label">الصورة الحالية:</label>
                    <div class="border rounded p-3 text-center">
                        <img src="{{ $category->icon_url }}"
                            alt="{{ $category->name }}"
                            class="img-thumbnail"
                            style="max-width: 150px; max-height: 150px; object-fit: cover;">
                    </div>
                </div>
                @endif

                <input type="file"
                    class="form-control @error('icon') is-invalid @enderror"
                    id="icon"
                    name="icon"
                    accept="image/*">
                <div class="form-text">الصيغ المدعومة: JPG, PNG, GIF, WebP. الحجم الأقصى: 2MB</div>
                @error('icon')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mt-3">
                    <div id="icon_preview" class="d-none">
                        <label class="form-label">معاينة الصورة الجديدة:</label>
                        <div class="border rounded p-3 text-center">
                            <img id="image_preview" src="" alt="معاينة" class="img-thumbnail d-none" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iconFile = document.getElementById('icon');
        const iconPreview = document.getElementById('icon_preview');
        const imagePreview = document.getElementById('image_preview');

        function updatePreview() {
            iconPreview.classList.add('d-none');
            imagePreview.classList.add('d-none');

            if (iconFile.files && iconFile.files[0]) {
                // Show image preview
                const file = iconFile.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                    iconPreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        }

        iconFile.addEventListener('change', updatePreview);
    });
</script>
@endsection