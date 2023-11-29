<?php

namespace Appstore\Bundle\AssetsBundle\Controller;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Appstore\Bundle\AssetsBundle\Entity\Particular;
use Appstore\Bundle\AssetsBundle\Form\ParticularType;

/**
 * Particular controller.
 *
 */
class ParticularController extends Controller
{

	/**
	 * Lists all Particular entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$inventory = $this->getUser()->getGlobalOption()->getAssetsConfig();
		$entities = $em->getRepository( 'AssetsBundle:Particular' )->findBy(array( 'config' =>$inventory),array( 'type' =>'asc','name' =>'asc'));

		return $this->render('AssetsBundle:Particular:index.html.twig', array(
			'entities' => $entities,
		));
	}
	/**
	 * Creates a new Particular entity.
	 *
	 */
	public function createAction(Request $request)
	{
		$entity = new Particular();
		$form = $this->createCreateForm($entity);
		$form->handleRequest($request);
		if ($form->isValid()) {
            $inventory = $this->getUser()->getGlobalOption()->getAssetsConfig();
			$em = $this->getDoctrine()->getManager();
            $entity->setConfig($inventory);
			$em->persist($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been added successfully"
			);
			return $this->redirect($this->generateUrl('assets_particular'));
		}

		return $this->render('AssetsBundle:Particular:new.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView(),
		));
	}

	/**
	 * Creates a form to create a Particular entity.
	 *
	 * @param Particular $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCreateForm(Particular $entity)
	{
		$form = $this->createForm(new ParticularType(), $entity, array(
			'action' => $this->generateUrl('assets_particular_create'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	/**
	 * Displays a form to create a new Particular entity.
	 *
	 */
	public function newAction()
	{
		$entity = new Particular();
		$form   = $this->createCreateForm($entity);

		return $this->render('AssetsBundle:Particular:new.html.twig', array(
			'entity' => $entity,
			'form'   => $form->createView(),
		));
	}

	/**
	 * Finds and displays a Particular entity.
	 *
	 */
	public function showAction($id)
	{

	}

	/**
	 * Displays a form to edit an existing Particular entity.
	 *
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AssetsBundle:Particular' )->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Particular entity.');
		}

		$editForm = $this->createEditForm($entity);

		return $this->render('AssetsBundle:Particular:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Particular entity.
	 *
	 * @param Particular $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(Particular $entity)
	{
		$form = $this->createForm(new ParticularType(), $entity, array(
			'action' => $this->generateUrl('assets_particular_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing Particular entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AssetsBundle:Particular' )->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Particular entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been updated successfully"
			);
			return $this->redirect($this->generateUrl('assets_particular_edit', array('id' => $id)));
		}

		return $this->render('AssetsBundle:Particular:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Deletes a Particular entity.
	 *
	 */
	public function deleteAction(Particular $entity)
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

		return $this->redirect($this->generateUrl('assets_particular'));
	}

	public function autoSearchAction(Request $request)
	{
		$item = $_REQUEST['q'];
		if ($item) {
			$inventory = $this->getUser()->getGlobalOption()->getAssetsConfig()->getId();
			$item = $this->getDoctrine()->getRepository( 'AssetsBundle:Particular' )->searchAutoComplete($inventory,$item);
		}
		return new JsonResponse($item);
	}

	public function searchParticularNameAction($brand)
	{
		return new JsonResponse(array(
			'id'=> $brand,
			'text'=> $brand
		));
	}
}
