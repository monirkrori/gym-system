@extends('layouts.dashboard')

@section('title', 'تفاصيل الجلسة التدريبية')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h5><i class="bi bi-eye me-1"></i> تفاصيل الجلسة التدريبية</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>اسم الجلسة:</strong> {{ $session->name }}</li>
                <li class="list-group-item"><strong>التاريخ:</strong> {{ $session->date->format('Y-m-d') }}</li>
                <li class="list-group-item"><strong>المدة:</strong> {{ $session->duration }} دقيقة</li>
                <li class="list-group-item"><strong>الحالة:</strong>
                    <span class="badge badge-status {{ $session->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $session->status == 'active' ? 'نشط' : 'ملغي' }}
                    </span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <a href="{{ route('sessions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> العودة
            </a>
        </div>
    </div>
</div>
@endsection
