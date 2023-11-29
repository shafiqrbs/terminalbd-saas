<?php

namespace Appstore\Bundle\AccountingBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * AccountSalesAdjustmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountSalesAdjustmentRepository extends EntityRepository
{

    public function findWithSearch($globalOption,$data = '')
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where("e.globalOption = :globalOption");
        $qb->setParameter('globalOption', $globalOption);
        $this->handleSearchBetween($qb,$data);
        $qb->orderBy('e.updated','DESC');
        $result = $qb->getQuery();
        return $result;

    }

    public function accountCashOverview($globalOption,$data)
    {

        $qb = $this->createQueryBuilder('e');
        $qb->select('COALESCE(SUM(e.purchase),0) AS purchase, COALESCE(SUM(e.sales),0) AS sales, COALESCE(SUM(e.profit),0) AS profit');
        $qb->where("e.globalOption = :globalOption");
        $qb->setParameter('globalOption', $globalOption);
        $qb->andWhere("e.process = :process");
        $qb->setParameter('process', 'approved');
        $this->handleSearchBetween($qb,$data);
        $result = $qb->getQuery()->getOneOrNullResult();
        return $result;

    }

    /**
     * @param $qb
     * @param $data
     */

    protected function handleSearchBetween($qb,$data)
    {
        if(empty($data))
        {
                $datetime = new \DateTime("now");
                $startDate = $datetime->format('Y-m-d 00:00:00');
                $endDate = $datetime->format('Y-m-d 23:59:59');

                /*
                $qb->andWhere("e.updated >= :startDate");
                $qb->setParameter('startDate', $startDate);
                $qb->andWhere("e.updated <= :endDate");
                $qb->setParameter('endDate', $endDate);
                */

        }else{
                $process =    isset($data['process'])? $data['process'] :'';
                if (!empty($data['startDate']) and !empty($data['endDate']) ) {
                    $datetime = new \DateTime($data['startDate']);
                    $startDate = $datetime->format('Y-m-d 00:00:00');
                    $qb->andWhere("e.updated >= :startDate");
                    $qb->setParameter('startDate',$startDate);
                }
                if (!empty($data['endDate']) and !empty($data['startDate'])) {
                    $datetime = new \DateTime($data['endDate']);
                    $endDate = $datetime->format('Y-m-d 23:59:59');
                    $qb->andWhere("e.updated <= :endDate");
                    $qb->setParameter('endDate', $endDate);
                }
                if (!empty($process)) {
                    $qb->andWhere("e.processHead = :process");
                    $qb->setParameter('process', $process);
                }

        }

    }

}