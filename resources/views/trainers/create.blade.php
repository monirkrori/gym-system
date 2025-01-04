@extends('layouts.dashboard')

@section('title', 'إضافة مدرب')

@section('content')
@can('create-trainer')
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
                <i class="bi bi-person-plus-fill me-2"></i> إضافة مدرب جديد
            </h1>
            <a href="{{ route('admin.trainers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> العودة إلى القائمة
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.trainers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- المستخدم -->
                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label">المستخدم</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" id="user_id" required>
                                <option value="">اختر المستخدم</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- التخصص -->
                        <div class="col-md-6 mb-3">
                            <label for="specialization" class="form-label">التخصص</label>
                            <input type="text" class="form-control @error('specialization') is-invalid @enderror" id="specialization" name="specialization" required>
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- سنوات الخبرة -->
                        <div class="col-md-6 mb-3">
                            <label for="experience_years" class="form-label">سنوات الخبرة</label>
                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror" id="experience_years" name="experience_years" required>
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status" required>
                                <option value="available">نشط</option>
                                <option value="unavailable">غير نشط</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> إضافة المدرب
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@endsection
