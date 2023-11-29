<?php

namespace Appstore\Bundle\AssetsBundle\EventListener;

use Appstore\Bundle\AssetsBundle\Entity\Product;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ProductListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Purchase" entity
        if ($entity instanceof Product) {

            $datetime = new \DateTime("now");
            $lastCode = $this->getLastCode($args, $datetime, $entity);
            $entity->setCode($lastCode+1);
        //    $branch = $entity->getBranch()->getBranchCode().'-';
           // $entity->setBranchSerialNo(sprintf("%s%s",$branch, str_pad($entity->getCode(),6, '0', STR_PAD_LEFT)));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $datetime
     * @param $entity
     * @return int|mixed
     */
    public function getLastCode(LifecycleEventArgs $args, $datetime, Product $entity)
    {

        $today_startdatetime = $datetime->format('Y-m-01 00:00:00');
        $today_enddatetime = $datetime->format('Y-m-t 23:59:59');

/*
        $entityManager = $args->getEntityManager();
        $qb = $entityManager->getRepository('AssetsBundle:Product')->createQueryBuilder('s');
        $qb
            ->select('MAX(s.code)')
	        ->where('s.branch = :branch')->setParameter('branch', $entity->getBranch()->getId());
        $lastCode = $qb->getQuery()->getSingleScalarResult();
        if (empty($lastCode)) {
            return 0;
        }
        return $lastCode;*/
    }
}