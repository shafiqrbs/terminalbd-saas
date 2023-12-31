<?php

namespace Appstore\Bundle\HotelBundle\Bundle\Repository;
use Appstore\Bundle\MedicineBundle\Entity\MedicineConfig;
use Appstore\Bundle\MedicineBundle\Entity\MedicinePurchaseItem;
use Appstore\Bundle\MedicineBundle\Entity\MedicinePurchaseReturn;
use Appstore\Bundle\MedicineBundle\Entity\MedicineStock;
use Appstore\Bundle\MedicineBundle\Entity\MedicineVendor;
use Doctrine\ORM\EntityRepository;


/**
 * HotelPurchaseReturnRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HotelPurchaseReturnRepository extends EntityRepository
{

    public function updatePurchaseReturnTotalPrice(MedicinePurchaseReturn $entity)
    {
        $em = $this->_em;
        $total = $em->createQueryBuilder()
            ->from('HotelBundle:HotelPurchaseReturnItem','si')
            ->select('sum(si.subTotal) as total')
            ->where('si.businessPurchaseReturn = :entity')
            ->setParameter('entity', $entity ->getId())
            ->getQuery()->getSingleResult();

        if($total['total'] > 0){
            $entity->setSubTotal($total['total']);
        }else{
            $entity->setSubTotal(0);
        }

        $em->persist($entity);
        $em->flush();
        return $entity;

    }
}
