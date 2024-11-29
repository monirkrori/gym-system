@extends('layouts.dashboard')

@section('title', 'إضافة عضوية')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4">إضافة عضوية جديدة</h1>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.memberships.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="user_id" class="form-label">المستخدم</label>
                        <select name="user_id" id="user_id" class="form-control">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="package_id" class="form-label">الباقة</label>
                        <select name="package_id" id="package_id" class="form-control">
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active">نشط</option>
                            <option value="expired">منتهي</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">حفظ</button>
                </form>
            </div>
        </div>
    </div>
@endsection
