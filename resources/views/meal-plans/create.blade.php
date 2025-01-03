@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">إضافة خطة وجبات جديدة</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.meal-plans.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">اسم الخطة</label>
            <input t ype="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="calories_per_day" class="form-label">السعرات الحرارية اليومية</label>
            <input type="number" name="calories_per_day" id="calories_per_day" class="form-control" value="{{ old('calories_per_day') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">الوصف</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">حفظ</button>
    </form>
</div>
@endsection
