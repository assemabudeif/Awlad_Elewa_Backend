@extends('admin.layouts.app')

@section('title', 'الإعدادات')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold text-primary mb-1">الإعدادات</h1>
        <p class="text-muted mb-0">إدارة معلومات التواصل والروابط الاجتماعية</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i> إعدادات التواصل</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                                </label>
                                <input type="email"
                                    class="form-control @error('contact_email') is-invalid @enderror"
                                    id="contact_email"
                                    name="contact_email"
                                    value="{{ old('contact_email', $settings['contact_email']->value ?? '') }}"
                                    placeholder="example@awlad-elewa.com"
                                    required>
                                @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_phone" class="form-label">
                                    <i class="fas fa-phone me-2"></i>رقم الهاتف
                                </label>
                                <input type="text"
                                    class="form-control @error('contact_phone') is-invalid @enderror"
                                    id="contact_phone"
                                    name="contact_phone"
                                    value="{{ old('contact_phone', $settings['contact_phone']->value ?? '') }}"
                                    placeholder="+20 10 0000 0000"
                                    required>
                                @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3 fw-bold text-primary">
                        <i class="fas fa-share-alt me-2"></i>وسائل التواصل الاجتماعي
                    </h6>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="facebook_url" class="form-label">
                                    <i class="fab fa-facebook me-2 text-primary"></i>رابط فيسبوك
                                </label>
                                <input type="url"
                                    class="form-control @error('facebook_url') is-invalid @enderror"
                                    id="facebook_url"
                                    name="facebook_url"
                                    value="{{ old('facebook_url', $settings['facebook_url']->value ?? '') }}"
                                    placeholder="https://facebook.com/awlad-elewa">
                                @error('facebook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="whatsapp_url" class="form-label">
                                    <i class="fab fa-whatsapp me-2 text-success"></i>رابط واتساب
                                </label>
                                <input type="url"
                                    class="form-control @error('whatsapp_url') is-invalid @enderror"
                                    id="whatsapp_url"
                                    name="whatsapp_url"
                                    value="{{ old('whatsapp_url', $settings['whatsapp_url']->value ?? '') }}"
                                    placeholder="https://wa.me/201000000000">
                                @error('whatsapp_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="instagram_url" class="form-label">
                                    <i class="fab fa-instagram me-2 text-danger"></i>رابط انستجرام
                                </label>
                                <input type="url"
                                    class="form-control @error('instagram_url') is-invalid @enderror"
                                    id="instagram_url"
                                    name="instagram_url"
                                    value="{{ old('instagram_url', $settings['instagram_url']->value ?? '') }}"
                                    placeholder="https://instagram.com/awlad-elewa">
                                @error('instagram_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> حفظ الإعدادات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> معلومات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                    </h6>
                    <p class="text-muted mb-0">{{ $settings['contact_email']->value ?? 'لم يتم تعيينه' }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-phone me-2"></i>رقم الهاتف
                    </h6>
                    <p class="text-muted mb-0">{{ $settings['contact_phone']->value ?? 'لم يتم تعيينه' }}</p>
                </div>

                <hr class="my-3">

                <div class="mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="fab fa-facebook me-2"></i>فيسبوك
                    </h6>
                    @if(isset($settings['facebook_url']) && $settings['facebook_url']->value)
                    <a href="{{ $settings['facebook_url']->value }}" target="_blank" class="text-decoration-none">
                        <i class="fas fa-external-link-alt me-1"></i>عرض الصفحة
                    </a>
                    @else
                    <p class="text-muted mb-0">لم يتم تعيينه</p>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="fab fa-whatsapp me-2"></i>واتساب
                    </h6>
                    @if(isset($settings['whatsapp_url']) && $settings['whatsapp_url']->value)
                    <a href="{{ $settings['whatsapp_url']->value }}" target="_blank" class="text-decoration-none">
                        <i class="fas fa-external-link-alt me-1"></i>فتح المحادثة
                    </a>
                    @else
                    <p class="text-muted mb-0">لم يتم تعيينه</p>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="fab fa-instagram me-2"></i>انستجرام
                    </h6>
                    @if(isset($settings['instagram_url']) && $settings['instagram_url']->value)
                    <a href="{{ $settings['instagram_url']->value }}" target="_blank" class="text-decoration-none">
                        <i class="fas fa-external-link-alt me-1"></i>عرض الحساب
                    </a>
                    @else
                    <p class="text-muted mb-0">لم يتم تعيينه</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection