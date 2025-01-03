@extends('layouts.auth')

@section('title', 'التسجيل')

@section('content')
    <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">التسجيل</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-600">الاسم</label>
            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600">كلمة المرور</label>
            <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400" required>
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-600">تأكيد كلمة المرور</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400" required>
        </div>

        <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">تسجيل</button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline">هل لديك حساب؟ تسجيل الدخول</a>
    </div>
@endsection
