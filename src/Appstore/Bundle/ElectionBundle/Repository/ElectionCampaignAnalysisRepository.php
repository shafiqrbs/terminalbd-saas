<?php

namespace Appstore\Bundle\ElectionBundle\Repository;
use Appstore\Bundle\DomainUserBundle\Entity\Customer;
use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\HospitalBundle\Entity\Invoice;
use Appstore\Bundle\InventoryBundle\Entity\Sales;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * CustomerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ElectionCampaignAnalysisRepository extends EntityRepository
{


	public function getCampaigns(ElectionConfig $config){

		$qb = $this->createQueryBuilder('e');
		$qb->select('e');
		$qb->where('e.electionConfig='.$config->getId());
		$qb->andWhere("e.status = :status");
		$qb->setParameter('status', 1);
		$qb->orderBy("e.updated",'DESC');
		$qb->getMaxResults(0,5);
		$results = $qb->getQuery()->getResult();
		return $results;
	}

	public function getAnalysisType(ElectionConfig $config){

		$qb = $this->createQueryBuilder('e');
		$qb->join('e.analysisType','t');
		$qb->select('t.name as analysisName , COUNT(e.id) as countId');
		$qb->where('e.electionConfig='.$config->getId());
		$qb->andWhere("e.status = :status");
		$qb->setParameter('status', 1);
		$qb->groupBy('t.name');
		$results = $qb->getQuery()->getArrayResult();
		return $results;
	}

	public function getPriorityBaseEvent(ElectionConfig $config){

		$qb = $this->createQueryBuilder('e');
		$qb->join('e.priority','t');
		$qb->select('t.name as priorityName , COUNT(e.id) as countId');
		$qb->where('e.electionConfig='.$config->getId());
		$qb->andWhere("e.status = :status");
		$qb->setParameter('status', 1);
		$qb->groupBy('t.name');
		$results = $qb->getQuery()->getArrayResult();
		return $results;
	}

	public function getLocationBaseEvent(ElectionConfig $config){

		$qb = $this->createQueryBuilder('e');
		$qb->join('e.location','t');
		$qb->select('t.name as locationName , COUNT(e.id) as countId');
		$qb->where('e.electionConfig='.$config->getId());
		$qb->andWhere("e.status = :status");
		$qb->setParameter('status', 1);
		$qb->groupBy('t.name');
		$results = $qb->getQuery()->getArrayResult();
		return $results;
	}

	public function searchAutoComplete(ElectionConfig $config, $q)
	{
		$query = $this->createQueryBuilder('e');
		$query->leftJoin('e.analysisType','analysis');
		$query->leftJoin('e.priority','priority');
		$query->select('e.id as id');
		$query->addSelect('CONCAT(e.name, \' \', analysis.name, \' \', priority.name) AS text');
		$query->where($query->expr()->like("e.name", "'$q%'"  ));
		$query->orWhere($query->expr()->like("analysis.name", "'$q%'"  ));
		$query->orWhere($query->expr()->like("priority.name", "'$q%'"  ));
		$query->andWhere("e.electionSetup = :config");
		$query->setParameter('config', $config->getSetup()->getId());
		$query->orderBy('e.name', 'ASC');
		$query->setMaxResults( '10' );
		return $query->getQuery()->getResult();

	}

}
