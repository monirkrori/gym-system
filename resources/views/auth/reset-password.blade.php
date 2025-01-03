
@extends('layouts.auth')

@section('title', 'إعادة تعيين كلمة المرور')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 p-6">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <svg class="mx-auto mb-6 w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                    </svg>

                    <h2 class="text-3xl font-bold text-blue-600 mb-4">إعادة تعيين كلمة المرور</h2>
                </div>

                <div class="bg-blue-50 border-r-4 border-blue-500 p-4 mb-6 rounded-lg">
                    <p class="text-sm text-blue-800">
                        {{ __('أدخل بريدك الإلكتروني وكلمة المرور الجديدة لإعادة تعيين كلمة المرور الخاصة بك.') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-600">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400">
                        @error('email')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-600">كلمة المرور الجديدة</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400">
                        @error('password')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-600">تأكيد كلمة المرور</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400">
                        @error('password_confirmation')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">
                        إعادة تعيين كلمة المرور
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Cairo', 'Arial', sans-serif;
        }
    </style>

    <script>
        // إضافة تأثيرات تفاعلية خفيفة
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', (e) => {
                    button.style.transform = 'scale(1.02)';
                });
                button.addEventListener('mouseleave', (e) => {
                    button.style.transform = 'scale(1)';
                });
            });
        });
    </script>
@endsection
