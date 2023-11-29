<?php

namespace Appstore\Bundle\DomainUserBundle\Repository;
use Appstore\Bundle\DomainUserBundle\Entity\CustomerSms;
use Appstore\Bundle\DomainUserBundle\Entity\NotificationConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionSms;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * ElectionVoteCenterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomerSmsRepository extends EntityRepository
{
	public function updateSmsStatus(CustomerSms $sms , $total = 0 , $success = 0){

		$sms->setTotal($total);
		$sms->setSuccess($success);
		$sms->setFailed($total - $success);
		$sms->setSmsStatus('Done');
		$this->_em->flush($sms);

	}
}