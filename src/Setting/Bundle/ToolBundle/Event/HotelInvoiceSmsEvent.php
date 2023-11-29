<?php
/**
 * Created by PhpStorm.
 * User: dhaka
 * Date: 8/19/14
 * Time: 5:24 PM
 */

namespace Setting\Bundle\ToolBundle\Event;

use Appstore\Bundle\DmsBundle\Entity\DmsInvoice;
use Appstore\Bundle\HotelBundle\Entity\HotelInvoice;
use Symfony\Component\EventDispatcher\Event;


class HotelInvoiceSmsEvent extends Event
{

	/** @var HotelInvoice  */
	protected $hotelInvoice;


	public function __construct(HotelInvoice $hotelInvoice)
	{
		$this->hotelInvoice = $hotelInvoice;
	}

	/**
	 * @return HotelInvoice
	 */
	public function getHotelInvoice()
	{
		return $this->hotelInvoice;
	}


}