<?php

namespace Appstore\Bundle\AssetsBundle\EventListener;

use Appstore\Bundle\AssetsBundle\Entity\Item;
use Appstore\Bundle\AssetsBundle\Entity\OfficeNote;
use Doctrine\ORM\Event\LifecycleEventArgs;

class OfficeNoteListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Purchase" entity
        if ($entity instanceof OfficeNote) {
            $lastCode = $this->getLastCode($args, $entity);
            $entity->setCode($lastCode+1);
            $month = $entity->getUpdated()->format('M-y');
            $entity->setRefNo(sprintf("%s%s%s", "BSSF/",$month,str_pad($entity->getCode(),4, '0', STR_PAD_LEFT)));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $datetime
     * @param $entity
     * @return int|mixed
     */
    public function getLastCode(LifecycleEventArgs $args, OfficeNote $entity)
    {

        $datetime = new \DateTime("now");
        $today_startdatetime = $datetime->format('Y-m-01 00:00:00');
        $today_enddatetime = $datetime->format('Y-m-t 23:59:59');
        $entityManager = $args->getEntityManager();
        $qb = $entityManager->getRepository('AssetsBundle:OfficeNote')->createQueryBuilder('s');
        $qb
            ->select('MAX(s.code)')
	        ->where('s.config = :config')->setParameter('config', $entity->getConfig()->getId())
            ->andWhere('s.updated >= :today_startdatetime')
            ->andWhere('s.updated <= :today_enddatetime')
            ->setParameter('today_startdatetime', $today_startdatetime)
            ->setParameter('today_enddatetime', $today_enddatetime);
        $lastCode = $qb->getQuery()->getSingleScalarResult();
        if (empty($lastCode)) {
            return 0;
        }
        return $lastCode;
    }
}