<?php

namespace Appstore\Bundle\EducationBundle\EventListener;

use Appstore\Bundle\EducationBundle\Entity\EducationParticular;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EducationParticularListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createCode($args);
    }

    public function createCode(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();
        if ($entity instanceof EducationParticular) {
            $lastCode = $this->getLastCode($args,$entity);
            $entity->setCode((int)$lastCode + 1);
            $entity->setParticularCode(sprintf("%s",str_pad($entity->getCode(),3, '0', STR_PAD_LEFT)));

        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $entity
     * @return int|mixed
     */
    public function getLastCode(LifecycleEventArgs $args, $entity)
    {

        $class = get_class($entity);
        $entityManager = $args->getEntityManager();
        $qb = $entityManager->getRepository($class)->createQueryBuilder('s');
        $qb
            ->select('MAX(s.code)')
            ->where('s.educationConfig = :EducationConfig')
            ->setParameter('EducationConfig', $entity->getEducationConfig());
        $lastCode = $qb->getQuery()->getSingleScalarResult();

        if (empty($lastCode)) {
            return 0;
        }
        return $lastCode;

    }


}