<?php

namespace Appstore\Bundle\ElectionBundle\Repository;
use Appstore\Bundle\DomainUserBundle\Entity\Customer;
use Appstore\Bundle\ElectionBundle\Entity\ElectionCampaignAnalysis;
use Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee;
use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionEvent;
use Appstore\Bundle\ElectionBundle\Entity\ElectionLocation;
use Appstore\Bundle\ElectionBundle\Entity\ElectionMember;
use Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter;
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
class ElectionMemberRepository extends EntityRepository
{
    public function checkDuplicateCustomer(ElectionConfig $config, $mobile)
    {
        $em = $this->_em;
        $entity = $em->getRepository('ElectionBundle:ElectionMember')->findOneBy(array('config' => $config,'mobile' => $mobile));
        if($entity) {
            return false;
        }else{
            return true;
        }

    }

	public function getBarcodeForPrint($config,$data)
	{
		$qb = $this->createQueryBuilder('item');
		$qb->select('item');
		$qb->where($qb->expr()->in("item.id", $data ));
		$qb->andWhere("item.electionConfig = :config");
		$qb->setParameter('config', $config->getId());
		$qb->orderBy('item.memberId','ASC');
		return $qb->getQuery()->getResult();

	}

    public function getGenderBaseMember(ElectionConfig $config,$type = 'member'){

	    $qb = $this->createQueryBuilder('e');
	    $qb->select('e.gender as gender, COUNT(e.id) as countId');
	    $qb->where('e.electionConfig='.$config->getId());
	    $qb->andWhere("e.memberType = :type");
	    $qb->setParameter('type', $type);
	    $qb->andWhere("e.status = :status");
	    $qb->setParameter('status', 1);
	    $qb->groupBy('e.gender');
	    $qb->orderBy('e.gender','ASC');
	    $results = $qb->getQuery()->getArrayResult();
	    $male = !empty($results[1]['countId'])?$results[1]['countId']:0;
	    $other = !empty($results[2]['countId'])?$results[2]['countId']:0;
	    $female = !empty($results[0]['countId'])?$results[0]['countId']:0;
	    $totalMembers = ($male + $female + $other);
	    $data = array('results' => $results,'totalMember' => $totalMembers);
	    return $data;
    }

    public function getGenderBaseVoter(ElectionConfig $config){

	    $qb = $this->createQueryBuilder('e');
	    $qb->select('COUNT(e.id) as totalVoters');
	    $qb->where('e.electionConfig='.$config->getId());
	    $qb->andWhere("e.memberType = :type");
	    $qb->setParameter('type', 'voter');
	    $qb->andWhere("e.status = :status");
	    $qb->setParameter('status', 1);
	    $result = $qb->getQuery()->getOneOrNullResult();
	    return $result['totalVoters'];
    }

    public function getUnionWiseMember(ElectionConfig $config){

	    $qb = $this->createQueryBuilder('e');
	    $qb->select('e.memberUnion as unionName,COUNT(e.id) as total');
	    $qb->where('e.electionConfig='.$config->getId());
	    $qb->andWhere("e.memberType = :type");
	    $qb->setParameter('type', 'member');
	    $qb->andWhere("e.status = :status");
	    $qb->setParameter('status', 1);
	    $qb->groupBy('e.memberUnion');
	    $results = $qb->getQuery()->getArrayResult();
	    return $results;
    }

	public function getWardWiseMember(ElectionConfig $config){

	    $qb = $this->createQueryBuilder('e');
	    $qb->select('e.ward as wardName,e.memberUnion as unionName,COUNT(e.id) as countId');
	    $qb->where('e.electionConfig='.$config->getId());
		$qb->andWhere("e.memberType = :type");
		$qb->setParameter('type', 'member');
	    $qb->andWhere("e.status = :status");
	    $qb->setParameter('status', 1);
		$qb->groupBy('e.ward, e.memberUnion');
	    $results = $qb->getQuery()->getArrayResult();
	    return $results;

    }


