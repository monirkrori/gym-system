@extends('layouts.dashboard')

@section('title', 'تفاصيل خطة الاشتراك')

@section('content')
<div class="container">
    <h1 class="mb-4">تفاصيل خطة الاشتراك</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h5><i class="bi bi-eye me-1"></i> تفاصيل خطة الاشتراك: {{ $membershipPlan->name }}</h5>
        </div>

        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>اسم الخطة:</strong> {{ $membershipPlan->name }}</li>
                <li class="list-group-item"><strong>السعر:</strong> ${{ $membershipPlan->price }}</li>
                <li class="list-group-item"><strong>المدة (بالأشهر):</strong> {{ $membershipPlan->duration_month }}</li>
                <li class="list-group-item"><strong>الوصف:</strong> {{ $membershipPlan->description ?? 'لا يوجد' }}</li>
                <li class="list-group-item"><strong>الحالة:</strong> {{ $membershipPlan->status === 'active' ? 'نشطة' : 'غير نشطة' }}</li>
            </ul>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.membership-plans.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> العودة إلى قائمة الخطط
            </a>

            <div>
                @can('manage-membership-plan')
                
                    <a href="{{ route('admin.membership-plans.edit', $membershipPlan->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> تعديل
                    </a>

                    <form action="{{ route('admin.membership-plans.destroy', $membershipPlan->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                            <i class="bi bi-trash me-1"></i> حذف
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
