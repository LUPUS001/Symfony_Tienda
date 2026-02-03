<?php

namespace App\Repository;

use App\Entity\Product;
use App\Service\CartService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // Necesitamos que el repositorio sea capaz de traducir la lista de IDs de la sesión 
    // (ej: [1, 5]) en objetos reales de la base de datos.
    public function getFromCart(CartService $cart): array
    {
        // Si el carrito está vacío, devolvemos array vacío
        if (empty($cart->getCart())) {
            return [];
        }

        // Obtenemos los IDs (las claves del array: 1, 2, 5...)
        $ids = implode(',', array_keys($cart->getCart()));

        // Creamos la consulta: SELECT * FROM product WHERE id IN (1, 5)
        return $this->createQueryBuilder('p')
            ->andWhere("p.id in ($ids)")
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
