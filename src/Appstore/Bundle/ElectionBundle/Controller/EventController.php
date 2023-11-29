<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionEvent;
use Appstore\Bundle\ElectionBundle\Entity\ElectionEventMember;
use Appstore\Bundle\ElectionBundle\Form\EventMemberType;
use Appstore\Bundle\ElectionBundle\Form\EventType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Event controller.
 *
 */
class EventController extends Controller
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
     * Lists all Event entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
	    $data = $_REQUEST;
        $entity = new ElectionEvent();
        $form = $this->createCreateForm($entity);
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->findWithSearch($config,$data);
	    $pagination = $this->paginate($entities);
        $locationTypes = $this->getDoctrine()->getRepository( 'ElectionBundle:ElectionParticular' )->getListOfParticular($config,'location');

	    return $this->render('ElectionBundle:Event:index.html.twig', array(
            'entities' => $pagination,
            'entity' => $entity,
            'searchForm' => $data,
            'locationTypes' => $locationTypes,
            'show'      => 'hide',
            'form'   => $form->createView(),
        ));
    }
    /**
     * Creates a new Event entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ElectionEvent();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->findWithSearch($config);
	    $pagination = $this->paginate($entities);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $config = $this->getUser()->getGlobalOption()->getElectionConfig();
            $entity->setElectionConfig($config);
	        $eventTime = $request->request->get('eventTime');
	        $entity->setEventTime($eventTime);
	        $entity->upload();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been inserted successfully"
            );
            return $this->redirect($this->generateUrl('election_event', array('id' => $entity->getId())));
        }

        return $this->render('ElectionBundle:Event:index.html.twig', array(
            'entities' => $pagination,
            'entity' => $entity,
            'show'      => 'show',
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Event entity.
     *
     * @param ElectionEvent $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ElectionEvent $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
    	$form = $this->createForm(new EventType($config,$location), $entity, array(
            'action' => $this->generateUrl('election_event_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'id' => 'campaign',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

	/**
	 * Finds and displays a ElectionMember entity.
	 *
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionEvent')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		$html = $this->renderView('ElectionBundle:Event:show.html.twig',
			array('entity' => $entity)
		);
		return New Response($html);
		exit;

	}


	/**
     * Displays a form to edit an existing Event entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $pagination = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->findWithSearch($config);
	    $entities = $this->paginate($pagination);
        $entity = $em->getRepository('ElectionBundle:ElectionEvent')->findOneBy(array('electionConfig'=>$config,'id'=>$id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('ElectionBundle:Event:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'show'      => 'show',
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Event entity.
    *
    * @param Event $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ElectionEvent $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $location = $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation');
	    $form = $this->createForm(new EventType($config,$location), $entity, array(
            'action' => $this->generateUrl('election_event_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'id' => 'campaign',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing Event entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
	    $pagination = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->findWithSearch($config);
	    $entities = $this->paginate($pagination);
        $entity = $em->getRepository('ElectionBundle:ElectionEvent')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
	        $eventTime = $request->request->get('eventTime');
	        $entity->setEventTime($eventTime);
	        if($entity->getRemoveImage() == 1){
	        	$entity->removeUpload();
	        }
	        $entity->upload();
        	$em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been changed successfully"
            );
            return $this->redirect($this->generateUrl('election_event'));
        }

        return $this->render('ElectionBundle:Event:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'show'      => 'show',
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Event entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ElectionBundle:ElectionEvent')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
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

        return $this->redirect($this->generateUrl('election_event'));
    }


    /**
     * Status a Page entity.
     *
     */
    public function statusAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ElectionBundle:ElectionEvent')->find($id);

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
        return $this->redirect($this->generateUrl('election_event'));
    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $config = $this->getUser()->getGlobalOption()->getElectionConfig();
            $item = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->searchAutoComplete($config,$item);
        }
        return new JsonResponse($item);
    }

    public function searchEventNameAction($vendor)
    {
        return new JsonResponse(array(
            'id' => $vendor,
            'text' => $vendor
        ));
    }

	public function returnResultData(ElectionEvent $entity, $msg=''){

		$data = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->getMemberLists($entity);
		return $data;

	}

	private function createCommitteeForm(ElectionEvent $event ,ElectionEventMember $entity)
	{
		$form = $this->createForm(new EventMemberType(), $entity, array(
			'action' => $this->generateUrl('election_event_member_create',array('id' => $event->getId())),
			'method' => 'POST',
			'attr' => array(
				'class' => 'horizontal-form',
				'id' => 'memberEvent',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	public function newMemberAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entity = $em->getRepository('ElectionBundle:ElectionEvent')->findOneBy(array('electionConfig'=>$config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Committee entity.');
		}
		$eventForm = $this->createCommitteeForm($entity, New ElectionEventMember());
		return $this->render('ElectionBundle:Event:member.html.twig', array(
			'entity'      => $entity,
			'form'   => $eventForm->createView(),
		));
	}

	public function createMemberAction(Request $request, ElectionEvent $event )
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new ElectionEventMember();
		$form = $this->createCommitteeForm($event,$entity);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$entity->setEvent($event);
			$em->persist($entity);
			$em->flush();
			$result = $this->returnResultData($event);
			return new Response($result);
		}
		return new Response('invalid');
		exit;

	}

	public function memberDeleteAction(ElectionEventMember $entity)
	{
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		if($entity->getEvent()->getElectionConfig()->getId() == $config->getId() ){
			$em = $this->getDoctrine()->getManager();
			$em->remove($entity);
			$em->flush();
			return new Response('success');
		}else{
			return new Response('in-valid');
		}

	}

	public function printAction(ElectionEvent $entity){

		$em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		// $entity = $em->getRepository('ElectionBundle:ElectionCommittee')->findOneBy(array('electionConfig' => $config,'id'=>$id));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find ElectionMember entity.');
		}
		return $this->render('ElectionBundle:Print:campaign.html.twig', array(
			'entity'      => $entity,
		));
	}

	public function listPrintAction(){

		$data = $_REQUEST;
		$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionEvent')->findWithSearch($config,$data);
		$pagination = $entities->getQuery()->getResult();
		return $this->render('ElectionBundle:Print:campaign-list.html.twig', array(
			'entities'      => $pagination,
		));
	}





}
