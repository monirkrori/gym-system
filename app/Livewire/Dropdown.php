<?php

namespace App\Livewire;


use Livewire\Component;

class Dropdown extends Component
{
    public $open = false;

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function render()
    {
        return view('livewire.dropdown');
    }
}
