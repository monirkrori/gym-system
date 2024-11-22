<?php
namespace App\Livewire;

use Livewire\Component;

class ActivitiesTable extends Component
{
    public $activities = [
        ['title' => 'تسجيل مستخدم جديد', 'date' => '2024-11-20'],
        ['title' => 'تحديث النظام', 'date' => '2024-11-19'],
    ];

    public function render()
    {
        return view('livewire.activities-table');
    }
}
