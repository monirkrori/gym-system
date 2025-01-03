@extends('layouts.auth')

@section('title', 'تأكيد البريد الإلكتروني')

@section('content')
    <div class="mb-6 text-sm text-gray-600">
        {{ __('قبل المتابعة، يُرجى التحقق من بريدك الإلكتروني للحصول على رابط التحقق.') }}
        {{ __('إذا لم تتلقَ البريد الإلكتروني، يمكنك طلب رابط جديد أدناه.') }}
    </div>

    <!-- حالة الجلسة -->
    @if (session('resent'))
        <div class="mb-4 text-sm text-green-600">
            {{ __('تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">
            {{ __('إعادة إرسال رابط التحقق') }}
        </button>
    </form>
@endsection