    public function getImportCount(ElectionConfig $config,$process){


	    $qb = $this->createQueryBuilder('e');
	    $qb->select('COUNT(e.id) as countId');
	    $qb->where('e.electionConfig='.$config->getId());
	    $qb->andWhere("e.process = :process");
	    $qb->setParameter('process', $process);
	    $results = $qb->getQuery()->getOneOrNullResult();
	    return $results['countId'];
	}

    public function getLocationBaseMembers(ElectionCommittee $committee)
    {
	    /* @var $location ElectionLocation */
    	$location = $committee->getLocation();

	    $config = $committee->getElectionConfig()->getId();
	    $qb = $this->createQueryBuilder('e');
	    $orX = $qb->expr()->orX();
	    $orX->add("node.path like '%" .$location->getId(). "/%'");
	    $orX->add("center.path like '%" .$location->getId(). "/%'");
	    $qb->leftJoin('e.location','node');
	    $qb->leftJoin('e.voteCenter','center');
		$qb->orderBy('node.level, node.name', 'ASC');
		$qb->where('e.electionConfig='.$config);
		$qb->andWhere($orX);
	    $results = $qb->getQuery()->getResult();
	    $choices = [];
	    foreach ($results as $product) {
		    $choices[$product->getId()] =  $product->getName().' [ '.$product->getLocation()->villageName().' ]';
	    }
	    return $choices;
    }

	public function getVotecenterBaseMembers(ElectionVoteCenter $committee)
	{
		/* @var $location ElectionLocation */
		$location = $committee->getLocation();

		$config = $committee->getElectionConfig()->getId();
		$qb = $this->createQueryBuilder('e');
		$orX = $qb->expr()->orX();
		$orX->add("center.path like '%" .$location->getId(). "/%'");
		$qb->leftJoin('e.location','node');
		$qb->leftJoin('e.voteCenter','center');
		$qb->orderBy('node.level, node.name', 'ASC');
		$qb->where('e.electionConfig='.$config);
		$qb->andWhere($orX);
		$results = $qb->getQuery()->getResult();
		$choices = [];
		foreach ($results as $product) {
			$choices[$product->getId()] =  $product->getName().' [ '.$product->getLocation()->villageName().' ]';
		}
		return $choices;
	}


	public function findWithSearch( $config , $data , $type = '')
    {
	    $sort = isset($data['sort'])? $data['sort'] :'e.name';
	    $direction = isset($data['direction'])? $data['direction'] :'ASC';
        $qb = $this->createQueryBuilder('e');
        $qb->where("e.electionConfig = :config");
        $qb->setParameter('config', $config);
        if(!empty($type)){
	        $qb->andWhere("e.memberType = :type");
	        $qb->setParameter('type', $type);
        }
	    $qb->leftJoin('e.location','l');
	    $qb->leftJoin('l.parent','parentLocation');
	    $this->handleSearchBetween($qb,$data);
	    $qb->orderBy("{$sort}",$direction);
        $qb->getQuery();
        return  $qb;

    }

