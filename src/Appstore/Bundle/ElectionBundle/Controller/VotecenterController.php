<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenter;
use Appstore\Bundle\ElectionBundle\Entity\ElectionVoteCenterMember;
use Appstore\Bundle\ElectionBundle\Form\VotecenterMemberType;
use Appstore\Bundle\ElectionBundle\Form\VotecenterPoolingType;
use Appstore\Bundle\ElectionBundle\Form\VotecenterType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Response;

/**
 * Votecenter controller.
 *
 */
class VotecenterController extends Controller
{

	public function paginate($entities)
	{

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$entities,
			$this->get('request')->query->get('page', 1)/*page number*/,
			25  /*limit per page*/
		);
		return $pagination;
	}



	/**
	 * Lists all Votecenter entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionVoteCenter();
		$data = $_REQUEST;
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->findWithSearch($config,$data);
		$pagination = $this->paginate($entities);
		return $this->render('ElectionBundle:Votecenter:index.html.twig', array(
			'entities' => $pagination,
			'entity' => $entity,
			'searchForm' => $data,
		));
	}


	/**
	 * @Secure(roles="ROLE_ELECTION,ROLE_DOMAIN")
	 */

	public function newAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionVoteCenter();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity->setElectionConfig($config);
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl('election_votecenter_edit', array('id' => $entity->getId())));

	}

	/**
	 * Finds and displays a ElectionMember entity.
	 *
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteCenter')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		$html = $this->renderView('ElectionBundle:Votecenter:show.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
		exit;

	}



	/**
	 * Displays a form to edit an existing Votecenter entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteCenter')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Votecenter entity.');
		}
		$editForm = $this->createEditForm($entity);
		return $this->render('ElectionBundle:Votecenter:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Votecenter entity.
	 *
	 * @param ElectionVoteCenter $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(ElectionVoteCenter $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');

		$form = $this->createForm(new VotecenterType($config,$location), $entity, array(
			'action' => $this->generateUrl('election_votecenter_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing Votecenter entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteCenter')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Votecenter entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		if ($editForm->isValid()) {
			$entity->setVoteCenterName($entity->getLocation()->getName());
			$entity->setMemberUnion($entity->getLocation()->getParent()->getName());
			$entity->setThana($entity->getLocation()->getParent()->getParent()->getName());
			$entity->setDistrict($entity->getElectionConfig()->getDistrict()->getName());
			$totalVoter = $entity->getMaleVoter() + $entity->getFemaleVoter() + $entity->getOtherVoter();
			$entity->setTotalVoter($totalVoter);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been changed successfully"
			);
			$this->getDoctrine()->getRepository('ElectionBundle:ElectionSetup')->insertTotalVote($entity->getElectionSetup());
			return $this->redirect($this->generateUrl('election_votecenter'));
		}

		return $this->render('ElectionBundle:Votecenter:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}



	/**
	 * Deletes a Votecenter entity.
	 *
	 */
	public function deleteAction($id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteCenter')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Votecenter entity.');
		}
		try {

			$em->remove($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'error',"Data has been deleted successfully"
			);

		} catch (ForeignKeyConstraintViolationException $e) {
			$this->get('session')->getFlashBag()->add(
				'notice',"Data has been relation another Table"
			);
		}catch (\Exception $e) {
			$this->get('session')->getFlashBag()->add(
				'notice', 'Please contact system administrator further notification.'
			);
		}

		return $this->redirect($this->generateUrl('election_votecenter'));
	}

	public function printAction(ElectionVoteCenter $center)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		if($config->getId() == $center->getElectionConfig()->getId()){
			return $this->render('ElectionBundle:Print:votecenter.html.twig', array(
				'entity'      => $center,
				'config'      => $config,
			));
		}else{
			return $this->redirect($this->generateUrl('election_votecenter'));
		}
	}

	public function printListAction()
	{
		$data = $_REQUEST;
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->findWithSearch($config,$data);
		$pagination = $entities->getQuery()->getResult();
		return $this->render('ElectionBundle:Print:votecenter-list.html.twig', array(
			'entities'      => $pagination,
			'config'      => $config,
		));
	}


	/**
	 * Status a Page entity.
	 *
	 */
	public function statusAction(Request $request, $id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteCenter')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find District entity.');
		}

		$status = $entity->isStatus();
		if($status == 1){
			$entity->setStatus(false);
		} else{
			$entity->setStatus(true);
		}
		$em->flush();
		$this->get('session')->getFlashBag()->add(
			'success',"Status has been changed successfully"
		);
		return $this->redirect($this->generateUrl('election_votecenter'));
	}

	public function autoSearchAction(Request $request)
	{
		$item = $_REQUEST['q'];
		if ($item) {
			$config = $this->getUser()->getGlobalOption()->getElectionConfig();
			$item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->searchAutoComplete($config,$item);
		}
		return new JsonResponse($item);
	}

	public function searchVotecenterNameAction($vendor)
	{
		return new JsonResponse(array(
			'id'=>$vendor,
			'text'=>$vendor
		));
	}

	public function returnResultData(ElectionVoteCenter $entity, $msg=''){

		$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->getMemberLists($entity);
		return $data;

	}

	private function createVotecenterForm(ElectionVoteCenter $committee , ElectionVoteCenterMember $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$memberRep = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember');
		$form = $this->createForm(new VotecenterMemberType($config,$committee,$memberRep), $entity, array(
			'action' => $this->generateUrl('election_votecenter_member_create',array('id' => $committee->getId())),
			'method' => 'POST',
			'attr' => array(
				'class' => 'horizontal-form',
				'id' => 'memberVotecenter',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	private function createVotecenterPoolingForm(ElectionVoteCenter $committee , ElectionVoteCenterMember $entity)
	{
		$form = $this->createForm(new VotecenterPoolingType(), $entity, array(
			'action' => $this->generateUrl('election_votecenter_pooling_create',array('id' => $committee->getId())),
			'method' => 'POST',
			'attr' => array(
				'class' => 'horizontal-form',
				'id' => 'memberPooling',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	public function newMemberAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionVoteCenter')->findOneBy(array('electionConfig'=>$config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Votecenter entity.');
		}
		$committeeForm = $this->createVotecenterForm($entity, New ElectionVoteCenterMember());
		$poolingForm = $this->createVotecenterPoolingForm($entity, New ElectionVoteCenterMember());
		return $this->render('ElectionBundle:Votecenter:member.html.twig', array(
			'entity'      => $entity,
			'form'   => $committeeForm->createView(),
			'pooling'   => $poolingForm->createView(),
		));
	}

	public function createMemberAction(Request $request, ElectionVoteCenter $center )
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionVoteCenterMember();
		$form = $this->createVotecenterForm($center,$entity);
		$form->handleRequest($request);
		$member = $request->request->all();
		$memberId = $member['committee']['member'];

		$member = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->find($memberId);
		$existMember = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenterMember')->findOneBy(array('voteCenter' => $center,'member' => $member));
		if ($form->isValid() and empty($existMember)) {
			$entity->setVotecenter($center);
			$entity->setMember($member);
			$entity->setPersonType('agent');
			$em->persist($entity);
			$em->flush();
		}
		$result = $this->returnResultData($center);
		return new Response($result);
		exit;

	}

	public function createPoolingAction(Request $request, ElectionVoteCenter $center )
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionVoteCenterMember();
		$form = $this->createVotecenterPoolingForm($center,$entity);
		$form->handleRequest($request);
		$member = $request->request->all();
		$mobile = $member['committee']['poolingMobile'];
		$name = $member['committee']['poolingOfficer'];
		$existMember = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenterMember')->findOneBy(array('voteCenter' => $center,'poolingMobile' => $mobile , 'poolingOfficer' => $name));
		if ($form->isValid() and empty($existMember)) {
			$entity->setVotecenter($center);
			$entity->setPersonType('pooling');
			$em->persist($entity);
			$em->flush();
		}
		$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionVoteCenter')->getPoolingLists($center);
		return new Response($data);
		exit;

	}


	public function memberDeleteAction(ElectionVoteCenterMember $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		if($entity->getVotecenter()->getElectionConfig()->getId() == $config->getId() ){
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			return new Response('success');
		}else{
			return new Response('in-valid');
		}

	}



}
