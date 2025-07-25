@extends('admin.layouts.app')

@section('title', 'الفئات')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">الفئات</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة فئة جديدة
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-tags"></i> قائمة الفئات</h5>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الصورة</th>
                        <th>عدد المنتجات</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td>
                            @if($category->hasIcon())
                            <img src="{{ asset('public/storage/'.$category->icon) }}"
                                alt="{{ $category->name }}"
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
                            <span class="badge bg-info">{{ $category->products_count }}</span>
                        </td>
                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.categories.show', $category) }}"
                                    class="btn btn-sm btn-info"
                                    title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="btn btn-sm btn-warning"
                                    title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
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
            {{ $categories->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد فئات</h5>
            <p class="text-muted">ابدأ بإضافة فئة جديدة</p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة فئة جديدة
            </a>
        </div>
        @endif
    </div>
</div>
@endsection