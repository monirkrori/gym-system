<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'صفحة تسجيل الدخول')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* الخط الأساسي */
        body {
            font-family: 'Cairo', 'Arial', sans-serif;
            background-color: #f9f9f9; /* خلفية عامة خفيفة */
            color: #333; /* لون النص الافتراضي */
        }

        /* ترويسة الصفحة */
        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #092161; /* الأزرق الداكن */
        }

        /* النصوص الفرعية */
        p {
            font-size: 1rem;
            color: #555; /* رمادي متوسط */
        }

        /* الروابط */
        a {
            color: #26C80E; /* الأخضر */
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #092161; /* الأزرق الداكن عند التمرير */
        }

        /* الحاويات الأساسية */
        .container {
            background: #fff; /* خلفية بيضاء */
            border: 1px solid #eee; /* إطار خفيف */
            border-radius: 15px; /* زوايا دائرية */
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* ظل خفيف */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: scale(1.03); /* تكبير طفيف عند التمرير */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* زيادة الظل */
        }

        /* الأزرار */
        button {
            background-color: #26C80E; /* الأخضر */
            color: #fff; /* نص أبيض */
            font-size: 1rem;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 10px; /* زوايا دائرية */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #092161; /* أزرق داكن عند التمرير */
            transform: translateY(-2px); /* حركة بسيطة للأعلى */
        }

        /* الحقول النصية */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd; /* إطار خفيف */
            border-radius: 10px;
            font-size: 1rem;
            color: #333;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            border-color: #26C80E; /* أخضر عند التركيز */
            box-shadow: 0 0 5px rgba(38, 200, 14, 0.5); /* ظل خفيف */
            outline: none;
        }

        /* الحواشي */
        footer {
            font-size: 0.9rem;
            color: #666; /* رمادي غامق */
            text-align: center;
            margin-top: 20px;
        }
    </style>

</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:scale-105">
        <div class="p-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-blue-600 mb-2">@yield('page-title', 'مرحباً')</h1>
                <p class="text-gray-500">@yield('page-subtitle', 'يرجى إدخال بياناتك')</p>
            </div>

            @yield('content')

            <div class="mt-6 text-center">
                @yield('additional-links')
            </div>
        </div>

        <div class="bg-blue-50 p-4 text-center text-sm text-gray-600">
            @yield('footer', '© ' . date('Y') . ' جميع الحقوق محفوظة')
        </div>
    </div>
</body>
</html>
