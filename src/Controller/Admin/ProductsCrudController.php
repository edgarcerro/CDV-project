<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;


class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('category_id')
                ->autocomplete()
                ->setRequired(true)
                ->formatValue(function ($value, $entity) {
                    return $entity->getCategoryId()->getName();
                }),
            IntegerField::new('stock')
                ->setFormTypeOptions([
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual([
                            'value' => 0,
                            'message' => 'El valor del stock debe ser mayor o igual a 0.'
                        ])
                    ],
                ]),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }

     public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->setIcon('fa fa-edit'); 
        });

        $actions->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action->setIcon('fa fa-trash'); 
        });

        $viewStockHistory = Action::new('viewStockHistory', 'Ver Historial')
            ->linkToCrudAction('stockHistory')
            ->setIcon('fa fa-eye');

        $actions->add(Crud::PAGE_INDEX, $viewStockHistory);

        return $actions;
    }

   
}
