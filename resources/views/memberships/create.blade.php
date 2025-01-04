@extends('layouts.dashboard')

@section('title', 'إضافة عضوية جديدة')

@section('content')
@can('create-membership')
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
                <i class="bi bi-person-plus-fill me-2"></i> إضافة عضوية جديدة
            </h1>
            <a href="{{ route('admin.memberships.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> العودة إلى القائمة
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.memberships.store') }}">
                    @csrf
                    <div class="row">
                        <!-- اسم العضو -->
                        <div class="col-md-6 mb-3">
                            <label for="user_id" class="form-label">اسم العضو</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                <option value="">اختر العضو</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الخطة -->
                        <div class="col-md-6 mb-3">
                            <label for="plan_id" class="form-label">الخطة</label>
                            <select name="plan_id" id="plan_id" class="form-control @error('plan_id') is-invalid @enderror">
                                <option value="">اختر الخطة</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الباقة -->
                        <div class="col-md-6 mb-3">
                            <label for="package_id" class="form-label">الباقة</label>
                            <select name="package_id" id="package_id" class="form-control @error('package_id') is-invalid @enderror">
                                <option value="">اختر الباقة</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="active">نشط</option>
                                <option value="expired">غير نشط</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- تاريخ الإنشاء (تلقائي) -->
                        <div class="col-md-6 mb-3">
                            <label for="created_at" class="form-label">تاريخ الإنشاء</label>
                            <input type="text" class="form-control" name="start_date" id="created_at" value="{{ now()->format('Y-m-d') }}" readonly>
                        </div>

                        <!-- Buttons -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> إضافة العضوية
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@endsection
