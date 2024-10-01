<?php

if (!function_exists('dd')) {
    function dd(...$args): void
    {
        die(var_dump(...$args));
    }
}
