<?php

namespace App\Livewire;

use Livewire\Component;

class StatCard extends Component
{
    public $title;
    public $count;

    public function render()
    {
        return view('livewire.stat-card');
    }
}

