<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/cart')]
class CartController extends AbstractController
{
    private $repository;
    private $cart;
    
    // Modificamos el constructor para inyectar ProductRepository directamente
    public  function __construct(ProductRepository $repository, CartService $cart)
    {
        $this->repository = $repository;
        $this->cart = $cart;
    }

    #[Route('/', name: 'app_cart')]
    public function index(): Response
    {
        // 1. Obtenemos los productos de la BD usando nuestro método nuevo
        $products = $this->repository->getFromCart($this->cart);

        $items = [];
        $totalCart = 0;

        // 2. Recorremos cada producto para añadirle la cantidad (que viene de la sesión)
        foreach($products as $product){
            $item = [
                "id" => $product->getId(),
                "name" => $product->getName(),
                "price" => $product->getPrice(),
                "photo" => $product->getPhoto(), // Ojo: usa getImagen() si tu entidad está en español
                "quantity" => $this->cart->getCart()[$product->getId()]
            ];
            
            // Calculamos el total (precio * cantidad)
            $totalCart += $item["quantity"] * $item["price"];
            $items[] = $item;
        }

        // 3. Renderizamos la vista
        return $this->render('cart/index.html.twig', [
            'items' => $items, 
            'totalCart' => $totalCart
        ]);
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

    // Creamos la ruta que recibirá la orden del JavaScript para actualizar la cantidad
    #[Route('/update/{id}/{quantity}', name: 'cart_update', methods: ['POST', 'GET'], requirements: ['id' => '\d+', 'quantity' => '\d+'])]
    public function cart_update(int $id, int $quantity): JsonResponse
    {
        $this->cart->update($id, $quantity);
        
        // Devolvemos un OK básico
        return new JsonResponse(['status' => 'success']);
    }

    // Nueva ruta para eliminar un producto del carrito
    // Esta ruta recibirá la petición de borrado.
    #[Route('/delete/{id}', name: 'cart_delete', methods: ['POST', 'DELETE'], requirements: ['id' => '\d+'])]
    public function cart_delete(int $id): JsonResponse
    {
        $this->cart->delete($id);
        return new JsonResponse(['status' => 'success']);
    }
}