    protected function handleSearchBetween($qb,$data)
    {
        if(!empty($data))
        {

            $mobile =    isset($data['member'])? $data['member'] :'';
            $keyword =    isset($data['keyword'])? $data['keyword'] :'';
            $thana =    isset($data['thana'])? $data['thana'] :'';
            $union =    isset($data['union'])? $data['union'] :'';
            $ward =    isset($data['ward'])? $data['ward'] :'';
            $village =    isset($data['village'])? $data['village'] :'';
            $voteCenter =    isset($data['voteCenter'])? $data['voteCenter'] :'';
            $district =    isset($data['district'])? $data['district'] :'';

            if (!empty($mobile)) {
                $qb->andWhere("e.mobile = :mobile");
                $qb->setParameter('mobile', $mobile);
            }

	        if (!empty($keyword)) {
		        $qb->andWhere("e.name LIKE :name");
		        $qb->setParameter('name','%'. $keyword.'%');
	            $qb->orWhere("e.mobile LIKE :mobile");
		        $qb->setParameter('mobile','%'. $keyword.'%');
	        }

	        if (!empty($district)) {
                $qb->andWhere("e.district LIKE :district");
                $qb->setParameter('district','%'. $district.'%');
            }

	        if (!empty($thana)) {
		        $val = explode(',',$thana);
		        $name = $val[0];
		        $qb->andWhere($qb->expr()->like("e.thana", "'%$name%'"  ));
		    }

	        if (!empty($union)) {
		        $val = explode(',',$union);
		        $name = $val[0];
		        $parent = $val[1];
		        $qb->andWhere($qb->expr()->like("e.memberUnion", "'%$name%'"  ));
		        $qb->andWhere($qb->expr()->like("e.thana", "'%$parent%'"  ));

	        }

            if (!empty($ward)) {
		        $val = explode(',',$ward);
	            $name = $val[0];
	            $parent = $val[1];
	            $qb->andWhere($qb->expr()->like("e.ward", "'%$name%'"  ));
	            $qb->andWhere($qb->expr()->like("e.memberUnion", "'%$parent%'"  ));

            }

            if (!empty($village)) {
		        $val = explode(',',$village);
	            $name = $val[0];
	            $parent = $val[1];
	            $qb->andWhere($qb->expr()->like("e.village", "'%$name%'"  ));
	            $qb->andWhere($qb->expr()->like("e.ward", "'%$parent%'"  ));

            }

	        if (!empty($voteCenter)) {
		        $val = explode(',',$voteCenter);
		        $name = $val[0];
		        $parent = $val[1];
		        $qb->andWhere($qb->expr()->like("e.voteCenterName", "'%$name%'"  ));
		        $qb->andWhere($qb->expr()->like("e.memberUnion", "'%$parent%'"  ));
			//	$qb->leftJoin('center.parent','centerParent');
		    //    $qb->andWhere($qb->expr()->like("centerParent.name", "'%$parent%'"  ));

	        }

        }

    }


    public function insertSMSCustomer($data)
    {
        $em = $this->_em;
        $entity = $em->getRepository('DomainUserBundle:Customer')->findOneBy(array('config'=>$data['config'],'mobile' => $data['mobile']));
        if($entity){
            return $entity;
        }else{
            $entity = new Customer();
            $config = $this->_em->getRepository('SettingToolBundle:GlobalOption')->find($data['config']);
            $entity->setMobile($data['mobile']);
            $entity->setName($data['name']);
            $entity->setGlobalOption($config);
            $entity->setCustomerType('sms');
            $em->persist($entity);
            $em->flush();
            return $entity;
        }

    }

    public function searchAutoComplete(ElectionConfig $config, $q , $type='member')
    {
        $query = $this->createQueryBuilder('e');
        $query->select('e.mobile as id');
        $query->addSelect('CONCAT(e.mobile, \' - \', e.name) AS text');
        $query->where($query->expr()->like("e.mobile", "'$q%'"  ));
        $query->orWhere($query->expr()->like("e.name", "'%$q%'"  ));
        $query->andWhere("e.electionConfig = :config");
        $query->setParameter('config', $config->getId());
        $query->andWhere("e.memberType = :type");
        $query->setParameter('type', $type);
        $query->orderBy('e.name', 'ASC');
        $query->groupBy('e.mobile');
        $query->setMaxResults( '20' );
        return $query->getQuery()->getResult();

    }

     public function searchMobileAutoComplete(GlobalOption $config, $q, $type = 'member')
    {
        $query = $this->createQueryBuilder('e');

        $query->select('e.mobile as id');
        $query->addSelect('e.id as e');
        $query->addSelect('CONCAT(e.mobile, \'-\', e.name) AS text');
        $query->where($query->expr()->like("e.mobile", "'$q%'"  ));
        $query->andWhere("e.config = :config");
        $query->setParameter('config', $config->getId());
	    $query->andWhere("e.memberType = :type");
	    $query->setParameter('type', $type);
        $query->orderBy('e.mobile', 'ASC');
        $query->groupBy('e.mobile');
        $query->setMaxResults( '10' );
        return $query->getQuery()->getResult();

    }

