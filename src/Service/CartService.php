<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private const KEY = '_cart';
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        // Inyectamos el servicio RequestStack para poder acceder a la sesión
        $this->requestStack = $requestStack;
    }

    public function getSession()
    {
        return $this->requestStack->getSession();
    }

    public function getCart(): array 
    {
        // Obtenemos el carrito de la sesión. Si no existe, devolvemos un array vacío []
        return $this->getSession()->get(self::KEY, []);
    }

    public function add(int $id, int $quantity = 1)
    {
        // 1. Obtenemos el carrito actual
        $cart = $this->getCart();

        // 2. Solo añadimos el producto si NO existe ya en el carrito
        // (Nota: En este paso del tutorial no estamos sumando cantidades si ya existe, 
        // solo lo registramos si es nuevo).
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = $quantity;
        }

        // 3. Guardamos el carrito actualizado en la sesión
        $this->getSession()->set(self::KEY, $cart);
    }

    public function update(int $id, int $quantity): void
    {
        $cart = $this->getCart(); // Obtenemos el carrito actual

        // Solo actualizamos si el producto ya existe en el carro
        if (isset($cart[$id])) {
            $cart[$id] = $quantity;
            $this->getSession()->set(self::KEY, $cart);
        }
    }
}