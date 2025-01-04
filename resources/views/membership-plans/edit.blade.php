@extends('layouts.dashboard')

@section('title', 'تعديل خطة الاشتراك')

@section('content')
@can('manage-membership-plan')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800"><i class="bi bi-pencil-square me-2"></i> تعديل خطة الاشتراك</h1>

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
                <form action="{{ route('admin.membership-plans.update', $membershipPlan->id) }}" method="POST">
                    @csrf
                    @method('PUT') 

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">اسم الخطة</label>
                            <input type="text" id="name" name="name" class="form-control" required value="{{ old('name', $membershipPlan->name) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">السعر</label>
                            <input type="number" id="price" name="price" class="form-control" required value="{{ old('price', $membershipPlan->price) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="duration_month" class="form-label">مدة الاشتراك (بالأشهر)</label>
                            <input type="number" id="duration_month" name="duration_month" class="form-control" required value="{{ old('duration_month', $membershipPlan->duration_month) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">الحالة</label>
                            <select id="status" name="status" class="form-select">
                                <option value="active" {{ old('status', $membershipPlan->status) == 'active' ? 'selected' : '' }}>نشطة</option>
                                <option value="expired" {{ old('status', $membershipPlan->status) == 'expired' ? 'selected' : '' }}>غير نشطة</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea id="description" name="description" class="form-control">{{ old('description', $membershipPlan->description) }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">تحديث الخطة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
@endsection
