@extends('layouts.dashboard')

@section('title', 'تعديل الملف الشخصي')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            {{-- Header Section --}}
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
                    تعديل الملف الشخصي: {{ $user->name }}
                </h1>
                <p class="text-gray-600 dark:text-gray-300">قم بتحديث معلوماتك الشخصية بسهولة</p>
            </div>

            {{-- Error Handling --}}
            @if ($errors->any())
                <div class="bg-red-50 border-r-4 border-red-500 p-4 mb-6 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h3 class="text-red-800 font-semibold mb-2">هناك بعض الأخطاء في النموذج</h3>
                            <ul class="list-disc list-inside text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Profile Information Form --}}
            <div class="grid md:grid-cols-2 gap-8">
                {{-- Personal Info Card --}}
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-r from-orange-400 to-pink-500 rounded-full flex items-center justify-center text-white text-4xl font-bold">
                            <img src="{{  asset('storage/' . auth()->user()->profile_photo) }}"
                                         class="rounded-circle me-5 shadow-sm"
                                         alt="صورة المستخدم"
                                         width="130"
                                         height="130"
                                         style="object-fit: cover; border: 2px solid var(--luxury-gold);">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Name Input --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">الاسم</label>
                            <div class="relative">
                                <input type="text" name="name" value="{{ auth()->user()->name }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-300">
                                <span class="absolute left-3 top-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Email Input --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">البريد الإلكتروني</label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ auth()->user()->email }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-300">
                                <span class="absolute left-3 top-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Gender Select --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">الجنس</label>
                            <div class="relative">
                                <select name="gender" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-300">
                                    <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>أنثى</option>

                                </select>
                                <span class="absolute left-3 top-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 100 8 4 4 0 000-8z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h3m-3 0H5m3 0a5 5 0 0110 0z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- Profile Photo Input --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">صورة الملف الشخصي</label>
                            <input type="file" name="profile_photo" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-300">
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-500 text-white py-3 rounded-lg hover:opacity-90 transition duration-300 shadow-md">
                            حفظ التغييرات
                        </button>
                    </form>
                </div>

                {{-- Security Section --}}
                <div class="space-y-8">
                    {{-- Change Password Card --}}
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 ml-2 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            تغيير كلمة المرور
                        </h3>
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Current Password --}}
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">كلمة المرور الحالية</label>
                                <input type="password" name="current_password"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-300">
                            </div>

                            {{-- New Password --}}
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">كلمة المرور الجديدة</label>
                                <input type="password" name="new_password"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-300">
                            </div>

                            {{-- Confirm New Password --}}
                            <div class="mb-4">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" name="new_password_confirmation"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-300">
                            </div>

                            {{-- Change Password Button --}}
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white py-3 rounded-lg hover:opacity-90 transition duration-300 shadow-md">
                                تغيير كلمة المرور
                            </button>
                        </form>
                    </div>

                    {{-- Delete Account Card --}}
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 border border-red-100 dark:border-red-900">
                        <h3 class="text-lg font-semibold text-red-700 dark:text-red-400 mb-4 flex items-center">
                            <svg class="w-6 h-6 ml-2 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            حذف الحساب
                        </h3>
                        <p class="text-sm text-red-600 dark:text-red-300 mb-4">
                            تحذير: سيؤدي حذف حسابك إلى إزالة جميع بياناتك بشكل دائم وغير قابل للاسترداد.
                        </p>
                        <form action="{{ route('admin.profile.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white py-3 rounded-lg hover:opacity-90 transition duration-300 shadow-md">
                                حذف الحساب نهائياً
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
