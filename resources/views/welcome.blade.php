<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', 'Arial', sans-serif;
            background: linear-gradient(135deg, #f6f8f9 0%, #e5ebee 100%);
        }
        .hero-container {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .hero-container:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="hero-container w-full max-w-md bg-white rounded-2xl p-8 text-center shadow-lg">
        <div class="mb-6">
            <svg class="mx-auto mb-4 w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>

            <h1 class="text-4xl font-bold text-emerald-600 mb-4 animate-pulse">مرحباً بك!</h1>
            <p class="text-lg text-blue-800 mb-8 leading-relaxed">
                مرحبًا في منصتنا. نحن سعداء برؤيتك اليوم.
                <br>
                هل أنت مستعد للبدء؟
            </p>
        </div>

        <div class="space-y-4">
            @if (Auth::check())
                <!-- في حالة تسجيل الدخول -->
                <a href="{{ route('admin.dashboard') }}" class="block w-full bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-emerald-700 transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                    الذهاب إلى لوحة التحكم
                </a>
            @else
                <!-- في حالة عدم تسجيل الدخول -->
                <a href="{{ route('login') }}" class="block w-full bg-blue-800 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-900 transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                    تسجيل الدخول
                </a>

                <a href="{{ route('register') }}" class="block w-full border-2 border-emerald-600 text-emerald-600 px-6 py-3 rounded-lg hover:bg-emerald-50 transition-all duration-300 ease-in-out">
                    إنشاء حساب جديد
                </a>
            @endif
        </div>
    </div>

    <script>
        // إضافة بعض التأثيرات التفاعلية الخفيفة
        document.addEventListener('DOMContentLoaded', () => {
            const heroContainer = document.querySelector('.hero-container');
            heroContainer.addEventListener('mousemove', (e) => {
                const rect = heroContainer.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                heroContainer.style.setProperty('--mouse-x', `${x}px`);
                heroContainer.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    </script>
</body>
</html>
