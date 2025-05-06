<?php

if (! function_exists('default_paginate')) {
    function default_paginate($default = 20, $max = 100, $query = 'per_page')
    {
        $pagination = request()->get($query, $default);

        return $pagination <= $max ? $pagination : $default;
    }
}
