<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            // Configuramos la subida de imágenes
            ImageField::new('photo')
                ->setUploadDir('public/img')
                ->setBasePath('img'),
            // Configuración para el precio (en euros)
            MoneyField::new('price')->setCurrency('EUR')->setStoredAsCents(false),
        ];
    }
}
