<?php

namespace Appstore\Bundle\AccountingBundle\Repository;
use Appstore\Bundle\AccountingBundle\Entity\AccountBank;
use Appstore\Bundle\AccountingBundle\Entity\AccountJournal;
use Appstore\Bundle\AccountingBundle\Entity\AccountPurchase;
use Appstore\Bundle\InventoryBundle\Entity\Sales;
use Appstore\Bundle\InventoryBundle\Entity\SalesReturn;
use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\Appstore\Bundle\DomainUserBundle\Entity\PaymentSalary;

/**
 * AccountMobileBankRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountMobileBankRepository extends EntityRepository
{



    public function findWithSearch($globalOption,$data = '')
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where("e.globalOption = :globalOption");
        $qb->setParameter('globalOption', $globalOption);
       // $this->handleSearchBetween($qb,$data);
        $qb->orderBy('e.name','ASC');
        $result = $qb->getQuery()->getResult();
        return $result;

    }

    public function entityOverview($globalOption,$data)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.debit) AS debit, SUM(e.credit) AS credit');
        $qb->where("e.globalOption = :globalOption");
        $qb->setParameter('globalOption', $globalOption);
        $this->handleSearchBetween($qb,$data);
        $result = $qb->getQuery()->getSingleResult();
        $data =  array('debit'=> $result['debit'],'credit'=> $result['credit']);
        return $data;
    }

    /**
     * @param $qb
     * @param $data
     */

    protected function handleSearchBetween($qb,$data)
    {
        if(empty($data))
        {
           /* $datetime = new \DateTime("now");
            $startDate = $datetime->format('Y-m-d 00:00:00');
            $endDate = $datetime->format('Y-m-d 23:59:59');

            $qb->andWhere("e.updated >= :startDate");
            $qb->setParameter('startDate', $startDate);
            $qb->andWhere("e.updated <= :endDate");
            $qb->setParameter('endDate', $endDate);*/

        }else{

            $startDate = isset($data['startDate'])  ? $data['startDate'] : '';
            $endDate =   isset($data['endDate'])  ? $data['endDate'] : '';
            $toUser =    isset($data['toUser'])? $data['toUser'] :'';
            $account =    isset($data['accountHead'])? $data['accountHead'] :'';

            if (!empty($data['startDate']) and !empty($data['endDate']) ) {

                $qb->andWhere("e.updated >= :startDate");
                $qb->setParameter('startDate', $startDate.' 00:00:00');
            }
            if (!empty($data['endDate']) and !empty($data['startDate'])) {

                $qb->andWhere("e.updated <= :endDate");
                $qb->setParameter('endDate', $endDate.' 23:59:59');
            }
            if (!empty($toUser)) {
                $qb->join('e.toUser','u');
                $qb->andWhere("u.username = :user");
                $qb->setParameter('user', $toUser);
            }
            if (!empty($account)) {
                $qb->join('e.accountHead','a');
                $qb->andWhere("a.id = :account");
                $qb->setParameter('account', $account);
            }
        }

    }


}
