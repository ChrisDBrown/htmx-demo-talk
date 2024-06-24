<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/fragment', name: 'fragment_')]
class FragmentController extends AbstractController
{
    #[Route('/switch', name: 'switch', methods: [Request::METHOD_GET])]
    public function switch(): Response
    {
        return $this->render('fragment/switch.html.twig');
    }
}
