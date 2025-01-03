@extends('layouts.auth')

@section('title', 'التحقق من البريد الإلكتروني')

@section('content')

        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden p-8">

            <div class="text-center mb-6">
                <h2 class="text-3xl font-semibold text-blue-600 mb-4">{{ __('التحقق من البريد الإلكتروني') }}</h2>
                <p class="text-sm text-gray-600">
                    {{ __('شكرًا للتسجيل! قبل البدء، يرجى التحقق من عنوان بريدك الإلكتروني من خلال النقر على الرابط الذي أرسلناه إليك. إذا لم تتلقَّ البريد الإلكتروني، سنرسل لك رابطًا آخر.') }}
                </p>
            </div>

            <!-- حالة الجلسة -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-sm text-green-600">
                    {{ __('تم إرسال رابط تحقق جديد إلى عنوان البريد الإلكتروني الذي قمت بتقديمه أثناء التسجيل.') }}
                </div>
            @endif

            <div class="space-y-4">

                <!-- زر إرسال رابط التحقق مرة أخرى -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 ease-in-out transform hover:-translate-y-1 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <span class="flex items-center justify-center">
                            <svg class="w-6 h-6 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                            </svg>
                            {{ __('إرسال رابط التحقق مرة أخرى') }}
                        </span>
                    </button>
                </form>

                <!-- زر تسجيل الخروج -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-white text-blue-600 border border-blue-500 rounded-lg hover:bg-blue-50 transition-all duration-300 ease-in-out transform hover:-translate-y-1 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <span class="flex items-center justify-center">
                            <svg class="w-6 h-6 ml-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
                            </svg>
                            {{ __('تسجيل الخروج') }}
                        </span>
                    </button>
                </form>

            </div>
        </div>

@endsection
