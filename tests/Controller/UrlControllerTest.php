<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UrlsControllerTest extends WebTestCase
{
    public function testSendShortUrl()
    {
        // Crear un cliente de prueba
        $client = static::createClient();

        // Datos de ejemplo para enviar en la solicitud
        $requestData = [
            'url' => 'https://example.com'
        ];

        // Convertir datos a formato JSON
        $content = json_encode($requestData);

        // Enviar una solicitud POST al endpoint de la API
        $client->request(
            'POST',
            '/api/v1/short-url',
            [],
            [],
            [],
            $content
        );

        // Verificar que la solicitud haya sido exitosa (código de estado 200)
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        // Verificar que la respuesta sea JSON
        $this->assertJson($client->getResponse()->getContent());

        // Verificar que la respuesta contenga la clave 'url'
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('url', $responseData);
    }
}
