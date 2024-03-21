<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Request;

final class ComprobarToken
{
    public function validate(Request $request): bool
    {
        $authorizationHeader = $request->headers->get('Authorization');

        if ($authorizationHeader && strpos($authorizationHeader, 'Bearer ') === 0) {
            // Extraer y devolver el token de autenticación
            $string = substr($authorizationHeader, 7);

            if (preg_match('/^[{}\[\]\(\)]*$/', $string)) {
                $stack = [];
                $mapping = [
                    '{' => '}',
                    '[' => ']',
                    '(' => ')'
                ];
                foreach (str_split($string) as $character) {
                    if (array_key_exists($character, $mapping)) {
                        $stack[] = $character;
                    } elseif (in_array($character, $mapping)) {
                        if (empty($stack) || $mapping[array_pop($stack)] != $character) {
                            return false;
                        }
                    }
                }
                return empty($stack);
            } elseif (is_null($string)) {
                return true;
            }
        }



        return false;
    }
}