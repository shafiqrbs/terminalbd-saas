<?php

namespace Appstore\Bundle\HotelBundle\EventListener;

use Appstore\Bundle\HotelBundle\Entity\HotelInvoice;
use Doctrine\ORM\Event\LifecycleEventArgs;

class HotelInvoiceListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Sales" entity
        if ($entity instanceof HotelInvoice) {


            $datetime = new \DateTime("now");
            $lastCode = $this->getLastCode($args, $datetime, $entity);
            $entity->setCode($lastCode+1);
            if(empty($entity->getHotelConfig()->getInvoicePrefix())){
                $entity->setInvoice(sprintf("%s%s", $datetime->format('ym'), str_pad($entity->getCode(),4, '0', STR_PAD_LEFT)));
            }else{
                $entity->setInvoice(sprintf("%s%s%s", $entity->getHotelConfig()->getInvoicePrefix(), $datetime->format('ym'), str_pad($entity->getCode(),4, '0', STR_PAD_LEFT)));
            }

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
        $qb = $entityManager->getRepository('HotelBundle:HotelInvoice')->createQueryBuilder('s');
        $qb
            ->select('MAX(s.code)')
            ->where('s.hotelConfig = :config')
            ->setParameter('config', $entity->getHotelConfig())
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