<div class="relative">
    <button class="w-full bg-gray-800 text-white text-lg py-2 px-4 rounded-md focus:outline-none hover:bg-gray-700" wire:click="toggleDropdown">
        إعدادات الحساب
    </button>

    @if($open)
        <ul class="absolute right-0 mt-2 bg-white text-black w-48 rounded-md shadow-lg z-50">
            <li><a href="{{ route('profile.edit') }}" class="block px-4 py-2">تعديل الملف الشخصي</a></li>
            <li><a href="{{ route('settings.index') }}" class="block px-4 py-2">إعدادات النظام</a></li>
        </ul>
    @endif
</div>
