@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="float-start">
                            <strong>User Information</strong>
                        </div>
                        <div class="float-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Name -->
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                                {{ $user->name }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-end text-start"><strong>Email Address:</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                                {{ $user->email }}
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="mb-3 row">
                            <label for="roles" class="col-md-4 col-form-label text-md-end text-start"><strong>Roles:</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                                @forelse ($user->getRoleNames() as $role)
                                    <span class="badge bg-primary">{{ $role }}</span>
                                @empty
                                    <span class="badge bg-secondary">No Roles Assigned</span>
                                @endforelse
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mb-3 text-center">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning w-100">Edit User</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
