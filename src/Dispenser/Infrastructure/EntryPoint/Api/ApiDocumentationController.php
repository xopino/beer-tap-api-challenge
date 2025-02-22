<?php

namespace App\Dispenser\Infrastructure\EntryPoint\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiDocumentationController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('swagger.html.twig');
    }
}
