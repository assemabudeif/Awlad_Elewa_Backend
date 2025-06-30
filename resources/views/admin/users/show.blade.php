@extends('admin.layouts.app')

@section('title', 'تفاصيل المستخدم')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">تفاصيل المستخدم</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning ms-2">
            <i class="fas fa-edit"></i> تعديل
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h3 class="card-title mb-3">{{ $user->name }}</h3>
        <p class="card-text mb-2"><strong>البريد الإلكتروني:</strong> {{ $user->email }}</p>
        <p class="card-text mb-2"><strong>رقم الهاتف 1:</strong> {{ $user->phone1 }}</p>
        <p class="card-text mb-2"><strong>رقم الهاتف 2:</strong> {{ $user->phone2 ?? '-' }}</p>
        <p class="card-text mb-2">
            <strong>تأكيد البريد:</strong>
            @if($user->email_verified_at)
            <span class="badge bg-success">مؤكد</span>
            @else
            <span class="badge bg-warning">غير مؤكد</span>
            @endif
        </p>
        <p class="card-text"><small class="text-muted">تاريخ التسجيل: {{ $user->created_at->format('Y-m-d H:i') }}</small></p>
    </div>
</div>
@endsection