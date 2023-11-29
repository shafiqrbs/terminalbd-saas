<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionSms;
use Appstore\Bundle\ElectionBundle\Entity\ElectionSmsMember;
use Appstore\Bundle\ElectionBundle\EventListener\ElectionSmsEvent;
use Appstore\Bundle\ElectionBundle\Form\SmsMemberType;
use Appstore\Bundle\ElectionBundle\Form\SmsPoolingType;
use Appstore\Bundle\ElectionBundle\Form\SmsType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Response;

/**
 * SmsController controller.
 *
 */
class SmsController extends Controller
{

	/**
	 * Lists all Sms entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionSms();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionSms')->findBy(array('electionConfig' => $config),array('created'=>'DESC'));
		return $this->render('ElectionBundle:Sms:index.html.twig', array(
			'entities' => $entities,
			'entity' => $entity,
		));
	}


	/**
	 * @Secure(roles="ROLE_ELECTION,ROLE_DOMAIN")
	 */

	public function newAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionSms();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity->setElectionConfig($config);
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl('election_sms_edit', array('id' => $entity->getId())));

	}

	/**
	 * Finds and displays a ElectionSms entity.
	 *
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}
		$html = $this->renderView('ElectionBundle:Sms:show.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
		exit;

	}

	/**
	 * Displays a form to edit an existing Sms entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Sms entity.');
		}
		$editForm = $this->createEditForm($entity);
		return $this->render('ElectionBundle:Sms:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Sms entity.
	 *
	 * @param Sms $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(ElectionSms $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');

		$form = $this->createForm(new SmsType($config,$location), $entity, array(
			'action' => $this->generateUrl('election_sms_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing Sms entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Sms entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		$data = $request->request->all();
		if ($editForm->isValid()) {
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been changed successfully"
			);
			return $this->redirect($this->generateUrl('election_sms'));
		}

		return $this->render('ElectionBundle:Sms:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Deletes a Sms entity.
	 *
	 */
	public function deleteAction($id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Sms entity.');
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
		return $this->redirect($this->generateUrl('election_sms'));
	}


	/**
	 * Status a Page entity.
	 *
	 */
	public function statusAction(Request $request, $id)
	{

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->find($id);

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
		return $this->redirect($this->generateUrl('election_sms'));
	}

	public function autoSearchAction(Request $request)
	{
		$item = $_REQUEST['q'];
		if ($item) {
			$inventory = $this->getUser()->getGlobalOption()->getElectionConfig();
			$item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionSms')->searchAutoComplete($item,$inventory);
		}
		return new JsonResponse($item);
	}

	public function searchSmsNameAction($vendor)
	{
		return new JsonResponse(array(
			'id'=>$vendor,
			'text'=>$vendor
		));
	}

	/**
	 * Finds and displays a ElectionSms entity.
	 *
	 */
	public function memberAction(Request $request , $id)
	{
		$msg = $_REQUEST['sms'];
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionMember')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if(!$entity){
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}
	//	if(!empty($entity->getHotelConfig()->isNotification() == 1) and  !empty($this->getUser()->getGlobalOption()->getSmsSenderTotal())) {
			$dispatcher = $this->container->get('event_dispatcher');
			$dispatcher->dispatch('appstore_election.post.election_sms', new \Appstore\Bundle\ElectionBundle\Event\ElectionSmsEvent($entity,$msg));
	//	}
		exit;

	}

	/**
	 * Finds and displays a ElectionSms entity.
	 *
	 */
	public function bulkSmsAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if(!$entity){
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}
		$dispatcher = $this->container->get('event_dispatcher');
		$dispatcher->dispatch('appstore_election.post.election_bulk_sms', new \Appstore\Bundle\ElectionBundle\Event\ElectionSmsBulkEvent($entity));
		exit;

	}

	public function committeeAction(Request $request , $id)
	{
		$msg = $_REQUEST['sms'];
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}

		exit;

	}



	public function eventAction(Request $request , $id)
	{
		$msg = $_REQUEST['sms'];
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}

		exit;

	}

	public function voteCenterAction(Request $request , $id)
	{
		$msg = $_REQUEST['sms'];
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionSms')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}

		exit;

	}




}
