@extends('layouts.dashboard')

@section('title', 'إضافة خطة اشتراك جديدة')

@section('content')
@can('manage-membership-plan')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800"><i class="bi bi-plus-circle me-2"></i> إضافة خطة اشتراك جديدة</h1>
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
                <form action="{{ route('admin.membership-plans.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">اسم الخطة</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">السعر</label>
                            <input type="number" id="price" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="duration_month" class="form-label">مدة الاشتراك (بالأشهر)</label>
                            <input type="number" id="duration_month" name="duration_month" class="form-control" required value="{{ old('duration_month', $membershipPlan->duration_month ?? 1) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">الحالة</label>
                            <select id="status" name="status" class="form-select">
                                <option value="active" selected>نشطة</option>
                                <option value="expired">غير نشطة</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">إضافة الخطة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@endsection
