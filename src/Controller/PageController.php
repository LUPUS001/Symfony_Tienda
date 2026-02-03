<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Team;
use App\Service\ProductsService;

class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ManagerRegistry $doctrine, ProductsService $productsService): Response
    {
        $repository = $doctrine->getRepository(Team::class);
        $team = $repository->findAll();

        $products = $productsService->getProducts();

        // Pasar la variable 'team' a la vista
        return $this->render('page/index.html.twig', compact('team', 'products'));
    }


    #[Route('/about', name: 'about')]
    public function about(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Team::class);
        $team = $repository->findAll();
    
        return $this->render('page/about.html.twig', compact('team'));
    }

    #[Route('/service', name: 'service')]
    public function service(): Response
    {
        return $this->render('page/service.html.twig', []);
    }

    #[Route('/price', name: 'price')]
    public function price(): Response
    {
        return $this->render('page/price.html.twig', []);
    }

    #[Route('/team', name: 'team')]
    public function team(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Team::class);
        $team = $repository->findAll();

        return $this->render('page/team.html.twig', compact('team'));
    }

    #[Route('/testimonial', name: 'testimonial')]
    public function testimonial(): Response
    {
        return $this->render('page/testimonial.html.twig', []);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', []);
    }

    #[Route('/product', name: 'product')]
    public function product(ProductRepository $repository): Response
    {
        // El servicio nos da los productos
        $products = $repository->findAll();
        
        // Se los pasamos a la vista
        return $this->render('product/product.html.twig', compact('products'));
    }
}
