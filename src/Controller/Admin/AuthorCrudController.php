<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuthorCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Author::class;
    }

    /**
     * @param string $pageName Page name.
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('first_name'),
            TextField::new('middle_name'),
            TextField::new('last_name'),
            AssociationField::new('books'),
        ];
    }
}
