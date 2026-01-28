<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;

abstract class AuthLayoutComponent extends Component
{
    #[Layout('layouts.guest')]
    public function render()
    {
        return view($this->getView());
    }

    abstract protected function getView();
}