    public function searchCustomerAutoComplete(GlobalOption $config, $q, $type = 'member')
    {
        $query = $this->createQueryBuilder('e');
        $query->select('e.name as id');
        $query->addSelect('e.id as name');
        $query->addSelect('e.name as text');
        $query->where($query->expr()->like("e.mobile", "'$q%'"  ));
        $query->andWhere("e.config = :config");
        $query->setParameter('config', $config->getId());
	    $query->andWhere("e.memberType = :type");
	    $query->setParameter('type', $type);
        $query->orderBy('e.name', 'ASC');
        $query->groupBy('e.mobile');
        $query->setMaxResults( '10' );
        return $query->getQuery()->getResult();

    }

    public function searchAutoCompleteName(GlobalOption $config, $q)
    {
        $query = $this->createQueryBuilder('e');
        $query->select('e.name as id');
        $query->addSelect('e.id as e');
        $query->addSelect('e.name as text');
        $query->where($query->expr()->like("e.name", "'$q%'"  ));
        $query->andWhere("e.config = :config");
        $query->setParameter('config', $config->getId());
        $query->groupBy('e.name');
        $query->orderBy('e.name', 'ASC');
        $query->setMaxResults( '10' );
        return $query->getQuery()->getResult();

    }

    public function searchAutoCompleteCode(GlobalOption $config, $q)
    {
        $query = $this->createQueryBuilder('e');

        $query->select('e.mobile as id');
        $query->addSelect('e.id as e');
        $query->addSelect('e.eId as text');
        //$query->addSelect('CONCAT(e.eId, " - ", e.name) AS text');
        $query->where($query->expr()->like("e.eId", "'$q%'"  ));
        $query->andWhere("e.config = :config");
        $query->setParameter('config', $config->getId());
        $query->orderBy('e.eId', 'ASC');
        $query->setMaxResults( '10' );
        return $query->getQuery()->getResult();
    }

    public function memberData(ElectionMember $entity )
    {

	    $voter = '';
	    if($entity->getVoteCenter()){
		    $voter = $entity->getVoteCenter()->getName();
	    }
    	$data ="";
    	$data .="<div class='portlet-body flip-scroll'><table class='table table-bordered table-striped table-condensed flip-content '>";
	    $data .=" <thead class='flip-content'><tr class='head-blue'><th>ID</th><th>Name</th><th>Mobile</th><th>Village</th><th>Vote Center</th><th>Ward</th><th>Union/Purashava</th><th>Thana/Upazila</th><th></th></tr></thead>";
        $data .= "<tbody>";
        $data .= "<tr>";
	    $data .= "<td>{$entity->getMemberId()}</td>";
	    $data .= "<td>{$entity->getName()}</td>";
	    $data .= "<td><a href='tel:+88 {$entity->getMobile()}'>{$entity->getMobile()}</a></td>";
	    $data .= "<td>{$entity->getLocation()->getName()}</td>";
	    $data .= "<td>{$voter}</td>";
	    $data .= "<td>{$entity->getLocation()->wardName()}</td>";
	    $data .= "<td>{$entity->getLocation()->unionName()}</td>";
	    $data .= "<td>{$entity->getLocation()->thanaName()}</td>";
        $data .= "<td>";
		$data .= "<a data-title='{$entity->getName()}' class='btn mini view blue' href='javascript:' data-url='/election/member/{$entity->getId()}/show'>&nbsp;<i class='icon-eye-open'></i> View</a>";
		$data .= "<a data-title='{$entity->getName()}' class='btn mini sms purple' href='javascript:' data-url='/election/member/{$entity->getId()}/sms'>&nbsp;<i class='icon-file-text'></i> SMS</a>";
	    $data .= "</td>";
	    $data .= "</tr>";
	    $data .= "</tbody>";
	    $data .= "</table></div>";
	    return $data;

    }

