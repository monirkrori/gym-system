@extends('layouts.dashboard')

@section('title', 'تعديل المدرب')

@section('content')
    <div class="container">
        <h1 class="mb-4">تعديل المدرب: {{ $trainer->user->name }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.trainers.update', $trainer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="user_id" class="form-label">المستخدم</label>
                <select class="form-select" name="user_id" id="user_id" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ $trainer->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="specialization" class="form-label">التخصص</label>
                <input type="text" class="form-control" id="specialization" name="specialization"
                    value="{{ old('specialization', $trainer->specialization) }}" required>
            </div>

            <div class="mb-3">
                <label for="experience_years" class="form-label">سنوات الخبرة</label>
                <input type="number" class="form-control" id="experience_years" name="experience_years"
                    value="{{ old('experience_years', $trainer->experience_years) }}" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">الحالة</label>
                <select class="form-select" name="status" id="status" required>
                    <option value="available" {{ $trainer->status == 'available' ? 'selected' : '' }}>نشط</option>
                    <option value="unavailable" {{ $trainer->status == 'unavailable' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">تحديث</button>
        </form>
    </div>
@endsection
