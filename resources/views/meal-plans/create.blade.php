@extends('layouts.dashboard')

@section('title', 'إضافة خطة وجبات جديدة')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
@can('manage-meal-plans')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-calendar-plus me-2"></i> إضافة خطة وجبات جديدة
            </h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.meal-plans.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- اسم الخطة -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">اسم الخطة</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- السعرات الحرارية اليومية -->
                            <div class="col-md-6 mb-3">
                                <label for="calories_per_day" class="form-label">السعرات الحرارية اليومية</label>
                                <input type="number" name="calories_per_day" id="calories_per_day" class="form-control @error('calories_per_day') is-invalid @enderror" value="{{ old('calories_per_day') }}" required>
                                @error('calories_per_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الوصف -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">حفظ</button>
                                <a href="{{ route('admin.meal-plans.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
@endcan
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
@endpush
