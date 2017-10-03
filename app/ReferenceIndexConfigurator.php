<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class ReferenceIndexConfigurator extends IndexConfigurator
{
    use Migratable;
	
	protected $settings = [
        //
    ];

    protected $defaultMapping = [
        //
    ];
}