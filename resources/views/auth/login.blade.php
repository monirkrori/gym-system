@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">تسجيل دخول</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">الحساب الالكتروني</label>
            <input type="email" name="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-400" required>
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-600">كلمة المرور</label>
            <input type="password" name="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-400" required>
        </div>

        <div class="mb-6 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-orange-500 border-gray-300 rounded focus:ring-orange-400">
            <label for="remember" class="ml-2 text-sm text-gray-600">تذكرني</label>
        </div>

        <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">تسجيل دخول</button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">هل نسيت كلمة المرور؟</a>

    </div>
    <div class="mt-4 text-center">
         <p class="text-sm text-gray-600">ليس لديك حساب؟ <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500">إنشاء حساب جديد</a></p>
    </div>
@endsection
