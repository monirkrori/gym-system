@extends('layouts.dashboard')

@section('title', 'عرض المدرب')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <!-- Check if the trainer has a profile photo -->
            @if ($trainer->user->profile_photo)
                <!-- Display the profile photo if available -->
                <img src="{{ asset('storage/' . $trainer->user->profile_photo) }}"
                     class="rounded-circle me-2"
                     width="110" height="110"
                     alt="صورة المدرب">
            @else
                <!-- If no profile photo, show the "person-circle" icon -->
                <i class="bi bi-person-circle me-2"></i>
            @endif
            عرض المدرب: {{ $trainer->user->name }}


            <i class="bi bi-person-circle me-2"></i> عرض المدرب: {{ $trainer->user->name }}

        </h1>
        <a href="{{ route('admin.trainers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> العودة إلى القائمة
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- Trainer Details -->
                <div class="col-md-6 mb-3">
                    <strong>الاسم:</strong>
                    <p>{{ $trainer->user->name }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>البريد الإلكتروني:</strong>
                    <p>{{ $trainer->user->email }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>التخصص:</strong>
                    <p>{{ $trainer->specialization }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>سنوات الخبرة:</strong>
                    <p>{{ $trainer->experience_years }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>الحالة:</strong>
                    <p>{{ $trainer->status == 'available' ? 'متاح' : 'غير متاح' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>التقييم المتوسط:</strong>
                    <p>{{ $trainer->rating_avg ?? 'غير متوفر' }}</p>
                </div>

                <!-- You can add more trainer details here -->
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.trainers.edit', $trainer) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> تعديل المدرب
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
