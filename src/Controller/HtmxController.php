<?php

namespace App\Controller;

use App\Repository\PageRepository;
use App\Service\Paginator;
use App\Service\PaginatorLinks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HtmxController extends AbstractController
{
    #[Route('/htmx/modal', name: 'htmx_modal')]
    public function index(): Response
    {
        return $this->render('htmx/modal.html.twig', [
            'controller_name' => 'HtmxController',
        ]);
    }

    #[Route('/htmx/modal-content', name: 'htmx_modal_content')]
    public function modalContent(): Response
    {
        return $this->render('htmx/modal-content.html.twig', [
            'controller_name' => 'HtmxController',
        ]);
    }

    #[Route('/htmx/inline-edit', name: 'htmx_inline_edit')]
    public function inlineEdit(): Response
    {
        return $this->render('htmx/inline-edit.html.twig', [ ]);
    }

    #[Route('/htmx/inline-edit-content', name: 'htmx_inline_edit_content')]
    public function inlineEditContent(): Response
    {
        return $this->render('htmx/inline-edit-content.html.twig', [ ]);
    }

    #[Route('/htmx/infinite-pagination', name: 'htmx_infinite_pagination')]
    public function infinitePagination(Request $request, PageRepository $pageRepository, Paginator $paginator): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        $pages = $paginator->paginate($pageRepository->createQueryBuilder("pages"), $page, $limit);

        if ($page === 1){
            return $this->render('htmx/infinite-pagination.html.twig', [
                "pages" => $pages,
            ]);
        }

        return $this->render('htmx/infinite-pagination-more.html.twig', [
            "pages" => $pages,
            ]);
    }

    #[Route('/pages', name: 'pages_list')]
    public function pagesList(Request $request, PageRepository $pageRepository): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 5);
        $pages = Paginator::paginate($pageRepository->createQueryBuilder("pages"), $page, $limit);

        return $this->render('htmx/pages-list.html.twig', [
            "pages" => $pages,
            'limit' => $pages['limit'],
            "links" => PaginatorLinks::links(
                $pages['totalPages'],
                $pages['currentPage'],
                $this->generateUrl('htmx_pages_list'),
                [
                    'page' => $pages['currentPage'],
                    'limit' => $pages['limit'],
                ]
            ),
        ]);
    }

    #[Route('/htmx/pages', name: 'htmx_pages_list')]
    public function htmxPagesList(Request $request, PageRepository $pageRepository): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 5);
        $pages = Paginator::paginate($pageRepository->createQueryBuilder("pages"), $page, $limit);

        return $this->render('htmx/pages-list-content.html.twig', [
            "pages" => $pages,
            'limit' => $pages['limit'],
            "links" => PaginatorLinks::links(
                $pages['totalPages'],
                $pages['currentPage'],
                $this->generateUrl('htmx_pages_list'),
                [
                    'page' => $pages['currentPage'],
                    'limit' => $pages['limit'],
                ]
            ),
        ]);
    }


}
