<?php

if (! function_exists('rp')) {
    function rp($amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}

if (! function_exists('terminal_short')) {
    function terminal_short(?string $name): string
    {
        return preg_replace('/^Terminal\s+/i', '', (string) $name);
    }
}

if (! function_exists('time_short')) {
    function time_short($time): string
    {
        return substr((string) $time, 0, 5);
    }
}
