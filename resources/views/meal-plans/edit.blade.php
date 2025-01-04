@extends('layouts.dashboard')

@section('title', 'تعديل خطة الوجبات')

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
@can('manage-meal-plans')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">
                <i class="bi bi-pencil-square me-2"></i> تعديل خطة الوجبات
            </h1>
            <a href="{{ route('admin.meal-plans.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> العودة
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.meal-plans.update', $mealPlan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الخطة</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $mealPlan->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="calories_per_day" class="form-label">السعرات الحرارية اليومية</label>
                        <input type="number" id="calories_per_day" name="calories_per_day"
                            class="form-control @error('calories_per_day') is-invalid @enderror"
                            value="{{ old('calories_per_day', $mealPlan->calories_per_day) }}" required>
                        @error('calories_per_day')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea id="description" name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $mealPlan->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
