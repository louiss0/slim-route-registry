<?php


/* 

TODO In php 8.1 change this thing into an enum


*/

namespace Louiss0\SlimRouteRegistry\Enums;

final class AutomaticRegistrationMethodNames
{


    const GET_ANY = "index";
    const GET_ONE = "show";
    const CREATE_ONE = "store";
    const UPDATE_ONE = "update";
    const DELETE_ONE = "destroy";
    const UPDATE_OR_CREATE_ONE = "upsert";



    static public function getAutomaticRegistrationMethodNames(): array
    {
        # code...

        return [
            self::GET_ANY,
            self::GET_ONE,
            self::CREATE_ONE,
            self::UPDATE_ONE,
            self::UPDATE_OR_CREATE_ONE,
            self::DELETE_ONE
        ];
    }

    static function checkIfMethodNameExistsInAutomaticRegistrationMethodNames(
        string $method_name
    ): bool {



        return in_array($method_name, self::getAutomaticRegistrationMethodNames(), true);
    }
}
