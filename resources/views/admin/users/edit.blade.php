@extends('admin.layouts.app')

@section('title', 'تعديل المستخدم')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تعديل المستخدم</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info ms-2">
            <i class="fas fa-eye"></i> تفاصيل المستخدم
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-edit"></i> بيانات المستخدم</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">الاسم <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone1" class="form-label">رقم الهاتف 1 <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1" name="phone1" value="{{ old('phone1', $user->phone1) }}" required>
                @error('phone1')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone2" class="form-label">رقم الهاتف 2</label>
                <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2" name="phone2" value="{{ old('phone2', $user->phone2) }}">
                @error('phone2')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
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