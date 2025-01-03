@extends('layouts.dashboard')

@section('title', 'إضافة مدرب')

@section('content')
    <div class="container">
        <h1 class="mb-4">إضافة مدرب جديد</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.trainers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">المستخدم</label>
                <select class="form-select" name="user_id" id="user_id" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="specialization" class="form-label">التخصص</label>
                <input type="text" class="form-control" id="specialization" name="specialization" required>
            </div>
            <div class="mb-3">
                <label for="experience_years" class="form-label">سنوات الخبرة</label>
                <input type="number" class="form-control" id="experience_years" name="experience_years" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">الحالة</label>
                <select class="form-select" name="status" id="status" required>
                    <option value="available">نشط</option>
                    <option value="unavailable">غير نشط</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">إضافة</button>
        </form>
    </div>
@endsection
