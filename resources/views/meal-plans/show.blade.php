@extends('layouts.dashboard')

@section('title', 'تفاصيل خطة الوجبات')

@section('content')
<div class="container">
    <h1 class="mb-4">تفاصيل خطة الوجبات</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h5><i class="bi bi-eye me-1"></i> تفاصيل خطة الوجبات: {{ $mealPlan->name }}</h5>
        </div>

        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>اسم الخطة:</strong> {{ $mealPlan->name }}</li>
                <li class="list-group-item"><strong>السعرات الحرارية اليومية:</strong> {{ $mealPlan->calories_per_day }} كيلو كالوري</li>
                <li class="list-group-item"><strong>الوصف:</strong> {{ $mealPlan->description ?? 'لا يوجد' }}</li>
            </ul>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.meal-plans.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> العودة إلى قائمة الخطط
            </a>

            <div>
                @can('manage-meal-plans')
                    <a href="{{ route('admin.meal-plans.edit', $mealPlan->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> تعديل
                    </a>

                    <form action="{{ route('admin.meal-plans.destroy', $mealPlan->id) }}" method="POST" class="d-inline">
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
