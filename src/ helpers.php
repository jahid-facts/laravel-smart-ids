<?php

use IdGenerator\IdGenerator;

if (!function_exists('idgen')) {
    function idgen(): IdGenerator
    {
        return app(IdGenerator::class);
    }
}
