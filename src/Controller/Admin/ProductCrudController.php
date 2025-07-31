<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Product>
 */
class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }  public function configureCrud(Crud $crud): Crud
{
    // Modifier le titre de la page
    return $crud
        ->setPageTitle(Crud::PAGE_INDEX, 'Produits')
        ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un produit')
        ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un produit')
        ->setPageTitle(Crud::PAGE_DETAIL, 'Détails du produit');
}

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnDetail(),
            TextField::new('name')->setLabel('Nom du produit'),
            TextField::new('image'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR')->setLabel('Prix'),
            IntegerField::new('stock'),
            DateTimeField::new('createdAt')->onlyOnDetail()->setLabel('Créé le'),
            DateTimeField::new('updatedAt')->onlyOnDetail()->setLabel('Modifier le'),
            AssociationField::new('category')->setLabel('Categorie'),
        ];
    }
}
