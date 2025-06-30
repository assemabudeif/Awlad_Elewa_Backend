@extends('admin.layouts.app')

@section('title', 'تعديل الإشعار')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    النظام
                </div>
                <h2 class="page-title">
                    تعديل الإشعار
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="m0 0h24v24H0z" fill="none" />
                            <path d="M5 12l14 0" />
                            <path d="m12 5l-7 7l7 7" />
                        </svg>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">تعديل بيانات الإشعار</h3>
                    </div>

                    <form action="{{ route('admin.notifications.update', $notification) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="form-label required">عنوان الإشعار</label>
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title', $notification->title) }}" placeholder="أدخل عنوان الإشعار" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">محتوى الإشعار</label>
                                        <textarea name="body" rows="4" class="form-control @error('body') is-invalid @enderror"
                                            placeholder="أدخل محتوى الإشعار" required>{{ old('body', $notification->body) }}</textarea>
                                        @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">صورة الإشعار (اختيارية)</label>
                                        @if($notification->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $notification->image) }}" alt="صورة الإشعار الحالية" class="img-thumbnail" style="max-width: 200px;">
                                            <div class="form-text">الصورة الحالية</div>
                                        </div>
                                        @endif
                                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                            accept="image/*">
                                        <small class="form-hint">الحد الأقصى: 2MB. الصيغ المدعومة: JPG, PNG, GIF. اتركه فارغاً للاحتفاظ بالصورة الحالية</small>
                                        @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label required">نوع الإرسال</label>
                                        <select name="type" class="form-select @error('type') is-invalid @enderror" id="notification-type" required>
                                            <option value="">اختر نوع الإرسال</option>
                                            <option value="all_users" {{ old('type', $notification->type) === 'all_users' ? 'selected' : '' }}>جميع المستخدمين</option>
                                            <option value="specific_users" {{ old('type', $notification->type) === 'specific_users' ? 'selected' : '' }}>مستخدمون محددون</option>
                                            <option value="category_followers" {{ old('type', $notification->type) === 'category_followers' ? 'selected' : '' }}>متابعي فئة معينة</option>
                                        </select>
                                        @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3" id="specific-users-section" style="display: none;">
                                        <label class="form-label">المستخدمون المحددون</label>
                                        <select name="sent_to[]" class="form-select" multiple size="8">
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('sent_to', $notification->sent_to ?? [])) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="form-hint">اضغط Ctrl لتحديد عدة مستخدمين</small>
                                    </div>

                                    <div class="mb-3" id="category-followers-section" style="display: none;">
                                        <label class="form-label">الفئات</label>
                                        <select name="sent_to[]" class="form-select" multiple size="6">
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, old('sent_to', $notification->sent_to ?? [])) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="form-hint">اضغط Ctrl لتحديد عدة فئات</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">جدولة الإرسال (اختيارية)</label>
                                        <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror"
                                            value="{{ old('scheduled_at', $notification->scheduled_at ? $notification->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                        <small class="form-hint">اتركه فارغاً للإرسال يدوياً</small>
                                        @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-check">
                                            <input type="checkbox" name="send_now" value="1" class="form-check-input" {{ old('send_now') ? 'checked' : '' }}>
                                            <span class="form-check-label">إرسال فوراً بعد الحفظ</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <div class="d-flex">
                                <a href="{{ route('admin.notifications.index') }}" class="btn btn-link">إلغاء</a>
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="m0 0h24v24H0z" fill="none" />
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M14 4l0 4l-6 0l0 -4" />
                                    </svg>
                                    تحديث الإشعار
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('notification-type');
        const specificUsersSection = document.getElementById('specific-users-section');
        const categoryFollowersSection = document.getElementById('category-followers-section');

        function toggleSections() {
            const selectedType = typeSelect.value;

            specificUsersSection.style.display = selectedType === 'specific_users' ? 'block' : 'none';
            categoryFollowersSection.style.display = selectedType === 'category_followers' ? 'block' : 'none';
        }

        typeSelect.addEventListener('change', toggleSections);
        toggleSections(); // Initial call
    });
</script>
@endsection