<?php

namespace Appstore\Bundle\AssetsBundle\Controller;



use Appstore\Bundle\AssetsBundle\Entity\OfficeNote;
use Appstore\Bundle\AssetsBundle\Form\OfficeNoteType;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisitionItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * OfficeNoteController controller.
 *
 */
class OfficeNoteController extends Controller
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
	 * Lists all OfficeNoteOrder entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
		$entities = $em->getRepository('AssetsBundle:OfficeNote')->findWithSearch($config,$data);
		$pagination = $this->paginate($entities);
		return $this->render('AssetsBundle:OfficeNote:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}


	/**
	 * Displays a form to create a new OfficeNote entity.
	 *
	 */
	public function newAction()
	{
		$config = $this->getUser()->getGlobalOption()->getAssetsConfig();
	    $em = $this->getDoctrine()->getManager();
		$entity = new OfficeNote();
        $entity->setConfig($config);
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl('assets_officenote_edit', array('id' => $entity->getId())));
	}

	/**
	 * Finds and displays a OfficeNote entity.
	 *
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('AssetsBundle:OfficeNote')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find OfficeNote entity.');
		}
        $inventory = $this->getUser()->getGlobalOption()->getProcurementConfig();
        $data = '';
        $entities = $em->getRepository('ProcurementBundle:PurchaseRequisition')->findWithSearchApproved($inventory->getId(),$data);
        $pagination = $entities->getQuery()->getResult();
        return $this->render('AssetsBundle:OfficeNote:show.html.twig', array(
            'entity'      => $entity,
            'entities'      => $pagination,
        ));
	}

	/**
	 * Displays a form to edit an existing OfficeNote entity.
	 *
	 */
	
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$officeNote = $em->getRepository('AssetsBundle:OfficeNote')->find($id);
		if (!$officeNote) {
			throw $this->createNotFoundException('Unable to find OfficeNote entity.');
		}
		$editForm = $this->createEditForm($officeNote);
        $inventory = $this->getUser()->getGlobalOption()->getProcurementConfig();
        $data = '';
        $entities = $em->getRepository('ProcurementBundle:PurchaseRequisition')->findWithSearchApproved($inventory->getId(),$data);
        $pagination = $entities->getQuery()->getResult();
        $records = $this->getDoctrine()->getRepository(PurchaseRequisitionItem::class)->purchaseGroupItemQuantity($inventory);
        return $this->render('AssetsBundle:OfficeNote:new.html.twig', array(
			'entity'        => $officeNote,
			'entities'      => $pagination,
			'records'       => $records,
			'form'          => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a OfficeNote entity.
	 *
	 * @param OfficeNote $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(OfficeNote $entity)
	{
		$form = $this->createForm(new OfficeNoteType(), $entity, array(
			'action' => $this->generateUrl('assets_officenote_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'id' => '',
				'class' => 'horizontal-form OfficeNote',
				'novalidate' => 'novalidate',
			)

		));
		return $form;
	}


	/**
	 * Edits an existing OfficeNote entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('AssetsBundle:OfficeNote')->find($id);
		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		if ($editForm->isValid()) {
			$entity->setProcess('Complete');
			$entity->upload();
		    $em->flush();
            return $this->redirect($this->generateUrl('assets_officenote'));
        }
		return $this->render('AssetsBundle:OfficeNote:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	public function approveAction(OfficeNote $OfficeNote)
	{
		set_time_limit(0);
		$em = $this->getDoctrine()->getManager();
		if(empty($OfficeNote->getCheckedBy())  and $OfficeNote->getProcess() == "Complete"){
            $OfficeNote->setProcess('Checked');
            $OfficeNote->setCheckedBy($this->getUser());
        }
        if(empty($OfficeNote->getApprovedBy())  and $OfficeNote->getProcess() == "Checked"){
            $OfficeNote->setApprovedBy($this->getUser());
            $OfficeNote->setProcess('Checked');
        }
		$em->persist($OfficeNote);
        $em->flush();
		return new Response(json_encode(array('success'=>'success')));

	}

	/**
	 * Deletes a OfficeNote entity.
	 *
	 */
	public function deleteAction(OfficeNote $note)
	{
		if($note){
			$em = $this->getDoctrine()->getManager();
            $note->setIsDeleete(true);
			$em->flush();
			return new Response('success');
		}else{
			return new Response('failed');
		}
	}
	
	
	public function  updateOfficeNoteStatus(OfficeNote $entity,$process){

		$em = $this->getDoctrine()->getManager();
		$entity->setProcess($process);
		$em->persist($entity);
		$em->flush();
	}



}
