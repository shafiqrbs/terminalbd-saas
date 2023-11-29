<?php

namespace Appstore\Bundle\ElectionBundle\EventListener;

use Appstore\Bundle\InventoryBundle\Entity\Item;
use Appstore\Bundle\ElectionBundle\Entity\ElectionParticular;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ParticularListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Purchase" entity
        if ($entity instanceof ElectionParticular) {
            $lastCode = $this->getLastCode($args,$entity);
            $entity->setCode((int)$lastCode+1);
            $entity->setSku(sprintf("%s",str_pad($entity->getCode(),3, '0', STR_PAD_LEFT)));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $entity
     * @return int|mixed
     */
    public function getLastCode(LifecycleEventArgs $args,ElectionParticular $entity)
    {

        $entityManager = $args->getEntityManager();
        $qb = $entityManager->getRepository( 'ElectionBundle:ElectionParticular' )->createQueryBuilder('s');
        $qb
            ->select('MAX(s.code)')
            ->where('s.electionConfig = :config')
            ->setParameter('config', $entity->getElectionConfig())
            ->andWhere('s.particularType = :type')
            ->setParameter('type', $entity->getParticularType());
            $lastCode = $qb->getQuery()->getSingleScalarResult();

        if (empty($lastCode)) {
            return 0;
        }
        return $lastCode;
    }

}