<?php

namespace Appstore\Bundle\HotelBundle\Repository;
use Appstore\Bundle\HotelBundle\Entity\HotelParticular;
use Appstore\Bundle\MedicineBundle\Entity\MedicineVendor;
use Doctrine\ORM\EntityRepository;


/**
 * HotelInvoiceReturnRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HotelInvoiceReturnRepository extends EntityRepository
{

    public function salesReturnStockUpdate(HotelParticular $item)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.quantity) AS quantity');
        $qb->where('e.businessParticular = :businessParticular')->setParameter('businessParticular', $item->getId());
        $qnt = $qb->getQuery()->getOneOrNullResult();
        return $qnt['quantity'];
    }


    public function checkInInsert(HotelConfig $config , $vendor)
    {
        $entity = $this->findOneBy(array('businessConfig' => $config,'companyName' => $vendor));
        if(empty($entity)){
            $entity = new MedicineVendor();
            $entity->setHotelConfig($config);
            $entity->setCompanyName($vendor);
            $entity->setName($vendor);
            $this->_em->persist($entity);
            $this->_em->flush();
        }
        return $entity;
    }

}
