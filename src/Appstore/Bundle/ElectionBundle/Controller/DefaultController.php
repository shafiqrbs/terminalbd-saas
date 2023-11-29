<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
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

		return $this->render('ElectionBundle:Default:comittee.html.twig', array(

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

	public function memberDataAction()
	{
		$mobile = $_REQUEST['id'];
		$entity = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->findOneBy(array('mobile' => $mobile));
		if($entity){
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->memberData($entity);
			return new Response($data);
		}else{
			return new Response('No record found, please try again');
		}
		exit;
	}
	public function voterDataAction()
	{
		$mobile = $_REQUEST['id'];
		$entity = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->findOneBy(array('mobile' => $mobile));
		if($entity){
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->voterData($entity);
			return new Response($data);
		}else{
			return new Response('No record found, please try again');
		}
		exit;
	}
	public function voteCenterDataAction()
	{
		$name = $_REQUEST['id'];
		$entity = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->findOneBy(array('id' => $name));
		if($entity){
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->voteCenterData($entity);
			return new Response($data);
		}else{
			return new Response('No record found, please try again');
		}
		exit;
	}


	public function committeeDataAction()
	{
		$name = $_REQUEST['id'];
		$entity = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommittee')->findOneBy(array('id' => $name));
		if($entity){
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->committeeData($entity);
			return new Response($data);
		}else{
			return new Response('No record found, please try again');
		}
		exit;
	}

	public function campaignDataAction()
	{
		$name = $_REQUEST['id'];
		$entity = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->findOneBy(array('id' => $name));
		if($entity){
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->campaignData($entity);
			return new Response($data);
		}else{
			return new Response('No record found, please try again');
		}
		exit;
	}
	public function analysisDataAction()
	{
		$name = $_REQUEST['id'];
		$entity = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCampaignAnalysis')->findOneBy(array('id' => $name));
		if($entity){
			$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->analysisData($entity);
			return new Response($data);
		}else{
			return new Response('No record found, please try again');
		}
		exit;
	}

}
