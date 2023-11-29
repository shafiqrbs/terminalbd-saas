<?php
/**
 * Created by PhpStorm.
 * User: dhaka
 * Date: 8/19/14
 * Time: 5:24 PM
 */

namespace Appstore\Bundle\ElectionBundle\Event;

use Appstore\Bundle\ElectionBundle\Entity\ElectionMember;
use Symfony\Component\EventDispatcher\Event;


class ElectionSmsEvent extends Event
{


    protected $member;

    public $msg;


    public function __construct(ElectionMember $member , $msg)
    {
        $this->member = $member;
        $this->msg = $msg;
    }

    /**
     * @return ElectionMember
     */
    public function getElectionMember()
    {
        return $this->member;
    }

    public function getElectionMemberMsg()
    {
        return $this->msg;
    }


}