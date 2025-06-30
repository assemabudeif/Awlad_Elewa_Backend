@extends('admin.layouts.app')

@section('title', 'إضافة بانر جديد')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">إضافة بانر جديد</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-plus"></i> بيانات البانر</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="image" class="form-label">صورة البانر <span class="text-danger">*</span></label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                <div class="form-text">الأبعاد الموصى بها: 1200x400 بكسل</div>
                @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="link" class="form-label">الرابط (اختياري)</label>
                <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link') }}" placeholder="https://example.com">
                @error('link')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <div id="imagePreview" class="d-none">
                    <img id="preview" src="" alt="معاينة الصورة" class="img-fluid rounded" style="max-height: 200px;">
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ البانر
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush