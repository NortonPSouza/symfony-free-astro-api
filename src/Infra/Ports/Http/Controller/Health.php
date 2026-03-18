<?php

namespace App\Infra\Ports\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/health')]
final class Health extends AbstractController
{

    #[Route('', methods: [ 'GET' ])]
    public function handle(): Response
    {
        return new Response('Free Astro for read your future', Response::HTTP_OK);
    }
}