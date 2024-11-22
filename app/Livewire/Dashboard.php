<?php
namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $data = [100, 200, 300, 400, 500]; // بيانات افتراضية للمخطط

    public function render()
    {
        return view('livewire.dashboard');
    }
}

