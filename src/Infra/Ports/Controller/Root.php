<?php

namespace App\Infra\Ports\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Root extends AbstractController
{
    #[Route('/', methods: [ 'GET' ])]
    public function handle(): Response
    {
        return new Response('Free Astro for read your future', Response::HTTP_OK);
    }
}