	public function voterData(ElectionMember $entity )
	{
		$voter = '';
		if($entity->getVoteCenter()){
			$voter = $entity->getVoteCenter()->getName();
		}
		$data ="";
		if(!empty($entity->getLocation())){
			$data .="<div class='portlet-body flip-scroll'><table class='table table-bordered table-striped table-condensed flip-content '>";
			$data .=" <thead class='flip-content'><tr class='head-grey'><th>ID</th><th>Name</th><th>Mobile</th><th>Village</th><th>Vote Center</th><th>Ward</th><th>Union/Purashava</th><th>Thana/Upazila</th></tr></thead>";
			$data .= "<tbody>";
			$data .= "<tr>";
			$data .= "<td>{$entity->getMemberId()}</td>";
			$data .= "<td>{$entity->getName()}</td>";
			$data .= "<td><a href='tel:+88 {$entity->getMobile()}'>{$entity->getMobile()}</a></td>";
			$data .= "<td>{$entity->getLocation()->getName()}</td>";
			$data .= "<td>{$voter}</td>";
			$data .= "<td>{$entity->getLocation()->wardName()}</td>";
			$data .= "<td>{$entity->getLocation()->unionName()}</td>";
			$data .= "<td>{$entity->getLocation()->thanaName()}</td>";
			$data .= "</tr>";
			$data .= "</tbody>";
			$data .= "</table></div>";

		}else{
			$data .="<p class='text-center'>There is no available information. </p>";
		}

		return $data;

	}

	public function committeeData(ElectionCommittee $entity )
	{
		$duration = empty($entity->getStartDate()) ? '': "{$entity->getStartDate()->format('d-m-Y')} To {$entity->getEndDate()->format('d-m-Y')}";
		$data ="";
		$data .="<div class='portlet-body flip-scroll'><table class='table table-bordered table-striped table-condensed flip-content '>";
		$data .=" <thead class='flip-content'><tr class='head-purple'><th>Created</th><th>Name</th><th>Committee For</th><th>Location</th><th>Location Type</th><th>Date Duration</th><th>Created By</th><th>Approved By</th><th></th></tr></thead>";
		$data .= "<tbody>";
		$data .= "<tr>";
		$data .= "<td>{$entity->getCreated()->format('d-m-Y')}</td>";
		$data .= "<td>{$entity->getName()}</td>";
		$data .= "<td>{$entity->getElectionSetup()->getElectionName()}</td>";
		$data .= "<td>{$entity->getLocation()->getName()}</td>";
		$data .= "<td>{$entity->getLocation()->getLocationType()->getName()}</td>";
		$data .= "<td>{$duration}</td>";
		$data .= "<td>{$entity->getCreatedBy()}</td>";
		$data .= "<td>{$entity->getApprovedBy()}</td>";
		$data .= "<td>";
		$data .= "<a data-title='{$entity->getLocation()->getName()}' class='btn mini view blue' href='javascript:' data-url='/election/committee/{$entity->getId()}/show'>&nbsp;<i class='icon-eye-open'></i> View</a>";
		$data .= "<a data-title='{$entity->getLocation()->getName()}' class='btn mini sms purple' href='javascript:' data-url='/election/sms/{$entity->getId()}/committee'>&nbsp;<i class='icon-file-text'></i> SMS</a>";
		$data .= "</td>";
		$data .= "</tr>";
		$data .= "</tbody>";
		$data .= "</table></div>";
		return $data;

	}

	public function voteCenterData(ElectionVoteCenter $entity )
	{
		$voter = '';
		$data ="";
		$data .="<div class='portlet-body flip-scroll'><table class='table table-bordered table-striped table-condensed flip-content '>";
		$data .=" <thead class='flip-content'><tr class='head-green'><th>Created</th><th>Vote Center</th><th>Election</th><th>Total Voter</th><th>Male</th><th>Female</th><th>Representative</th><th>Mobile</th><th>Presiding</th><th>Presiding Mobile</th><th></th></tr></thead>";
		$data .= "<tbody>";
		$data .= "<tr>";
		$data .= "<td>{$entity->getCreated()->format('d-m-Y')}</td>";
		$data .= "<td>{$entity->getLocation()->getName()}</td>";
		$data .= "<td>{$entity->getElectionSetup()->getElectionName()}</td>";
		$data .= "<td>{$entity->getTotalVoter()}</td>";
		$data .= "<td>{$entity->getMaleVoter()}</td>";
		$data .= "<td>{$entity->getFemaleVoter()}</td>";
		$data .= "<td>{$entity->getRepresentative()->getName()}</td>";
		$data .= "<td><a href='tel:+88 {$entity->getRepresentativeMobile()}'>{$entity->getRepresentativeMobile()}</a></td>";
		$data .= "<td>{$entity->getPresiding()}</td>";
		$data .= "<td><a href='tel:+88 {$entity->getPresidingMobile()}'>{$entity->getPresidingMobile()}</a></td>";
		$data .= "<td>";
		$data .= "<a data-title='{$entity->getLocation()->getName()}' class='btn mini view blue' href='javascript:' data-url='/election/vote-center/{$entity->getId()}/show'>&nbsp;<i class='icon-eye-open'></i> View</a>";
		$data .= "<a data-title='{$entity->getLocation()->getName()}' class='btn mini sms purple' href='javascript:' data-url='/election/vote-center/{$entity->getId()}/sms'>&nbsp;<i class='icon-file-text'></i> SMS</a>";
		$data .= "</td>";
		$data .= "</tr>";
		$data .= "</tbody>";
		$data .= "</table></div>";
		return $data;

	}

