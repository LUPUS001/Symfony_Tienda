<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/cart')]
class CartController extends AbstractController
{
    private $doctrine;
    private $repository;
    private $cart;
    
    //Le inyectamos CartService como una dependencia
    public  function __construct(ManagerRegistry $doctrine, CartService $cart)
    {
        $this->doctrine = $doctrine;
        $this->repository = $doctrine->getRepository(Product::class);
        $this->cart = $cart;
    }

    #[Route('/add/{id}', name: 'cart_add', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function cart_add(int $id): Response
    {
        // Buscamos el producto por ID
        $product = $this->repository->find($id);

        // Si no existe, devolvemos error 404
        if (!$product) {
            return new JsonResponse("[]", Response::HTTP_NOT_FOUND);
        }

        // Añadimos al carrito usando el servicio
        $this->cart->add($id, 1);
        
        // Preparamos los datos para devolver el JSON actualizado
        // NOTA: Revisa que estos getters (getNombre, getPrecio...) coincidan con tu Entidad Producto.php
        $data = [
            "id" => $product->getId(),
            "name" => $product->getName(),  
            "price" => $product->getPrice(), 
            "photo" => $product->getPhoto(), 
            "quantity" => $this->cart->getCart()[$product->getId()]
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}