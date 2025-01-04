@extends('layouts.dashboard')

@section('title', 'تفاصيل المعدة')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h5><i class="bi bi-eye me-1"></i> تفاصيل المعدة: {{ $equipment->name }}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>اسم المعدة:</strong> {{ $equipment->name }}</li>
                <li class="list-group-item"><strong>الوصف:</strong> {{ $equipment->description ?? 'لا يوجد' }}</li>
                <li class="list-group-item"><strong>الحالة:</strong>
                    <span class="badge badge-status {{ $equipment->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                        {{ $equipment->status == 'available' ? 'جاهزة' : 'معطلة' }}
                    </span>
                </li>
            </ul>
        </div>

        <div class="card-footer d-flex justify-content-between">

            <a href="{{ route('admin.equipments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> العودة
            </a>

            <div>
                @can('manage-equipment')
                    <a href="{{ route('admin.equipments.edit', $equipment->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> تعديل
                    </a>

                    <form action="{{ route('admin.equipments.destroy', $equipment->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه المعدة؟')">
                            <i class="bi bi-trash me-1"></i> حذف
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
