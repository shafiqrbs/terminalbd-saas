<?php
/**
 * Created by PhpStorm.
 * User: dhaka
 * Date: 8/19/14
 * Time: 5:24 PM
 */

namespace Appstore\Bundle\ElectionBundle\Event;

use Appstore\Bundle\ElectionBundle\Entity\ElectionSms;
use Symfony\Component\EventDispatcher\Event;


class ElectionSmsBulkEvent extends Event
{

    /** @var ElectionSms */

    protected $smsBulk;


    public function __construct(ElectionSms $smsBulk)
    {
        $this->smsBulk = $smsBulk;
    }

    /**
     * @return ElectionSms
     */
    public function getSmsBulk()
    {
        return $this->smsBulk;
    }
}