<?php

namespace App\Service\UrlApi;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class TinyApi implements UrlApiInterface
{
    public function getUrlData($url) : Response
    {
        try {
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
            $shortUrlTiny = curl_exec($ch);
            curl_close($ch);

            return new Response($shortUrlTiny);
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

