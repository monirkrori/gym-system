<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepository extends Command
{
    // تعريف اسم الأمر الذي سيتم استخدامه في Artisan
    protected $signature = 'make:repository {name}';

    // وصف الأمر
    protected $description = 'Create a new repository class';

    // دالة التنفيذ الرئيسية
    public function handle()
    {
        $name = $this->argument('name');

        // تحديد مسار المجلد
        $directory = app_path('Repositories');

        // التحقق من وجود المجلد، وإذا لم يكن موجودًا يتم إنشاؤه
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = "{$directory}/{$name}.php";

        if (file_exists($path)) {
            $this->error("Repository {$name} already exists!");
            return;
        }

        $template = "<?php

namespace App\Repositories;

class {$name}
{
}";

        file_put_contents($path, $template);

        $this->info("Repository {$name} created successfully!");
    }
}

