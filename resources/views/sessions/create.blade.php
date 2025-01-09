@extends('layouts.dashboard')

@section('title', 'إضافة جلسة جديدة')

@section('content')
@can('create-sessions')
<div class="container">
    <h1 class="mb-4">إضافة جلسة جديدة</h1>

    <form action="{{ route('admin.sessions.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="package_id" class="form-label">الحزمة التدريبية</label>
            <select class="form-select" id="package_id" name="package_id" required>
                @foreach ($packages as $package)
                    <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }}</option>
                @endforeach
            </select>
            @error('package_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="trainer_id" class="form-label">المدرب</label>
            <select class="form-select" id="trainer_id" name="trainer_id" required>
                @foreach ($trainers as $trainer)
                    <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>{{ $trainer->user->name }}</option>
                @endforeach
            </select>
            @error('trainer_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">اسم الجلسة</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">الوصف</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">نوع الجلسة</label>
            <select class="form-select" id="type" name="type" required>
                <option value="group" {{ old('type') == 'group' ? 'selected' : '' }}>جماعية</option>
                <option value="personal" {{ old('type') == 'personal' ? 'selected' : '' }}>شخصية</option>
            </select>
            @error('type')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="difficulty_level" class="form-label">مستوى الصعوبة</label>
            <select class="form-select" id="difficulty_level" name="difficulty_level" required>
                <option value="beginner" {{ old('difficulty_level') == 'beginner' ? 'selected' : '' }}>مبتدئ</option>
                <option value="intermediate" {{ old('difficulty_level') == 'intermediate' ? 'selected' : '' }}>متوسط</option>
                <option value="advanced" {{ old('difficulty_level') == 'advanced' ? 'selected' : '' }}>متقدم</option>
            </select>
            @error('difficulty_level')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="start_time" class="form-label">وقت البدء</label>
            <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
            @error('start_time')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="end_time" class="form-label">وقت الانتهاء</label>
            <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
            @error('end_time')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="max_capacity" class="form-label">الحد الأقصى للمشتركين</label>
            <input type="number" class="form-control" id="max_capacity" name="max_capacity" value="{{ old('max_capacity') }}" min="1" required>
            @error('max_capacity')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">الحالة</label>
            <select class="form-select" id="status" name="status" required>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>منتهٍ</option>
                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
            </select>
            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary">إضافة الجلسة</button>
    </form>
</div>
@endcan
@endsection
