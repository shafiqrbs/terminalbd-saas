<?php

namespace Appstore\Bundle\ElectionBundle\Repository;
use Appstore\Bundle\DomainUserBundle\Entity\NotificationConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee;
use Appstore\Bundle\ElectionBundle\Entity\ElectionCommitteeMember;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * ElectionCommitteeMemberRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ElectionCommitteeMemberRepository extends EntityRepository
{
	public function getMemberLists(ElectionCommittee $committee)
	{
		$entities = $committee->getMembers();
		$data = '';
		$i = 1;

		/* @var $entity ElectionCommitteeMember */

		foreach ($entities as $entity) {
			$data .= "<tr id='remove-{$entity->getId()}'>";
			$data .= "<td>{$i}.</td>";
			$data .= "<td>{$entity->getMember()->getMemberId()}</td>";
			$data .= "<td>{$entity->getMember()->getName()}</td>";
			$data .= "<td>{$entity->getDesignation()->getName()}</td>";
			$data .= "<td><a href='tel:+88 {$entity->getMember()->getMobile()}'>{$entity->getMember()->getMobile()}</a></td>";
			$data .= "<td>{$entity->getMember()->getLocation()->getName()}</td>";
			$data .= "<td>{$entity->getMember()->getVoteCenter()->getName()}</td>";
			$data .= "<td>{$entity->getMember()->getLocation()->wardName()}</td>";
			$data .= "<td>{$entity->getMember()->getLocation()->unionName()}</td>";
			$data .= "<td>{$entity->getMember()->getLocation()->thanaName()}</td>";
			$data .= "<td><a data-id='{$entity->getId()}' data-url='/election/committee/{$entity->getId()}/member-delete' href='javascript:' class='btn red mini delete' ><i class='icon-trash'></i></a></td>";
			$data .= '</tr>';
			$i++;
		}
		return $data;
	}
}