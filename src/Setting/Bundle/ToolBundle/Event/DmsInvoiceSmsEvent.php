<?php
/**
 * Created by PhpStorm.
 * User: dhaka
 * Date: 8/19/14
 * Time: 5:24 PM
 */

namespace Setting\Bundle\ToolBundle\Event;

use Appstore\Bundle\DmsBundle\Entity\DmsInvoice;
use Symfony\Component\EventDispatcher\Event;


class DmsInvoiceSmsEvent extends Event
{

    /** @var DmsInvoice  */
    protected $dmsInvoice;


    public function __construct(DmsInvoice $dmsInvoice)
    {
        $this->dmsInvoice = $dmsInvoice;
    }

    /**
     * @return DmsInvoice
     */
    public function getDmsInvoice()
    {
        return $this->dmsInvoice;
    }


}