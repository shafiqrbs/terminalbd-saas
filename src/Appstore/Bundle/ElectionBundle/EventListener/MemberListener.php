<?php

namespace Appstore\Bundle\ElectionBundle\EventListener;

use Appstore\Bundle\ElectionBundle\Entity\ElectionMember;
use Doctrine\ORM\Event\LifecycleEventArgs;

class MemberListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // perhaps you only want to act on some "Purchase" entity
        if ($entity instanceof ElectionMember) {
            $lastCode = $this->getLastCode($args,$entity);
	        $entity->setCode($lastCode + 1);
	        $entity->setMemberId(sprintf("%s%s", $entity->getElectionConfig()->getGlobalOption()->getId(), str_pad($entity->getCode(), 5, '0', STR_PAD_LEFT)));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $datetime
     * @param $entity ElectionMember
     * @return int|mixed
     */
    public function getLastCode(LifecycleEventArgs $args,$entity)
    {

        $entityManager = $args->getEntityManager();
        $qb = $entityManager->getRepository('ElectionBundle:ElectionMember')->createQueryBuilder('s');
        $qb
            ->select('MAX(s.code)')
            ->where('s.electionConfig = :config')
            ->setParameter('config', $entity->getElectionConfig());
            $lastCode = $qb->getQuery()->getSingleScalarResult();

        if (empty($lastCode)) {
            return 0;
        }
        return $lastCode;
    }
}