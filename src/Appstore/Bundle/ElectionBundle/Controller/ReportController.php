<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{


	public function indexAction()
	{

		$em = $this->getDoctrine()->getManager();

		/* @var $config ElectionConfig */

		$config     = $this->getUser()->getGlobalOption()->getElectionConfig();
		$setup      = $config->getSetup();
		$members    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->getGenderBaseMember($config);
		$voters    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->getGenderBaseVoter($config);
		$unionMembers    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->getUnionWiseMember($config);
		$unionVoters    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->getUnionWiseVoter($config);
		$wardMembers    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->getWardWiseMember($config);
		$committees    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommittee')->getCommittees($setup);
		$typeBaseCommittees    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommittee')->getLocationGroupBaseCommittee($setup);
		$locationBaseCommittees    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommittee')->getLocationBaseCommittee($setup);
		$eventTypes    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->getTypeBaseEvent($config);
		$locationBaseEvent    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->getLocationBaseEvent($config);
		$campaignsType    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->getAnalysisType($config);
		$priorities    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->getPriorityBaseEvent($config);
		$unionBaseCenters    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->getUnionBaseVoteCenter($config);
		$events    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->getEvents($config);
		$campaigns    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->getCampaigns($config);

		return $this->render('ElectionBundle:Default:index.html.twig', array(

			'members'                   => $members,
			'voters'                    => $voters,
			'unionVoters'               => $unionVoters,
			'unionMembers'              => $unionMembers,
			'typeBaseCommittees'        => $typeBaseCommittees,
			'wardMembers'               => $wardMembers,
			'committees'                => $committees,
			'locationBaseCommittees'    => $locationBaseCommittees,
			'eventTypes'                => $eventTypes,
			'locationBaseEvent'         => $locationBaseEvent,
			'campaigns'                 => $campaigns,
			'campaignsType'             => $campaignsType,
			'priorities'                => $priorities,
			'unionBaseCenters'          => $unionBaseCenters,
			'events'                    => $events,
			'setup'                     => $setup,
			'globalOption'              => $this->getUser()->getGlobalOption(),

		));
	}

	public function voteCenterAction()
	{
		$data = $_REQUEST;
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$pagination = '';
		if(!empty($data)){
			$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->findWithSearch($config,$data);
			$pagination = $entities->getQuery()->getResult();
			$twig = 'Report/Print:vote-center.html.twig';
		}else{
			$twig = 'Report:vote-center.html.twig';
		}
		return $this->render("ElectionBundle:{$twig}", array(
			'config' => $config,
			'entities' => $pagination,
			'searchForm' => $data,
		));
	}

	public function voteCenterUnionAction()
	{

		/* @var $config ElectionConfig */

		$data = $_REQUEST;
		$config     = $this->getUser()->getGlobalOption()->getElectionConfig();
		$unionVoters = '';
		if(!empty($data)){
			$unionVoters    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->getUnionWiseVoter($config,$data);
			$twig = 'Report/Print:vote-center-union.html.twig';
		}else{
			$twig = 'Report:vote-center-union.html.twig';
		}
		return $this->render("ElectionBundle:{$twig}", array(

			'config'                    => $config,
			'entities'                  => $unionVoters,
			'searchForm'                => $data,
			'globalOption'              => $this->getUser()->getGlobalOption(),

		));
	}

	public function voteCenterDetailsAction()
	{
		$data = $_REQUEST;
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = '';
		if(!empty($data)){
			$entity = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->findVoteCenter($config,$data);
			$twig = 'Report/Print:vote-center-details.html.twig';
		}else{
			$twig = 'Report:vote-center-details.html.twig';
		}
		return $this->render("ElectionBundle:{$twig}", array(
			'entity' => $entity,
			'config' => $config,
			'searchForm' => $data,
		));

	}

	public function memberListAction()
	{

		ini_set( 'max_execution_time', 0 );
		ignore_user_abort( true );

		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$type = 'member';
		$pagination = '';

		if(!empty($data)){
			$entities = $em->getRepository('ElectionBundle:ElectionMember')->findWithSearch($config,$data,$type);
			$pagination = $entities->getQuery()->getResult();
			$twig = 'Report/Print:member-list.html.twig';
		}else{
			$twig = 'Report:member-list.html.twig';
		}
		return $this->render("ElectionBundle:{$twig}", array(
			'config' => $config,
			'entities' => $pagination,

		));
	}


	public function locationGroupMemberAction()
	{
		/* @var $config ElectionConfig */

		$data = $_REQUEST;
		$config     = $this->getUser()->getGlobalOption()->getElectionConfig();
		$unionVoters = '';
		$groups = '';

		if(!empty($data)){
			$unionVoters    = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->getUnionWiseVoter($config,$data);
			$twig = 'Report/Print:member-group.html.twig';
		}else{
			$groups = $this->getDoctrine()->getRepository( 'ElectionBundle:ElectionParticular' )->findBy(array( 'particularType' =>11));
			$twig = 'Report:member-group.html.twig';
		}
		return $this->render("ElectionBundle:{$twig}", array(

			'config'                    => $config,
			'groups'                  => $groups,
			'entities'                  => $unionVoters,
			'searchForm'                => $data,
			'globalOption'              => $this->getUser()->getGlobalOption(),

		));
	}

	public function locationBaseCommitteeAction()
	{

	}

	public function locationTypeBaseCommitteeAction()
	{

	}

	public function committeeListAction()
	{

	}

	public function committeeDetailsAction()
	{

	}

	public function electionResultAction()
	{

	}

	public function voteCenterListAction()
	{

	}


	public function campaignListAction()
	{

	}

	public function campaignDetailsAction()
	{

	}

	public function analysisListAction()
	{

	}

	public function analysisDetailsAction()
	{

	}



}
