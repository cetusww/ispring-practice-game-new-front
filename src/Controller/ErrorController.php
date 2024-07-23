<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ErrorController extends AbstractController
{
    public function show(): Response
    {
        $exception = $this->get('request_stack')->getCurrentRequest()->attributes->get('exception');

        if ($exception instanceof NotFoundHttpException)
        {
            return $this->redirectToRoute('error_not_found');
        }

        return new Response('Something went wrong!', 500);
    }
}