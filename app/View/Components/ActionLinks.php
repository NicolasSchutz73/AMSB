<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActionLinks extends Component
{
    /**
     * Create a new component instance.
     */
    public $showRoute;
    public $editRoute;
    public $deleteRoute;
    public $id;
    public function __construct($showRoute, $editRoute, $deleteRoute, $id)
    {
        $this->showRoute = $showRoute;
        $this->editRoute = $editRoute;
        $this->deleteRoute = $deleteRoute;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.action-links');
    }
}
