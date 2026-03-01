<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;
use Illuminate\View\View;

class SalonCard extends Component
{
    public function __construct(
        public $salon
    ) {}

    public function render(): View
    {
        return view('components.ui.salonCard');
    }
}
