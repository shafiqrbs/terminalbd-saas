<?php

namespace Appstore\Bundle\DomainUserBundle\Controller;

use Appstore\Bundle\DomainUserBundle\Entity\CustomerSms;
use Appstore\Bundle\DomainUserBundle\Event\AssociationSmsEvent;
use Appstore\Bundle\ElectionBundle\Entity\ElectionSms;
use Appstore\Bundle\ElectionBundle\Entity\ElectionSmsMember;
use Appstore\Bundle\ElectionBundle\EventListener\ElectionSmsEvent;
use Appstore\Bundle\ElectionBundle\Form\SmsMemberType;
use Appstore\Bundle\ElectionBundle\Form\SmsPoolingType;
use Appstore\Bundle\ElectionBundle\Form\SmsType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
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
class CustomerSmsController extends Controller
{

	/**
	 * Lists all Sms entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new CustomerSms();
        $config = $this->getUser()->getGlobalOption();
		$entities = $this->getDoctrine()->getRepository('DomainUserBundle:CustomerSms')->findBy(array('globalOption' => $config),array('created'=>'DESC'));
		return $this->render('ElectionBundle:Sms:index.html.twig', array(
			'entities' => $entities,
			'entity' => $entity,
		));
	}


	/**
	 * @Secure(roles="ROLE_CRM_ASSOCIATION,ROLE_DOMAIN")
	 */

	public function newAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionSms();
		$config = $this->getUser()->getGlobalOption();
		$entity->setGlobalOption($config);
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
        $config = $this->getUser()->getGlobalOption();
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->findOneBy(array('globalOption' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}
		$html = $this->renderView('ElectionBundle:Sms:show.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
	}

	/**
	 * Displays a form to edit an existing Sms entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption();
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->find($id);

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
        $config = $this->getUser()->getGlobalOption();
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
        $config = $this->getUser()->getGlobalOption();
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->find($id);

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
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->find($id);

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
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->find($id);

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
			$inventory = $this->getUser();
			$item = $this->getDoctrine()->getRepository('DomainUserBundle:CustomerSms')->searchAutoComplete($item,$inventory);
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
        $config = $this->getUser()->getGlobalOption();
        $entity = $em->getRepository('DomainUserBundle:Customer')->findOneBy(array('globalOption' => $config,'id'=>$id));
        if(!$entity){
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }
        /* @var $global GlobalOption */
        $global = $this->getUser()->getGlobalOption();
        if($global->getSmsSenderTotal() and $global->getSmsSenderTotal()->getRemaining() > 0 and $global->getNotificationConfig()->getSmsActive() == 1) {
            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch('appstore.customer.post.member_sms', new AssociationSmsEvent($entity,$msg));

        }
        return new Response('Success');


    }


    /**
	 * Finds and displays a ElectionSms entity.
	 *
	 */
	public function bulkSmsAction($id)
	{
		$em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption();
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->findOneBy(array('globalOption' => $config,'id'=>$id));
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
        $config = $this->getUser()->getGlobalOption();
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->findOneBy(array('globalOption' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}

		exit;

	}



	public function eventAction(Request $request , $id)
	{
		$msg = $_REQUEST['sms'];
		$em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption();
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->findOneBy(array('globalOption' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}

		exit;

	}

	public function voteCenterAction(Request $request , $id)
	{
		$msg = $_REQUEST['sms'];
		$em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption();
		$entity = $em->getRepository('DomainUserBundle:CustomerSms')->findOneBy(array('globalOption' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionSms entity.');
		}

		exit;

	}




}
