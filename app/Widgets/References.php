<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;

class References extends AbstractWidget
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
        $count = \App\Reference::count();
        $string = ($count > 1) ? 'References' : 'Reference';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-news',
            'title'  => "{$count} {$string}",
            'text'   => 'Click on button below to view all references.',
            'button' => [
                'text' => 'View all references',
                'link' => route('voyager.references.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/reference-bg.jpg'),
        ]));
    }
}
