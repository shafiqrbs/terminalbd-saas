<?php

namespace Appstore\Bundle\AssetsBundle\Controller;

use Appstore\Bundle\AssetsBundle\Form\DistributionlType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Appstore\Bundle\AssetsBundle\Entity\Distribution;


/**
 * DistributionController controller.
 *
 */
class DistributionController extends Controller
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
	 * Lists all Item entities.
	 */

	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$entities = $em->getRepository('AssetsBundle:Distribution')->findWithSearch($data);
		$pagination = $this->paginate($entities);
		return $this->render('AssetsBundle:Distribution:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}


	/**
	 * Creates a new Distribution entity.
	 *
	 */
	public function createAction(Request $request)
	{
		$entity = new Distribution();
		$form = $this->createCreateForm($entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been added successfully"
			);
			return $this->redirect($this->generateUrl('assets_distribution'));
		}

		return $this->render('AssetsBundle:Distribution:new.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView(),
		));
	}

	/**
	 * Creates a form to create a Distribution entity.
	 *
	 * @param Distribution $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCreateForm(Distribution $entity)
	{
		$option = $this->getUser()->getGlobalOption();
	    $form = $this->createForm(new DistributionlType($option), $entity, array(
			'action' => $this->generateUrl('assets_distribution_create'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'horizontal-form',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	/**
	 * Displays a form to create a new Distribution entity.
	 *
	 */
	public function newAction()
	{
		$entity = new Distribution();
		$form   = $this->createCreateForm($entity);

		return $this->render('AssetsBundle:Distribution:new.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView(),
		));
	}

	/**
	 * Finds and displays a Distribution entity.
	 *
	 */
	public function showAction($id)
	{

	}

	/**
	 * Displays a form to edit an existing Distribution entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AssetsBundle:Distribution' )->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Distribution entity.');
		}

		$editForm = $this->createEditForm($entity);

		return $this->render('AssetsBundle:Distribution:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Distribution entity.
	 *
	 * @param Distribution $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(Distribution $entity)
	{
        $option = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new DistributionlType($option), $entity, array(
			'action' => $this->generateUrl('assets_distribution_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'horizontal-form',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing Distribution entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AssetsBundle:Distribution' )->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Distribution entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been updated successfully"
			);
			return $this->redirect($this->generateUrl('assets_distribution_edit', array('id' => $id)));
		}

		return $this->render('AssetsBundle:Distribution:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Deletes a Distribution entity.
	 *
	 */
	public function deleteAction(Distribution $entity)
	{
		$em = $this->getDoctrine()->getManager();
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Brand entity.');
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
		return $this->redirect($this->generateUrl('assets_distribution'));
	}


}
