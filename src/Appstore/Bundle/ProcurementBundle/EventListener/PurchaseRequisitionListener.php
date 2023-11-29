<?php

namespace Appstore\Bundle\ProcurementBundle\EventListener;

use Appstore\Bundle\InventoryBundle\Entity\Purchase;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisition;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PurchaseRequisitionListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Purchase" entity
        if ($entity instanceof PurchaseRequisition) {

            $datetime = new \DateTime("now");
            $lastCode = $this->getLastCode($args, $datetime, $entity);
            $entity->setCode($lastCode+1);
            $entity->setGrn(sprintf("%s%s",$datetime->format('my'), str_pad($entity->getCode(),5, '0', STR_PAD_LEFT)));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $datetime
     * @param $entity
     * @return int|mixed
     */
    public function getLastCode(LifecycleEventArgs $args, $datetime, $entity)
    {

        $today_startdatetime = $datetime->format('Y-m-01 00:00:00');
        $today_enddatetime = $datetime->format('Y-m-t 23:59:59');


        $entityManager = $args->getEntityManager();
        $qb = $entityManager->getRepository('ProcurementBundle:PurchaseRequisition')->createQueryBuilder('s');

        $qb
            ->select('MAX(s.code)')
            ->andWhere('s.config = :config')->setParameter('config', $entity->getId())
            ->andWhere('s.updated >= :today_startdatetime')->setParameter('today_startdatetime', $today_startdatetime)
            ->andWhere('s.updated <= :today_enddatetime')->setParameter('today_enddatetime', $today_enddatetime);
        $lastCode = $qb->getQuery()->getSingleScalarResult();

        if (empty($lastCode)) {
            return 0;
        }

        return $lastCode;
    }
}