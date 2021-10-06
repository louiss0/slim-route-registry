<?php


namespace Louiss0\SlimRouteRegistry\Utils\Helpers;

function array_first(callable $callback, array $array): mixed
{
    # code...


    foreach ($array as $key => $value) {
        # code...

        if ($callback($value, $key)) {
            # code...

            return $value;
        }
    }

    return null;
}


function array_every(callable $callback, array $array): bool
{


    foreach ($array as $key => $value) {
        # code...

        if (!$callback($value, $key)) {
            # code...

            return false;
        }
    }

    return true;
}
