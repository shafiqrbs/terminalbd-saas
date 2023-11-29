<?php

namespace Appstore\Bundle\AssetsBundle\Controller;


use Appstore\Bundle\AssetsBundle\Entity\DisposalItem;
use Appstore\Bundle\AssetsBundle\Form\DisposalItemType;
use Appstore\Bundle\AssetsBundle\Form\DisposalType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Appstore\Bundle\AssetsBundle\Entity\Disposal;
use Symfony\Component\HttpFoundation\Response;


/**
 * DisposalController controller.
 *
 */
class DisposalController extends Controller
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
	 * Lists all DisposalOrder entities.
	 *
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$entities = $em->getRepository('AssetsBundle:Disposal')->findAll();
		$pagination = $this->paginate($entities);

		return $this->render('AssetsBundle:Disposal:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}


	/**
	 * Displays a form to create a new Disposal entity.
	 *
	 */
	public function newAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new Disposal();
		$em->persist($entity);
		$em->flush();
		return $this->redirect($this->generateUrl('assets_disposal_edit', array('id' => $entity->getId())));

	}

	/**
	 * Finds and displays a Disposal entity.
	 *
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('AssetsBundle:Disposal')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Disposal entity.');
		}
		return $this->render('AssetsBundle:Disposal:show.html.twig', array(
			'entity' => $entity,
		));
	}

	/**
	 * Displays a form to edit an existing Disposal entity.
	 *
	 */
	
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$disposal = $em->getRepository('AssetsBundle:Disposal')->find($id);

		if (!$disposal) {
			throw $this->createNotFoundException('Unable to find Disposal entity.');
		}
		$disposalItem = new DisposalItem();
		$disposalItemForm = $this->createDisposalItemForm($disposalItem,$disposal);
		$editForm = $this->createEditForm($disposal);

		return $this->render('AssetsBundle:Disposal:new.html.twig', array(
			'entity'      => $disposal,
			'disposalItemForm'   => $disposalItemForm->createView(),
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Disposal entity.
	 *
	 * @param Disposal $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(Disposal $entity)
	{
		$form = $this->createForm(new DisposalType(), $entity, array(
			'action' => $this->generateUrl('assets_disposal_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'id' => 'DisposalForm',
				'class' => 'horizontal-form Disposal',
				'novalidate' => 'novalidate',
			)

		));
		return $form;
	}

	/**
	 * Creates a form to edit a Disposal entity.
	 *
	 * @param Disposal $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDisposalItemForm(DisposalItem $disposalItem , Disposal $entity)
	{
		$form = $this->createForm(new DisposalItemType(), $disposalItem, array(
			'action' => $this->generateUrl('assets_disposal_create', array('disposal' => $entity->getId())),
			'method' => 'POST',
			'attr' => array(
				'id' => 'disposalItemForm',
				'class' => 'horizontal-form Disposal',
				'novalidate' => 'novalidate',
			)

		));
		return $form;
	}

	/**
	 * Edits an existing Disposal entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('AssetsBundle:Disposal')->find($id);
		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		if ($editForm->isValid()) {
			$em->flush();
			$this->getDoctrine()->getRepository('AssetsBundle:Disposal')->disposalUpdate($entity);
			if($entity->getProcess() == 'approved' ){
				$this->approveAction($entity);
				return $this->redirect($this->generateUrl('assets_disposal_show', array('id' => $id)));
			}elseif($entity->getProcess() == 'complete' ){
				return $this->redirect($this->generateUrl('assets_disposal_show', array('id' => $id)));
			}else{
				return $this->redirect($this->generateUrl('assets_disposal_edit', array('id' => $id)));
			}
		}
		return $this->render('AssetsBundle:Disposal:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),

		));
	}

	public function approveAction(Disposal $disposal)
	{

		set_time_limit(0);
		$em = $this->getDoctrine()->getManager();
		$disposal->setApprovedBy($this->getUser());
		$disposal->setProcess('approved');
		$em->persist($disposal);
		$em->flush();
		//$em->getRepository('InventoryBundle:Item')->($disposal);
		//$em->getRepository('InventoryBundle:StockItem')->insertDamageItem($disposal);
		//$this->getDoctrine()->getRepository('ProcurementBundle:DisposalOrderItem')->updateIssueItem($disposal->getDisposalOrder());
		return new Response(json_encode(array('success'=>'success')));

	}

	/**
	 * Deletes a Disposal entity.
	 *
	 */
	public function deleteAction(Disposal $disposal)
	{
		if($disposal){
			$em = $this->getDoctrine()->getManager();
			$em->remove($disposal);
			$em->flush();
			return new Response('success');
		}else{
			return new Response('failed');
		}

		exit;
	}


	public function  updateDisposalStatus(Disposal $entity,$process){

		$em = $this->getDoctrine()->getManager();
		$entity->setProcess($process);
		$em->persist($entity);
		$em->flush();
	}

	public function autoSearchAction(Request $request)
	{
		$item = $_REQUEST['q'];
		if ($item) {
			$inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
			$item = $this->getDoctrine()->getRepository('AssetsBundle:Disposal')->searchAutoComplete($inventory,$item);
		}
		return new JsonResponse($item);
	}

	public function searchNameAction($grn)
	{
		return new JsonResponse(array(
			'id'=>$grn,
			'text'=>$grn
		));
	}



	/**
	 * Creates a new Disposal entity.
	 *
	 */
	public function createDisposalItemAction(Request $request, Disposal $disposal)
	{
		$em = $this->getDoctrine()->getManager();
		$data = $request->request->all();
		$disposalItem = new DisposalItem();
		$disposalItemForm = $this->createDisposalItemForm($disposalItem,$disposal);
		$editForm = $this->createEditForm($disposal);
		$disposalItemForm->handleRequest($request);
		if ($disposalItemForm->isValid()) {
			$disposalItem->setDisposal($disposal);
			$serialNo = $data["disposalItem"]["serialNo"];
			if(empty($data['disposalItem']['product'])){
				$product = $em->getRepository('AssetsBundle:Product')->findOneBy(array('serialNo'=>$serialNo));
				$disposalItem->setProduct($product);
				$disposalItem->setItem($product->getItem());
			}else{
				$disposalItem->setItem($disposalItem->getProduct()->getItem());
			}
			$disposalItem->setQuantity(1);
			$em->persist($disposalItem);
			$em->flush();
			$em->getRepository('AssetsBundle:Disposal')->disposalUpdate($disposal);
			return $this->redirect($this->generateUrl('assets_disposal_edit', array('id' => $disposal->getId())));
		}

		return $this->render('AssetsBundle:Disposal:new.html.twig', array(
			'entity' => $disposal,
			'form'   => $editForm->createView(),
			'DisposalItemForm'   => $disposalItemForm->createView(),
		));

	}



	public function disposalItemDeleteAction(Disposal $disposal, DisposalItem $disposalItem)
	{
		$em = $this->getDoctrine()->getManager();

		$em->remove($disposalItem);
		$em->flush();
		$this->getDoctrine()->getRepository('AssetsBundle:DisposalItem')->generateDisposalVendorItem($disposal);
		$em->getRepository('AssetsBundle:Disposal')->DisposalModifyUpdate($disposal);
		$this->get('session')->getFlashBag()->add(
			'error', "Data has been deleted successfully"
		);
		exit;

	}


	public function createStockItemAction(Request $request)
	{

		$em = $this->getDoctrine()->getManager();
		$data = $request->request->all();

		/* @var InventoryConfig $inventory*/

		$inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
		if(!empty($data['masterItem'])){

			$entity = new Item();
			$existMasterItem = $em->getRepository('InventoryBundle:Product')->findOneBy(array('name'=> $data['masterItem']));
			if(empty($existMasterItem)){
				$existMasterItem = $em->getRepository('InventoryBundle:Product')->createNewProduct($inventory,$data['masterItem']);
			}
			$checkData = $this->getDoctrine()->getRepository('InventoryBundle:Item')->checkInstantDuplicateSKU($inventory,$data);
			if($checkData == 0 ) {
				$entity->setInventoryConfig($inventory);
				$entity->setMasterItem($existMasterItem);
				$entity->setName($existMasterItem->getName());
				if($inventory->getIsColor() == 1) {
					$color = $em->getRepository('InventoryBundle:ItemColor')->findOneBy(array('name'=> $data['color'] ));
					$entity->setColor($color);
				}
				if($inventory->getIsSize() == 1) {
					$size = $em->getRepository('InventoryBundle:ItemSize')->findOneBy(array('name'=> $data['size'] ));
					$entity->setSize($size);
				}
				if($inventory->getIsBrand() == 1) {
					$brand = $em->getRepository( 'InventoryBundle:ItemBrand' )->findOneBy(array( 'name' => $data['brand'] ));
					$entity->setBrand($brand);
				}
				if($inventory->getIsVendor() == 1) {
					$vendorName = $data['vendor'];
					$vendor = $em->getRepository('InventoryBundle:Vendor')->findOneBy(array('companyName'=> $vendorName));
					$entity->setVendor($vendor);
				}

				$em->persist($entity);
				$em->flush();
				$msg = "Item has been added successfully";
				$status ='valid';
			}else{
				$status ='invalid';
				$msg = "Item already exist, Please change add another item name";

			}
			return new Response(json_encode(array('status' => $status,'message' => $msg)));

		}
		exit;

	}

}
