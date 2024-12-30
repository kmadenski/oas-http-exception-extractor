<?php

namespace OasHttpExceptionExtractor\Tests\examples;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        //Commented code
        //throw new AccessDeniedHttpException();

        // Normal response (if no exception occurs)
        // return new Response('This is an example route.', Response::HTTP_OK);
    }
}
