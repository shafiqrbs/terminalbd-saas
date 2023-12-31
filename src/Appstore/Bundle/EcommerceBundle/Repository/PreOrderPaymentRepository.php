<?php

namespace Appstore\Bundle\EcommerceBundle\Repository;
use Appstore\Bundle\EcommerceBundle\Entity\PreOrder;
use Appstore\Bundle\EcommerceBundle\Entity\PreOrderPayment;
use Doctrine\ORM\EntityRepository;

/**
 * PreOrderPaymentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PreOrderPaymentRepository extends EntityRepository
{

    public function updatePreOrderPayment(PreOrder $order)
    {
        $em = $this->_em;
        foreach ($order->getPreOrderPayments() as $item){

            /* @var $item PreOrderPayment */
            if($item->getStatus() == 0 ){
                $item->setStatus(2);
                $em->persist($item);
                $em->flush($item);
            }
        }
    }



}
