<?php

namespace App\EventListener;

use App\Entity\Products;
use App\Entity\StockHistoric;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\EntityManagerInterface;

class ProductStockChangeListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function preUpdate(Products $product, PreUpdateEventArgs $eventArgs): void
    {
        if ($eventArgs->hasChangedField('stock')) {
            $originalStock = $eventArgs->getOldValue('stock');
            $newStock = $eventArgs->getNewValue('stock');
            $stockDifference = $newStock - $originalStock;

            $stockHistoric = new StockHistoric();
            $stockHistoric->setProductId($product);
            $stockHistoric->setStock($stockDifference);
            $stockHistoric->setCreatedAt(new \DateTime());

            $this->entityManager->persist($stockHistoric);
            $this->entityManager->flush();
        }
    }
}
