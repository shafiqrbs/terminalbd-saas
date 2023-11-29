<?php
namespace  Appstore\Bundle\ElectionBundle\EventListener;
use Appstore\Bundle\ElectionBundle\Event\ElectionSmsEvent;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Setting\Bundle\ToolBundle\Entity\SmsSender;
use Setting\Bundle\ToolBundle\Service\SmsGateWay;


class ElectionSmsListener extends BaseSmsAwareListener
{


	public function sendSms(ElectionSmsEvent $event)
	{

		/**
		 * @var ElectionSmsEvent $event
		 */

		$post = $event->getElectionMember();
		$msg = $event->getElectionMemberMsg();

		$mobile = "88".$post->getMobile();

		//  $mobile = "8801828148148";
		$status = $this->gateway->send($msg, $mobile);
		$this->insertSms($status);



	}

	public function insertSms($status)
	{
		$entity = new SmsSender();
	}
}