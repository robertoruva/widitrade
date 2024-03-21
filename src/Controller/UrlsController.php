<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\UrlApi\UrlApiInterface;
use App\Utils\ComprobarToken;

class UrlsController extends AbstractController {
    private $urlApi;
    private $comprobarToken;

    public function __construct(UrlApiInterface $urlApi, ComprobarToken $comprobarToken)
    {
        $this->urlApi = $urlApi;
        $this->comprobarToken = $comprobarToken;
    }

    #[Route('/api/v1/short-url', name: 'post_url', methods: 'POST')]
    public function sendShortUrl(Request $request) 
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            'url' => new Assert\NotBlank(),
        ]);

        $errors = $validator->validate($data, $constraint);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['error' => 'url: string, required' ], 400);
        }

        $validateToken = $this->comprobarToken->validate($request);
        if (!$validateToken) {
            return new JsonResponse(['error' => 'No Autorizado']);
        }

        $urlTiny = $this->urlApi->getUrlData($data['url']);

        return new JsonResponse(['url' => $urlTiny->getContent()]);
    }


}