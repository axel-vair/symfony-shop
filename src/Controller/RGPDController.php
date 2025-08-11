<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RGPDController extends AbstractController
{
    #[Route('mention-legales', name: 'app_mention_index')]
    public function index(): Response
    {
        return $this->render('pages/rgpd/index.html.twig');
    }
}
