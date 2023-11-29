<?php

namespace Appstore\Bundle\BusinessBundle\Repository;
use Appstore\Bundle\BusinessBundle\Entity\BusinessConfig;
use Appstore\Bundle\BusinessBundle\Entity\BusinessDistributionReturnItem;
use Appstore\Bundle\BusinessBundle\Entity\BusinessInvoice;
use Appstore\Bundle\BusinessBundle\Entity\BusinessInvoiceParticular;
use Appstore\Bundle\BusinessBundle\Entity\BusinessParticular;
use Appstore\Bundle\BusinessBundle\Entity\BusinessPurchaseReturn;
use Appstore\Bundle\BusinessBundle\Entity\BusinessPurchaseReturnItem;
use Doctrine\ORM\EntityRepository;

/**
 * MedicinePurchaseReturnItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BusinessPurchaseReturnItemRepository extends EntityRepository
{
    public function purchaseReturnStockUpdate(BusinessParticular $item)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.quantity) AS quantity');
        $qb->where('e.businessParticular = :businessParticular')->setParameter('businessParticular', $item->getId());
        $qnt = $qb->getQuery()->getOneOrNullResult();
        return $qnt['quantity'];
    }

    public function remainingStock($config)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.quantity) AS quantity');
        $qb->addSelect('s.id AS id');
        $qb->join('e.businessPurchaseReturn','pr');
        $qb->join('e.businessParticular','s');
        $qb->where('pr.businessConfig = :config')->setParameter('config', $config) ;
        $qb->andWhere("pr.process = 'Approved'");
        $qb->andWhere("pr.mode = 1");
        $qb->groupBy('s.id');
        $result = $qb->getQuery()->getArrayResult();
        $arrs = array();
        foreach ($result as $row){
            $arrs[$row['id']] = $row;
        }
        return  $arrs;
    }

    public function remainingStockItem($config,$item)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.quantity) AS quantity');
        $qb->join('e.businessPurchaseReturn','pr');
        $qb->join('e.businessParticular','s');
        $qb->where('pr.businessConfig = :config')->setParameter('config', $config) ;
        $qb->andWhere('s.id = :item')->setParameter('item',$item) ;
        $result = $qb->getQuery()->getSingleScalarResult();
        return  (int)$result;
    }

    public function insertPurchaseReturnItem(BusinessPurchaseReturn $entity, $data)
    {
        $em = $this->_em;

        $itemIds = $data['itemId'];
        $quantity= $data['quantity'];
        $price = $data['price'];
        foreach ($itemIds as $key  => $itemId):

            if($quantity[$key] > 0 ){

                $product = $em->getRepository('BusinessBundle:BusinessParticular')->find($itemId);
                $item = new BusinessPurchaseReturnItem();
                $item->setBusinessPurchaseReturn($entity);
                $item->setBusinessParticular($product);
                $item->setQuantity($quantity[$key]);
                $item->setPurchasePrice($price[$key]);
                $item->setSubTotal($price[$key] * $quantity[$key]);
                $em->persist($item);
                $em->flush();

            }

        endforeach;
    }

    public function insertStockPurchaseReturnItem(BusinessPurchaseReturn $entity,BusinessParticular $particular)
    {
        $em = $this->_em;
        $item = new BusinessPurchaseReturnItem();
        $item->setBusinessPurchaseReturn($entity);
        $item->setBusinessParticular($particular);
        $item->setPurchasePrice($particular->getPurchasePrice());
        $item->setSubTotal(0);
        $em->persist($item);
        $em->flush();
    }

    public function deletePurchaseReturnItem(BusinessInvoiceParticular $invoiceParticular)
    {
       $em = $this->_em;
       if($invoiceParticular){
           $entity = $this->findOneBy(array('salesInvoiceItem' => $invoiceParticular->getId()));
           if($entity){
               $em->remove($entity);
               $em->flush();
               $em->getRepository('BusinessBundle:BusinessParticular')->updateRemoveStockQuantity($invoiceParticular->getBusinessParticular(), "purchase-return");
           }
       }


    }

    public function removePurchaseReturn(BusinessInvoice $entity)
    {
        $em = $this->_em;
        $DistributionReturnItem = $em->createQuery("DELETE BusinessBundle:BusinessDistributionReturnItem e WHERE e.salesInvoice = {$entity->getId()}");
        $DistributionReturnItem->execute();
        foreach ($entity->getBusinessInvoiceParticulars() as $particular){
            $this->deletePurchaseReturnItem($particular);
        }
    }

    public function updatReturnQuantity(BusinessDistributionReturnItem $item)
    {
        $em = $this->_em;
        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.quantity) AS quantity');
        $qb->where('e.distributionReturnItem = :businessParticular')->setParameter('businessParticular', $item->getId());
        $qnt = $qb->getQuery()->getOneOrNullResult();
        $remain = $item->getQuantity() - $qnt['quantity'];
        $item->setRemainingQnt($remain);
        $em->persist($item);
        $em->flush();

    }

}
