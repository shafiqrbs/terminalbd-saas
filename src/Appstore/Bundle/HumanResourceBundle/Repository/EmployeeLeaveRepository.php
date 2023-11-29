<?php

namespace Appstore\Bundle\HumanResourceBundle\Repository;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;



/**
 * EmployeeLeaveRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmployeeLeaveRepository extends EntityRepository
{
    public function leaveTypeWiseAbsence(User $user,$leave ='')
    {
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.leaveSetup', 'ls');
        $qb->select('ls.name as leaveTypeName,ls.offDay AS noOfLeave, e.year as year , sum(e.noOffDay) AS offDay');
        $qb->where('e.employee = :employee')->setParameter('employee', $user->getId());
        if ($leave){
            $qb->andWhere('e.leaveSetup = :leave')->setParameter('leave',$leave);
        }
        $qb->andWhere('e.approvedBy IS NOT NULL');
        $qb->groupBy('e.leaveSetup, e.year');
        $result = $qb->getQuery()->getArrayResult();
        return $result;

    }

    public function getSpecificLeaveTypeWiseLeave(User $user,$leaveType)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.leaveSetup','ls');
        $qb->select('ls.name as leaveTypeName,ls.offDay AS noOfLeave, e.year as year sum(e.noOffDay) offDay');
        $qb->where('e.employee = :employee')->setParameter('employee', $user->getId());
        $qb->where('e.employee = :employee')->setParameter('employee', $user->getId());
        $qb->groupBy('e.leaveSetup, e.year');
        $result = $qb->getQuery()->getArrayResult();
        return $result;

    }
}