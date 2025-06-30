@extends('admin.layouts.app')

@section('title', 'البانرات')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">البانرات</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة بانر جديد
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-images"></i> قائمة البانرات</h5>
    </div>
    <div class="card-body">
        @if($banners->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>الرابط</th>
                        <th>تاريخ الإضافة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                    <tr>
                        <td>{{ $banner->id }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="بانر" class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank">{{ $banner->link }}</a>
                            @else
                            <span class="text-muted">لا يوجد</span>
                            @endif
                        </td>
                        <td>{{ $banner->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.banners.show', $banner) }}"
                                    class="btn btn-sm btn-info"
                                    title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                    class="btn btn-sm btn-warning"
                                    title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا البانر؟')">
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
        <div class="d-flex justify-content-center mt-4">
            {{ $banners->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-images fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد بانرات</h5>
            <p class="text-muted">ابدأ بإضافة بانر جديد</p>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة بانر جديد
            </a>
        </div>
        @endif
    </div>
</div>
@endsection