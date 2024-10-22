<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardView extends Component
{
    public $title;

    public $data;

    public $relatedData;

    public $editRoute;

    public $relatedLabel;

    public function __construct($title, $data, $relatedData = null, $editRoute = null, $relatedLabel = null)
    {
        $this->title = $title;
        $this->data = $data;
        $this->relatedData = $relatedData;
        $this->editRoute = $editRoute;
        $this->relatedLabel = $relatedLabel;
    }

    public function render()
    {
        return view('components.card-view');
    }
}
