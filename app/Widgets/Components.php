<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;

class Components extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = \App\Component::count();
        $string = ($count > 1) ? 'Components' : 'Component';
        
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-news',
            'title'  => "{$count} {$string}",
            'text'   => 'Click on button below to view all components.',
            'button' => [
                'text' => 'View all components',
                'link' => route('voyager.components.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/chip.jpg'),
        ]));
    }
}
