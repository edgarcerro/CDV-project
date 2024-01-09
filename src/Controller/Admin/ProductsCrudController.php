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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ProductsCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

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

        $actions->add(Crud::PAGE_INDEX, Action::new('viewStockHistory', 'Ver Historial')
            ->linkToUrl(function () {
                return $this->adminUrlGenerator->setController(StockHistoricCrudController::class)->generateUrl();
            })
            ->setIcon('fa fa-eye')
        );

        return $actions;
    }

}
