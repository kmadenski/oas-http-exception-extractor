<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExampleController
{
    /**
     * @Route("/example", name="example_route", methods={"GET"})
     */
    public function __invoke(): Response
    {
        // Simulate a scenario where an exception is thrown
        throw new NotFoundHttpException('The requested resource was not found.');

        // Normal response (if no exception occurs)
        // return new Response('This is an example route.', Response::HTTP_OK);
    }
}
