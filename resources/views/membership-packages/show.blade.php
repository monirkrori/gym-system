@extends('layouts.dashboard')

@section('title', 'تفاصيل الباقة')

@section('content')
<div class="container">
    <h1 class="mb-4">تفاصيل الباقة</h1>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h5><i class="bi bi-eye me-1"></i> تفاصيل الباقة: {{ $membershipPackage->name }}</h5>
        </div>

        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>اسم الباقة:</strong> {{ $membershipPackage->name }}</li>
                <li class="list-group-item"><strong>السعر:</strong> ${{ $membershipPackage->price }}</li>
                <li class="list-group-item"><strong>عدد الجلسات التدريبية القصوى:</strong> {{ $membershipPackage->max_training_sessions }}</li>
                <li class="list-group-item"><strong>الوصف:</strong> {{ $membershipPackage->description ?? 'لا يوجد' }}</li>
                <li class="list-group-item"><strong>الحالة:</strong> {{ $membershipPackage->status === 'active' ? 'نشطة' : 'غير نشطة' }}</li>
            </ul>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.membership-packages.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> العودة إلى قائمة الباقات
            </a>

            <div>
                @can('manage-membership-package')
                    <a href="{{ route('admin.membership-packages.edit', $membershipPackage->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> تعديل
                    </a>

                    <form action="{{ route('admin.membership-packages.destroy', $membershipPackage->id) }}" method="POST" class="d-inline">
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
