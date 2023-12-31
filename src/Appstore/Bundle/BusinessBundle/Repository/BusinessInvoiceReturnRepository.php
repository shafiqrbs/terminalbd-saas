<?php

namespace Appstore\Bundle\BusinessBundle\Repository;
use Appstore\Bundle\AccountingBundle\Entity\AccountVendor;
use Appstore\Bundle\BusinessBundle\Entity\BusinessConfig;
use Appstore\Bundle\BusinessBundle\Entity\BusinessInvoice;
use Appstore\Bundle\BusinessBundle\Entity\BusinessInvoiceReturn;
use Appstore\Bundle\BusinessBundle\Entity\BusinessInvoiceReturnItem;
use Appstore\Bundle\BusinessBundle\Entity\BusinessPurchase;
use Appstore\Bundle\BusinessBundle\Entity\BusinessPurchaseItem;
use Appstore\Bundle\BusinessBundle\Entity\BusinessParticular;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;


/**
 * BusinessPurchaseItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BusinessInvoiceReturnRepository extends EntityRepository
{
    protected function handleSearchBetween($qb,$data)
    {

        $grn = isset($data['grn'])? $data['grn'] :'';
        $business = isset($data['name'])? $data['name'] :'';
        $brand = isset($data['brandName'])? $data['brandName'] :'';
        $mode = isset($data['mode'])? $data['mode'] :'';
        $customer = isset($data['customer'])? $data['customer'] :'';
        $startDate = isset($data['startDate'])? $data['startDate'] :'';
        $endDate = isset($data['endDate'])? $data['endDate'] :'';

        if (!empty($grn)) {
            $qb->andWhere($qb->expr()->like("e.invoice", "'%$grn%'"  ));
        }
        if(!empty($business)){
            $qb->andWhere($qb->expr()->like("ms.name", "'%$business%'"  ));
        }
        if(!empty($brand)){
            $qb->andWhere($qb->expr()->like("ms.brandName", "'%$brand%'"  ));
        }
        if(!empty($mode)){
            $qb->andWhere($qb->expr()->like("ms.mode", "'%$mode%'"  ));
        }
        if (!empty($customer)) {
            $qb->join('e.customer','c');
            $qb->andWhere($qb->expr()->like("c.name", "'$customer%'"  ));
        }
        if (!empty($startDate) ) {
            $datetime = new \DateTime($data['startDate']);
            $start = $datetime->format('Y-m-d 00:00:00');
            $qb->andWhere("e.updated >= :startDate");
            $qb->setParameter('startDate', $start);
        }

        if (!empty($endDate)) {
            $datetime = new \DateTime($data['endDate']);
            $end = $datetime->format('Y-m-d 23:59:59');
            $qb->andWhere("e.updated <= :endDate");
            $qb->setParameter('endDate', $end);
        }
    }

    public function findWithSearch($config, $data = array())
    {

        $qb = $this->createQueryBuilder('e');
        $qb->where('e.businessConfig = :config')->setParameter('config', $config) ;
        $this->handleSearchBetween($qb,$data);
        $qb->orderBy('e.updated','DESC');
        $qb->getQuery();
        return  $qb;
    }

    public function checkInvoiceReturnCreate(BusinessInvoice $invoice)
    {
        $em = $this->_em;
        $exist = $this->findOneBy(array('businessConfig'=>$invoice->getBusinessConfig(),'businessInvoice' => $invoice));
        if(empty($exist)){
            $entity = new BusinessInvoiceReturn();
            $entity->setBusinessConfig($invoice->getBusinessConfig());
            $entity->setBusinessInvoice($invoice);
            $entity->setCustomer($invoice->getCustomer());
            $time = time();
            $timebd = (string)($time);
            $entity->setInvoice($timebd);
            $entity->setAdjustment(0);
            $entity->setPayment(0);
            $em->persist($entity);
            $em->flush();
            return $entity;
        }else{
            $exist->setCustomer($invoice->getCustomer());
            $exist->setAdjustment(0);
            $exist->setPayment(0);
            $em->persist($exist);
            $em->flush();
            return $exist;
        }
    }
}
