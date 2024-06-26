<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/progress', name: 'progress_')]
class ProgressController extends AbstractController
{
    #[Route('/update', name: 'update', methods: [Request::METHOD_GET])]
    public function update(Request $request): Response
    {
        $progress = null !== $request->query->get('p') ? (int) ($request->query->get('p')) : 0;

        $progress += random_int(10, 40);

        if ($progress >= 100) {
            $response = $this->render('progress/progress.html.twig', ['progress' => $progress]);

            $response->headers->add(['HX-Trigger' => 'done', 'HX-Push-Url' => $this->generateUrl('page_feed')]);

            return $response;
        }

        return $this->render('progress/progress.html.twig', ['progress' => $progress]);
    }
}
