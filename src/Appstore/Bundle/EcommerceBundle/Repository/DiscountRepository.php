<?php

namespace Appstore\Bundle\EcommerceBundle\Repository;
use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;

/**
 * DiscountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DiscountRepository extends EntityRepository
{
    public function getFeatureDiscount($config, $limit)
    {
        $query = $this->createQueryBuilder('e');
        $query->where("e.ecommerceConfig = :config")->setParameter('config', $config);
        $query->andWhere("e.status = 1");
        $query->andWhere("e.feature = 1");
        $query->orderBy('e.name', 'ASC');
        $query->setMaxResults($limit);
        return $query->getQuery()->getResult();

    }
}
