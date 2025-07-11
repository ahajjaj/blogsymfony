<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, ArticleRepository $repository): Response
    {
        $query = $request->query->get('q', '');
        $articles = [];

        if ($query) {
            $articles = $repository->search($query);
        }

        return $this->render('search/results.html.twig', [
            'articles' => $articles,
            'query' => $query,
        ]);
    }
}
