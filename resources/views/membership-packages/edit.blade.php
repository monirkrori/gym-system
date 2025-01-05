@extends('layouts.dashboard')

@section('title', 'تعديل الباقة')

@push('styles')
    <style>
        body {
            background-color: #f4f7fa;
        }

        .card {
            border-radius: 15px;
        }
    </style>
@endpush

@section('content')
@can('manage-membership-package')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">
                <i class="bi bi-pencil-square me-2"></i> تعديل الباقة
            </h1>
            <a href="{{ route('admin.membership-packages.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> العودة
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.membership-packages.update', $membershipPackage->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الباقة</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $membershipPackage->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">السعر ($)</label>
                        <input type="number" id="price" name="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $membershipPackage->price) }}" step="0.01" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="max_training_sessions" class="form-label">عدد الجلسات التدريبية القصوى</label>
                        <input type="number" id="max_training_sessions" name="max_training_sessions"
                            class="form-control @error('max_training_sessions') is-invalid @enderror"
                            value="{{ old('max_training_sessions', $membershipPackage->max_training_sessions) }}">
                        @error('max_training_sessions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea id="description" name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $membershipPackage->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                         <div class="col-md-6">
                                  <label for="status" class="form-label">الحالة</label>
                                  <select id="status" name="status" class="form-select">
                                      <option value="active" {{ old('status', $membershipPackage->status) == 'active' ? 'selected' : '' }}>نشطة</option>
                                      <option value="expired" {{ old('status', $membershipPackage->status) == 'expired' ? 'selected' : '' }}>غير نشطة</option>
                                  </select>
                         </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@endsection
