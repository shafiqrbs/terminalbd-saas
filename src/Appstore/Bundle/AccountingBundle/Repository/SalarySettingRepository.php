<?php

namespace Appstore\Bundle\AccountingBundle\Repository;
use Appstore\Bundle\AccountingBundle\Entity\PaymentSalary;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * EmployeeSalaryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SalarySettingRepository extends EntityRepository
{

    public function findWithSearch($globalOption,$data)
    {
        $user = isset($data['user'])? $data['user'] :'';
        $qb = $this->createQueryBuilder('e');
        $qb->where("e.globalOption = :globalOption");
        $qb->setParameter('globalOption', $globalOption);
        if (!empty($user)) {
            $qb->join('e.user','user');
            $qb->andWhere("user.username = :user");
            $qb->setParameter('user', $user);
        }
        $qb->addOrderBy('e.updated','DESC');
        $result = $qb->getQuery();
        return $result;

    }

    public function invoiceAmountOverview($globalOption,$data){

        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.totalAmount) AS totalAmount, SUM(e.advanceAmount) AS advanceAmount, SUM(e.basicAmount) AS basicAmount, SUM(e.otherAmount) AS otherAmount, SUM(e.bonusAmount) AS bonusAmount');
        $qb->where("e.process = 'completed' ");
        $qb->andWhere("e.globalOption = :globalOption");
        $qb->setParameter('globalOption', $globalOption->getId());
        if(!empty($data['toUser'])){
            $qb->andWhere("e.user = :user");
            $qb->setParameter('user', $data['toUser']->getId());
        }
        $result = $qb->getQuery()->getArrayResult();
        $values =  array('totalAmount'=>$result[0]['totalAmount'],'advanceAmount'=>$result[0]['advanceAmount'],'basicAmount'=>$result[0]['basicAmount'],'otherAmount'=>$result[0]['otherAmount'],'bonusAmount'=>$result[0]['bonusAmount']);
        return $values;
    }

    public function invoiceUpdate(PaymentSalary $entity)
    {
       $invoice =  $entity->getSalarySetting();
       $invoice->setProcess('completed');
       $this->_em->persist($invoice);
       $this->_em->flush();
    }
}