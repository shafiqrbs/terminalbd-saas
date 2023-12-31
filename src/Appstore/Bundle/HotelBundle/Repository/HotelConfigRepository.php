<?php

namespace Appstore\Bundle\HotelBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * HotelConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HotelConfigRepository extends EntityRepository
{


    public function hotelReset(GlobalOption $option)
    {

	    $em = $this->_em;
	    $config = $option->getHotelConfig()->getId();

	    $sales = $em->createQuery('DELETE HotelBundle:HotelInvoice e WHERE e.hotelConfig = '.$config);
	    $sales->execute();

        $stock = $em->createQuery('DELETE HotelBundle:HotelOption e WHERE e.hotelConfig = '.$config);
        $stock->execute();

	    $purchase = $em->createQuery('DELETE HotelBundle:HotelPurchase e WHERE e.hotelConfig = '.$config);
	    $purchase->execute();

	    $stock = $em->createQuery('DELETE HotelBundle:HotelParticular e WHERE e.hotelConfig = '.$config);
        $stock->execute();



    }
}
