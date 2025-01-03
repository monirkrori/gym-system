@extends('layouts.dashboard')
@section('content')
<div class="container mt-5">
    <h1 class="text-center">إضافة معدة جديدة</h1>
    <form action="{{ route('admin.equipments.store') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">اسم المعدة</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">الوصف</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">الحالة</label>
            <select name="status" id="status" class="form-control" required>
                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>جاهزة</option>
                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>معطلة</option>
            </select>
            @error('status')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-success">إضافة</button>
        <a href="{{ route('admin.equipments.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
