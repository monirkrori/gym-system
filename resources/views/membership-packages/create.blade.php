@extends('layouts.dashboard')

@section('title', 'إضافة باقة عضوية جديدة')

@section('content')
@can('manage-membership-package')
    <div class="container-fluid">
       @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2"></i> إضافة باقة عضوية جديدة
            </h1>
            <a href="{{ route('admin.membership-packages.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> العودة إلى القائمة
            </a>

        </div>

        <!-- Form Section -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.membership-packages.store') }}" method="POST">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الباقة <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="أدخل اسم الباقة">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price Field -->
                    <div class="mb-3">
                        <label for="price" class="form-label">السعر ($) <span class="text-danger">*</span></label>
                        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price') }}" placeholder="أدخل سعر الباقة" min="0" step="0.01">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Max Training Sessions Field -->
                    <div class="mb-3">
                        <label for="max_training_sessions" class="form-label">عدد الجلسات التدريبية </label>
                        <input type="number" id="max_training_sessions" name="max_training_sessions"
                            class="form-control @error('max_training_sessions') is-invalid @enderror"
                            value="{{ old('max_training_sessions') }}" placeholder="أدخل عدد الجلسات التدريبية" min="0">
                        @error('max_training_sessions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف (اختياري)</label>
                        <textarea id="description" name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="أدخل وصفًا للباقه">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Active Field -->
                  <div class="col-md-6">
                    <label for="status" class="form-label">الحالة</label>
                    <select id="status" name="status" class="form-select">
                    <option value="active" selected>نشطة</option>
                    <option value="expired">غير نشطة</option>
                     </select>
                  </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> حفظ الباقة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@endsection
