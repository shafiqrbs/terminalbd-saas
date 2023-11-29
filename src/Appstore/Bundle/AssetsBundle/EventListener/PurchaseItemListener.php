<?php

namespace Appstore\Bundle\AssetsBundle\EventListener;

use Appstore\Bundle\AssetsBundle\Entity\PurchaseItem;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PurchaseItemListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Purchase" entity
        if ($entity instanceof PurchaseItem) {

            $lastCode = $this->getLastCode($args,$entity);
            $entity->setCode($lastCode+1);
            $barcode = $this->getBarcode($entity);
            $entity->setBarcode($barcode);
        }
    }



    /**
     * @param LifecycleEventArgs $args
     * @param $entity
     * @return int|mixed
     */
    public function getLastCode(LifecycleEventArgs $args , PurchaseItem $entity)
    {

        $entityManager = $args->getEntityManager();
        $qb = $entityManager->getRepository('AssetsBundle:PurchaseItem')->createQueryBuilder('s');

        $qb
            ->select('MAX(s.code)')
            ->where('s.config = :config')->setParameter('config', $entity->getConfig()->getId())
            ->andWhere('s.item = :item')->setParameter('item', $entity->getItem()->getId());
            $lastCode = $qb->getQuery()->getSingleScalarResult();

        if (empty($lastCode)) {
            return 0;
        }
        return $lastCode;
    }

    /**
     * @param @entity
     */

    public function getBarcode(PurchaseItem $entity){

        $masterItemCode = $entity->getItem()->getSku();
        return $masterItemCode.$this->getStrPad($entity->getCode(),6);

    }

    private  function getStrPad($lastCode,$limit)
    {
        $data = str_pad($lastCode,$limit, '0', STR_PAD_LEFT);
        return $data;
    }
}