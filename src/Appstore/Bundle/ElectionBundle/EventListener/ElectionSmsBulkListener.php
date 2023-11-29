<?php
namespace  Appstore\Bundle\ElectionBundle\EventListener;
use Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee;
use Appstore\Bundle\ElectionBundle\Entity\ElectionCommitteeMember;
use Appstore\Bundle\ElectionBundle\Entity\ElectionEventMember;
use Appstore\Bundle\ElectionBundle\Entity\ElectionMember;
use Appstore\Bundle\ElectionBundle\Entity\ElectionSms;
use Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter;
use Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenterMember;
use Appstore\Bundle\ElectionBundle\Event\ElectionSmsBulkEvent;
use Setting\Bundle\ToolBundle\Entity\SmsSender;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Setting\Bundle\ToolBundle\Service\SmsGateWay;



class ElectionSmsBulkListener extends BaseSmsAwareListener
{


	/**
	 * @var EntityManager
	 */
	protected $em;
	/**
	 * @var \Doctrine\Bundle\DoctrineBundle\Registry
	 */
	private $doctrine;

	protected $gateway;

	public function __construct(Registry $doctrine, SmsGateWay $gateway)
	{
		$this->doctrine = $doctrine;
		$this->gateway = $gateway;
		$this->em = $doctrine->getManager();
	}


	public function sendSms(ElectionSmsBulkEvent $event)
	{

		/**
		 * @var ElectionSmsBulkEvent $event
		 */

		$entity = $event->getSmsBulk();

		if($entity->getProcess() == "Member" and !empty($entity->getLocationMember())){
			$this->memberSms($entity);
		}elseif ($entity->getProcess() == "Voter" and !empty($entity->getLocationVoter())){
			$this->voterSms($entity);
		}elseif ($entity->getProcess() == "Vote Center" and !empty($entity->getVoteCenter())){
			$this->voteCenterSms($entity);
		}elseif ($entity->getProcess() == "Committee" and !empty($entity->getCommittee())){
			$this->committeeSms($entity);
		}elseif ($entity->getProcess() == "Event" and !empty($entity->getEvent())){
			$this->campaignSms($entity);
		}
	}

	public function insertSmsStatus($event,$total,$count)
	{
		$this->em->getRepository('ElectionBundle:ElectionSms')->updateSmsStatus($event,$total,$count);
	}

	private function memberSms(ElectionSms $event)
	{

		$village    = $event->getLocationMember()->getId();
		$config     = $event->getElectionConfig();
		$members = $this->em->getRepository('ElectionBundle:ElectionMember')->findBy(array('electionConfig' => $config ,'location' => $village ,'memberType' => 'member','isMember'=>1));

		$msg = $event->getContent();

		/* @var ElectionMember $member */

		$total = 0;
		$count = 0;
		foreach ($members as $member){

			if(!empty($member->getMobile())){
				$mobile = "88".$member->getMobile();
				$this->gateway->send($msg, $mobile);
				$count ++;
			}
			$total++;
		}
		$this->insertSmsStatus($event,$total,$count);
	}

	private function voterSms(ElectionSms $event)
	{
		$village    = $event->getLocationVoter()->getId();
		$config     = $event->getElectionConfig();
		$members = $this->em->getRepository('ElectionBundle:ElectionMember')->findBy(array('electionConfig' => $config ,'location' => $village ,'memberType' => 'voter'));

		$msg = $event->getContent();

		/* @var ElectionMember $member */

		$total = 0;
		$count = 0;
		foreach ($members as $member){

			if(!empty($member->getMobile())){
				$mobile = "88".$member->getMobile();
				$this->gateway->send($msg, $mobile);
				$count ++;
			}
			$total++;
		}
		$this->insertSmsStatus($event,$total,$count);
	}

	private function voteCenterSms(ElectionSms $event)
	{
		$village    = $event->getVoteCenter()->getId();
		$config     = $event->getElectionConfig();

		/* @var ElectionVoteCenter $voteCenter */

		$voteCenter    = $this->em->getRepository('ElectionBundle:ElectionVoteCenter')->findBy(array('electionConfig' => $config ,'location' => $village));

		$msg = $event->getContent();

		$members = $voteCenter->getCenterMembers();

		/* @var ElectionVoteCenterMember $member */

		if(!empty($members)) {

			$total = 0;
			$count = 0;
			foreach ( $members as $member ) {
				if ( ! empty( $member->getAgentMobile() ) and $member->getPersonType() == 'agent' ) {
					$mobile = "88" . $member->getAgentMobile();
					$this->gateway->send( $msg, $mobile );
					$count ++;
				}
				$total ++;
			}
			$this->insertSmsStatus( $event, $total, $count );
		}
	}

	private function committeeSms(ElectionSms $event)
	{
		$committee    = $event->getCommittee();

		$msg = $event->getContent();

		/* @var ElectionCommitteeMember $member */

		$members = $committee->getMembers();

		$total = 0;
		$count = 0;
		if(!empty($members)){
			foreach ($members as $member){
				if(!empty($member->getMember()) and !empty($member->getMember()->getMobile()) ){
					$mobile = "88".$member->getMember()->getMobile();
					$this->gateway->send($msg, $mobile);
					$count ++;
				}
				$total++;
			}
			$this->insertSmsStatus( $event, $total, $count );
		}
		exit;

	}

	private function campaignSms(ElectionSms $event)
	{
		$committee    = $event->getEvent();

		$msg = $event->getContent();

		/* @var ElectionEventMember $member */

		$members = $committee->getEventMembers();

		$total = 0;
		$count = 0;
		if(!empty($members)){
			foreach ($members as $member){

				if(!empty($member->getMobile())){
					$mobile = "88".$member->getMobile();
					$this->gateway->send($msg, $mobile);
					$count ++;
				}
				$total++;
			}
			$this->insertSmsStatus( $event, $total, $count );
		}

	}


}