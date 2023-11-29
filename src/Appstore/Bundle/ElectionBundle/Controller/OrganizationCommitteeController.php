<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionCommittee;
use Appstore\Bundle\ElectionBundle\Entity\ElectionCommitteeMember;
use Appstore\Bundle\ElectionBundle\Form\CommitteeMemberType;
use Appstore\Bundle\ElectionBundle\Form\CommitteeType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Response;

/**
 * Committee controller.
 *
 */
class OrganizationCommitteeController extends Controller
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
	 * Lists all Committee entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionCommittee();
		$data = $_REQUEST;
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommittee')->findWithSearch($config,$data);
		$pagination = $this->paginate($entities);

		$locationTypes = $this->getDoctrine()->getRepository( 'ElectionBundle:ElectionParticular' )->getListOfParticular($config,'location');
		return $this->render('ElectionBundle:Committee:index.html.twig', array(
			'entities' => $pagination,
			'locationTypes' => $locationTypes,
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
		$entity = new ElectionCommittee();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity->setElectionConfig($config);
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl('election_committee_edit', array('id' => $entity->getId())));

	}

	/**
	 * Finds and displays a ElectionMember entity.
	 *
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCommittee')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		$html = $this->renderView('ElectionBundle:Committee:show.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
		exit;

	}



	/**
	 * Displays a form to edit an existing Committee entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCommittee')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Committee entity.');
		}
		$editForm = $this->createEditForm($entity);
		return $this->render('ElectionBundle:Committee:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Committee entity.
	 *
	 * @param Committee $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(ElectionCommittee $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');

		$form = $this->createForm(new CommitteeType($config,$location), $entity, array(
			'action' => $this->generateUrl('election_committee_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing Committee entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCommittee')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Committee entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		$data = $request->request->all();
		if ($editForm->isValid()) {
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been changed successfully"
			);
			return $this->redirect($this->generateUrl('election_committee'));
		}

		return $this->render('ElectionBundle:Committee:index.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}



	/**
	 * Deletes a Committee entity.
	 *
	 */
	public function deleteAction($id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionCommittee')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Committee entity.');
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

		return $this->redirect($this->generateUrl('election_committee'));
	}


	/**
	 * Status a Page entity.
	 *
	 */
	public function statusAction(Request $request, $id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionCommittee')->find($id);

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
		return $this->redirect($this->generateUrl('election_committee'));
	}

	public function autoSearchAction(Request $request)
	{
		$item = $_REQUEST['q'];
		if ($item) {
			$config = $this->getUser()->getGlobalOption()->getElectionConfig();
			$item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommittee')->searchAutoComplete($config,$item);
		}
		return new JsonResponse($item);
	}

	public function searchCommitteeNameAction($vendor)
	{
		return new JsonResponse(array(
			'id'=>$vendor,
			'text'=>$vendor
		));
	}

	public function returnResultData(ElectionCommittee $entity, $msg=''){

		$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommitteeMember')->getMemberLists($entity);
		return $data;

	}

	private function createCommitteeForm(ElectionCommittee $committee , ElectionCommitteeMember $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$memberRep = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember');
		$form = $this->createForm(new CommitteeMemberType($config,$committee,$memberRep), $entity, array(
			'action' => $this->generateUrl('election_committee_member_create',array('id' => $committee->getId())),
			'method' => 'POST',
			'attr' => array(
				'class' => 'horizontal-form',
				'id' => 'memberCommittee',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	public function newMemberAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionCommittee')->findOneBy(array('electionConfig'=>$config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Committee entity.');
		}
		$committeeForm = $this->createCommitteeForm($entity, New ElectionCommitteeMember());
		return $this->render('ElectionBundle:Committee:member.html.twig', array(
			'entity'      => $entity,
			'form'   => $committeeForm->createView(),
		));
	}

	public function createMemberAction(Request $request, ElectionCommittee $committee )
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionCommitteeMember();
		$form = $this->createCommitteeForm($committee,$entity);
		$form->handleRequest($request);
		$member = $request->request->all();
		$memberId = $member['committee']['member'];
		$member = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->find($memberId);
		$existMember = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommitteeMember')->findOneBy(array('committee' => $committee,'member' => $member));
		if ($form->isValid() and empty($existMember)) {
			$member = $this->getDoctrine()->getRepository('ElectionBundle:ElectionMember')->find($memberId);
			$entity->setCommittee($committee);
			$entity->setMember($member);
			$em->persist($entity);
			$em->flush();
			$result = $this->returnResultData($committee);
			return new Response($result);
		}
		return new Response('invalid');
		exit;

	}

	public function memberDeleteAction(ElectionCommitteeMember $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		if($entity->getCommittee()->getElectionConfig()->getId() == $config->getId() ){
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			return new Response('success');
		}else{
			return new Response('in-valid');
		}

	}

	public function printAction(ElectionCommittee $entity){

		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		// $entity = $em->getRepository('ElectionBundle:ElectionCommittee')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		return $this->render('ElectionBundle:Print:committe.html.twig', array(
			'entity'      => $entity,
		));
	}

	public function listPrintAction(){

		$data = $_REQUEST;
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionCommittee')->findWithSearch($config,$data);
		$pagination = $entities->getQuery()->getResult();
		return $this->render('ElectionBundle:Print:committee-list.html.twig', array(
			'entities'      => $pagination,
		));
	}



}
