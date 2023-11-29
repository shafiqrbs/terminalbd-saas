<?php

namespace Appstore\Bundle\AssetsBundle\Controller;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Appstore\Bundle\AssetsBundle\Entity\Product;
use Appstore\Bundle\AssetsBundle\Form\ProductType;

/**
 * ProductController controller.
 *
 */
class ProductController extends Controller
{

	public function paginate($entities)
	{

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$entities,
			$this->get('request')->query->get('page', 1)/*page number*/,
			25  /*limit per page*/
		);
        $pagination->setTemplate('SettingToolBundle:Widget:pagination.html.twig');
		return $pagination;
	}


	/**
	 * Lists all Item entities.
	 */

	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig()->getId();
		$entities = $em->getRepository('AssetsBundle:Product')->findWithSearch($config,$data);
		$pagination = $this->paginate($entities);
		return $this->render('AssetsBundle:Product:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}


	/**
	 * Creates a new Product entity.
	 *
	 */
	public function createAction(Request $request)
	{
		$entity = new Product();
		$form = $this->createCreateForm($entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been added successfully"
			);
			return $this->redirect($this->generateUrl('assets_product'));
		}

		return $this->render('AssetsBundle:Product:new.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView(),
		));
	}

	/**
	 * Creates a form to create a Product entity.
	 *
	 * @param Product $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCreateForm(Product $entity)
	{
		$form = $this->createForm(new ProductType(), $entity, array(
			'action' => $this->generateUrl('assets_product_create'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'horizontal-form',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	/**
	 * Displays a form to create a new Product entity.
	 *
	 */
	public function newAction()
	{
		$entity = new Product();
		$form   = $this->createCreateForm($entity);

		return $this->render('AssetsBundle:Product:new.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView(),
		));
	}

	/**
	 * Finds and displays a Product entity.
	 *
	 */
	public function showAction($id)
	{
        return $this->redirect($this->generateUrl('assets_product'));
	}

	/**
	 * Displays a form to edit an existing Product entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AssetsBundle:Product' )->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Product entity.');
		}

		$editForm = $this->createEditForm($entity);

		return $this->render('AssetsBundle:Product:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Product entity.
	 *
	 * @param Product $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(Product $entity)
	{
		$form = $this->createForm(new ProductType(), $entity, array(
			'action' => $this->generateUrl('assets_product_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'horizontal-form',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing Product entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AssetsBundle:Product' )->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Product entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			if(!empty($entity->getDepreciation())){
				$entity->setCustomDepreciation(true);
			}
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been updated successfully"
			);
			return $this->redirect($this->generateUrl('assets_product'));
		}

		return $this->render('AssetsBundle:Product:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Deletes a Product entity.
	 *
	 */
	public function deleteAction(Product $entity)
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
		return $this->redirect($this->generateUrl('assets_product'));
	}

	public function depreciationSelectAction()
	{
		$entities = $this->getDoctrine()->getRepository('AssetsBundle:Particular')->findBy(array('type'=>3));
		$items = array();
		foreach ($entities as $entity):
			$items[]=array('value' => $entity->getId(),'text'=> $entity->getName());
		endforeach;
		return new JsonResponse($items);
	}

	public function inlineUpdateAction(Request $request)
	{
		$data = $request->request->all();
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('AssetsBundle:Product')->find($data['pk']);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Item entity.');
		}
		if($data['name'] == 'DepreciationStatus' and !empty($data['value']) ){
			$process = $this->getDoctrine()->getRepository('AssetsBundle:Particular')->find($data['value']);
			$entity->setDepreciationStatus($process);
		}else{
			$process = 'set'.$data['name'];
			$entity->$process($data['value']);
		}

		$em->flush();
		exit;
	}



}
