@extends('admin.layouts.app')

@section('title', 'تعديل المدير')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تعديل المدير: {{ $admin->name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.admin-management.update', ['admin' => $admin]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">الاسم <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $admin->name) }}"
                                        required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $admin->email) }}"
                                        required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">كلمة المرور الجديدة (اختياري)</label>
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password">
                                    <small class="form-text text-muted">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</small>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.admin-management.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right"></i> رجوع
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection