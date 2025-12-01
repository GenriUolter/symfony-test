<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SwaggerController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/api/doc', name: 'api_doc')]
    public function doc(): Response
    {
        return $this->render('swagger/index.html.twig');
    }
}
