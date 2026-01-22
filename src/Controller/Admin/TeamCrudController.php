<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class TeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Team::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('name'),

            // Esto es vital para que las fotos se guarden en la carpeta correcta
            ImageField::new('photo')->setUploadDir('/public/img')->setBasePath('/img/'),
            Field::new('designation')
        ];
    }
}
