<?php

namespace Appstore\Bundle\AssetsBundle\EventListener;

use Appstore\Bundle\AssetsBundle\Entity\Purchase;
use Appstore\Bundle\AssetsBundle\Entity\Receive;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ReceiveListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Purchase" entity
        if ($entity instanceof Receive) {

            $datetime = new \DateTime("now");
            $lastCode = $this->getLastCode($args, $datetime, $entity);
            $entity->setCode($lastCode+1);
            $entity->setGrn(sprintf("%s%s",$datetime->format('my'), str_pad($entity->getCode(),4, '0', STR_PAD_LEFT)));

        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $datetime
     * @param $entity
     * @return int|mixed
     */
    public function getLastCode(LifecycleEventArgs $args, $datetime, Receive $entity)
    {

        $start = $datetime->format('Y-m-01 00:00:00');
        $end = $datetime->format('Y-m-t 23:59:59');
        $entityManager = $args->getEntityManager();
        $qb = $entityManager->getRepository('AssetsBundle:Receive')->createQueryBuilder('s');
        $qb
            ->select('MAX(s.code)')
            ->where('s.config = :config')->setParameter('config', $entity->getConfig())
            ->andWhere('s.created >= :start') ->setParameter('start', $start)
            ->andWhere('s.created <= :end')->setParameter('end', $end);
        $lastCode = $qb->getQuery()->getSingleScalarResult();
        if (empty($lastCode)) {
            return 0;
        }
        return $lastCode;
    }
}