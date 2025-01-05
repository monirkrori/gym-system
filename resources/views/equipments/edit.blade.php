@extends('layouts.dashboard')

@section('title', 'تعديل بيانات المعدة')

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
@can('manage-equipment')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">
                <i class="bi bi-pencil-square me-2"></i> تعديل بيانات المعدة
            </h1>
            <a href="{{ route('admin.equipments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> العودة
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.equipments.update', $equipment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">اسم المعدة</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $equipment->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea id="description" name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $equipment->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select id="status" name="status" class="form-select">
                            <option value="available" {{ old('status', $equipment->status) == 'available' ? 'selected' : '' }}>جاهزة</option>
                            <option value="maintenance" {{ old('status', $equipment->status) == 'maintenance' ? 'selected' : '' }}>معطلة</option>
                        </select>
                        @error('status')
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
