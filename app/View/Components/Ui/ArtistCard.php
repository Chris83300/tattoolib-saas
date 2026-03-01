<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;
use Illuminate\View\View;

class ArtistCard extends Component
{
    public function __construct(
        public $artist = null,
        public $studioArtist = null
    ) {}

    public function render(): View
    {
        return view('components.ui.artistCard');
    }
}
