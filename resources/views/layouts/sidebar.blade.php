<div class="bg-gray-800 text-white w-64 h-screen flex flex-col">
    <div class="p-5 border-b border-gray-700">
        <h2 class="text-2xl font-semibold">لوحة التحكم</h2>
    </div>

    <div class="flex-grow">
        <ul class="space-y-4 p-4">
            <li>
                <a href="{{ route('dashboard') }}" class="text-lg hover:bg-gray-700 p-2 rounded transition-colors">الصفحة الرئيسية</a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="text-lg hover:bg-gray-700 p-2 rounded transition-colors">إدارة المستخدمين</a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="text-lg hover:bg-gray-700 p-2 rounded transition-colors">تعديل الملف الشخصي</a>
            </li>
            <li>
                <a href="{{ route('settings.index') }}" class="text-lg hover:bg-gray-700 p-2 rounded transition-colors">الإعدادات</a>
            </li>

            <!-- Livewire مكون القائمة المنسدلة -->
            <livewire:dropdown />
        </ul>
    </div>

    <div class="bg-gray-800 p-5 text-center text-xs">
        <p>حقوق الطبع والنشر © 2024 جميع الحقوق محفوظة</p>
    </div>
</div>
