@extends('layouts.dashboard')

@section('title', 'تعديل الجلسة')

@section('content')
<div class="container">
    <h1 class="mb-4">تعديل الجلسة: {{ $session->name }}</h1>

  <form action="{{ route('admin.sessions.update', $session->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="package_id" class="form-label">الحزمة التدريبية</label>
            <select class="form-select" id="package_id" name="package_id" required>
                @foreach ($packages as $package)
                    <option value="{{ $package->id }}" {{ $session->package_id == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="trainer_id" class="form-label">المدرب</label>
            <select class="form-select" id="trainer_id" name="trainer_id" required>
                @foreach ($trainers as $trainer)
                    <option value="{{ $trainer->id }}" {{ $session->trainer_id == $trainer->id ? 'selected' : '' }}>{{ $trainer->user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">اسم الجلسة</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $session->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">الوصف</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $session->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">نوع الجلسة</label>
            <select class="form-select" id="type" name="type" required>
                <option value="group" {{ $session->type == 'group' ? 'selected' : '' }}>جماعية</option>
                <option value="personal" {{ $session->type == 'personal' ? 'selected' : '' }}>شخصية</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="difficulty_level" class="form-label">مستوى الصعوبة</label>
            <select class="form-select" id="difficulty_level" name="difficulty_level" required>
                <option value="beginner" {{ $session->difficulty_level == 'beginner' ? 'selected' : '' }}>مبتدئ</option>
                <option value="intermediate" {{ $session->difficulty_level == 'intermediate' ? 'selected' : '' }}>متوسط</option>
                <option value="advanced" {{ $session->difficulty_level == 'advanced' ? 'selected' : '' }}>متقدم</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_time" class="form-label">وقت البدء</label>
            <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($session->start_time)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">وقت الانتهاء</label>
            <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($session->end_time)->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="mb-3">
            <label for="max_capacity" class="form-label">الحد الأقصى للمشتركين</label>
            <input type="number" class="form-control" id="max_capacity" name="max_capacity" value="{{ old('max_capacity', $session->max_capacity) }}" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">تعديل الجلسة</button>
    </form>
</div>
@endsection