	public function campaignData(ElectionEvent $entity )
	{
		$data ="";
		$data .="<div class='portlet-body flip-scroll'><table class='table table-bordered table-striped table-condensed flip-content '>";
		$data .=" <thead class='flip-content'><tr class='head-grey'><th>Created</th><th>Name</th><th>Campaign Date</th><th>Campaign type</th><th>Election</th><th>Organiser</th><th>Mobile</th><th>Location</th><th></th></tr></thead>";
		$data .= "<tbody>";
		$data .= "<tr>";
		$data .= "<td>{$entity->getCreated()->format('d-m-Y')}</td>";
		$data .= "<td>{$entity->getName()}</td>";
		$data .= "<td>{$entity->getEventDate()->format('d-m-Y')} {$entity->getEventTime()}</td>";
		$data .= "<td>{$entity->getEventType()->getName()}</td>";
		$data .= "<td>{$entity->getElectionSetup()->getElectionName()}</td>";
		$data .= "<td>{$entity->getOrganiser()->getName()}</td>";
		$data .= "<td><a href='tel:+88 {$entity->getMobile()}'>{$entity->getMobile()}</a></td>";
		$data .= "<td>{$entity->getLocation()->getName()}</td>";
		$data .= "<td>";
		$data .= "<a data-title='{$entity->getName()}' class='btn mini view blue' href='javascript:' data-url='/election/campaign/{$entity->getId()}/show'>&nbsp;<i class='icon-eye-open'></i> View</a>";
		$data .= "<a data-title='{$entity->getName()}' class='btn mini sms purple' href='javascript:' data-url='/election/event/{$entity->getId()}/sms'>&nbsp;<i class='icon-file-text'></i> SMS</a>";
		$data .= "</td>";
		$data .= "</tr>";
		$data .= "</tr>";
		$data .= "</tbody>";
		$data .= "</table></div>";
		return $data;

	}

	public function analysisData(ElectionCampaignAnalysis $entity )
	{
		$data ="";
		$data .="<div class='portlet-body flip-scroll'><table class='table table-bordered table-striped table-condensed flip-content '>";
		$data .=" <thead class='flip-content'><tr class='head-yellow'><th>Created</th><th>Name</th><th>Election</th><th>Analysis type</th><th>Priority</th><th>Location</th><th></th></tr></thead>";
		$data .= "<tbody>";
		$data .= "<tr>";
		$data .= "<td>{$entity->getCreated()->format('d-m-Y')}</td>";
		$data .= "<td>{$entity->getName()}</td>";
		$data .= "<td>{$entity->getElectionSetup()->getElectionName()}</td>";
		$data .= "<td>{$entity->getAnalysisType()->getName()}</td>";
		$data .= "<td>{$entity->getPriority()->getName()}</td>";
		$data .= "<td>{$entity->getLocation()->getName()}</td>";
		$data .= "<td>";
		$data .= "<a data-title='{$entity->getName()}' class='btn mini view blue' href='javascript:' data-url='/election/campaign-analysis/{$entity->getId()}/show'>&nbsp;<i class='icon-eye-open'></i> View</a>";
		$data .= "</td>";
		$data .= "</tr>";
		$data .= "</tr>";
		$data .= "</tbody>";
		$data .= "</table></div>";
		return $data;

	}



}
