@extends('layouts.auth')

@section('title', 'نسيت كلمة المرور')

@section('content')
    <div class="mb-6 text-sm text-gray-600">
        {{ __('هل نسيت كلمة المرور؟ لا مشكلة. فقط أدخل بريدك الإلكتروني أدناه وسنرسل لك رابطًا لإعادة تعيين كلمة المرور.') }}
    </div>

    <!-- حالة الجلسة -->
    @if (session('status'))
        <div class="mb-4 text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- البريد الإلكتروني -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400">
            @error('email')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">
            إرسال رابط إعادة تعيين كلمة المرور
        </button>
    </form>
@endsection
