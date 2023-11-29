<?php

namespace Appstore\Bundle\ProcurementBundle\Controller;

use Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisition;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisitionItem;
use Appstore\Bundle\ProcurementBundle\Form\PurchaseRequisitionItemType;
use Appstore\Bundle\ProcurementBundle\Form\PurchaseRequisitionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PurchaseRequisition controller.
 *
 */
class PurchaseRequisitionController extends Controller
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
     * Lists all PurchaseOrder entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $inventory = $this->getUser()->getGlobalOption()->getProcurementConfig();
        $entities = $em->getRepository('ProcurementBundle:PurchaseRequisition')->findWithSearch($inventory->getId(),$data);
        $purchaseOverview = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisition')->purchaseOverview($inventory,$data);
        $pagination = $this->paginate($entities);
        return $this->render('ProcurementBundle:PurchaseRequisition:index.html.twig', array(
            'entities' => $pagination,
            'purchaseOverview' => $purchaseOverview,
            'searchForm' => $data
        ));
    }


    /**
     * Displays a form to create a new PurchaseRequisition entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new PurchaseRequisition();
        $inventory = $this->getUser()->getGlobalOption()->getProcurementConfig();
        $entity->setConfig($inventory);
        $entity->upload();
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('pro_purchaserequisition_edit', array('id' => $entity->getId())));

    }

    /**
     * Finds and displays a Purchase entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ProcurementBundle:PurchaseRequisition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Purchase entity.');
        }
        return $this->render('ProcurementBundle:PurchaseRequisition:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Purchase entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $purchase = $em->getRepository('ProcurementBundle:PurchaseRequisition')->find($id);
        if (!$purchase) {
            throw $this->createNotFoundException('Unable to find Purchase entity.');
        }
        $purchaseItem = new PurchaseRequisitionItem();
        $purchaseItemForm = $this->createPurchaseItemForm($purchaseItem,$purchase);
        $editForm = $this->createEditForm($purchase);
        return $this->render('ProcurementBundle:PurchaseRequisition:new.html.twig', array(
            'entity'      => $purchase,
            'purchaseItemForm'   => $purchaseItemForm->createView(),
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Purchase entity.
    *
    * @param PurchaseRequisition $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PurchaseRequisition $entity)
    {
        $inventoryConfig =  $this->getUser()->getGlobalOption();
        $form = $this->createForm(new PurchaseRequisitionType($inventoryConfig), $entity, array(
            'action' => $this->generateUrl('pro_purchaserequisition_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'id' => 'purchaseForm',
                'class' => 'horizontal-form purchase',
                'novalidate' => 'novalidate',
            )

        ));
        return $form;
    }

    /**
    * Creates a form to edit a Purchase entity.
    *
    * @param PurchaseRequisition $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createPurchaseItemForm(PurchaseRequisitionItem $purchaseItem , PurchaseRequisition $entity)
    {
        $inventoryConfig =  $this->getUser()->getGlobalOption()->getAssetsConfig();
        $form = $this->createForm(new PurchaseRequisitionItemType($inventoryConfig), $purchaseItem, array(
            'action' => $this->generateUrl('pro_purchaserequisition_create', array('purchase' => $entity->getId())),
            'method' => 'POST',
            'attr' => array(
                'id' => 'purchaseItemForm',
                'class' => 'horizontal-form purchase',
                'novalidate' => 'novalidate',
            )

        ));
        return $form;
    }

    /**
     * Edits an existing Purchase entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ProcurementBundle:PurchaseRequisition')->find($id);
        $editForm = $this->createEditForm($entity);
        $purchaseItem = new PurchaseRequisitionItem();
        $purchaseItemForm = $this->createPurchaseItemForm($purchaseItem,$entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
	        $entity->upload();
            $em->flush();
	        return $this->redirect($this->generateUrl('pro_purchaserequisition'));
        }
        return $this->render('ProcurementBundle:PurchaseRequisition:new.html.twig', array(
            'entity'      => $entity,
            'purchaseItemForm'   => $purchaseItemForm->createView(),
            'form'   => $editForm->createView(),

        ));
    }

    public function approveAction(PurchaseRequisition $purchase)
    {
		$approve = $_REQUEST['approve'];
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        if($approve == 'checked'){
        	$purchase->setCheckedBy($this->getUser());
        }elseif($approve == 'approved'){
	        $purchase->setApprovedBy($this->getUser());
	        $purchase->setProcess('PO Issue');
        }
        $purchase->setApprove($approve);
        $em->persist($purchase);
        $em->flush();
        return new Response(json_encode(array('success'=>'success')));

    }

    /**
     * Deletes a Purchase entity.
     *
     */
    public function deleteAction(PurchaseRequisition $purchase)
    {
        if($purchase){
            $em = $this->getDoctrine()->getManager();
            $em->remove($purchase);
            $em->flush();
            return new Response('success');
        }else{
            return new Response('failed');
        }
    }


    public function  updatePurchaseStatus(PurchaseRequisition $entity,$process){

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
            $item = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisition')->searchAutoComplete($inventory,$item);
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
     * Displays a form to edit an existing Purchase entity.
     *
     */
    public function editApproveAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ProcurementBundle:PurchaseRequisition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Purchase entity.');
        }
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $em->getRepository('ProcurementBundle:PurchaseRequisition')->getSumPurchase($this->getUser(),$inventory);
        $editForm = $this->createEditApproveForm($entity);

        return $this->render('ProcurementBundle:PurchaseRequisition:editApprove.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }


    /**
     * Creates a new PurchaseRequisition entity.
     *
     */
    public function createPurchaseItemAction(Request $request, PurchaseRequisition $purchase)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $purchaseItem = new PurchaseRequisitionItem();
        $purchaseItemForm = $this->createPurchaseItemForm($purchaseItem,$purchase);
        $purchaseItemForm->handleRequest($request);
        $purchaseItem->setRequisition($purchase);
        $purchaseItem->setPrice($purchaseItem->getItem()->getPrice());
        $purchaseItem->setPurchasePrice($purchaseItem->getItem()->getPrice());
        $purchaseSubTotal = ($purchaseItem->getQuantity() * $purchaseItem->getPrice());
        $purchaseItem->setPurchaseSubTotal($purchaseSubTotal);
        $em->persist($purchaseItem);
        $em->flush();
        $em->getRepository('ProcurementBundle:PurchaseRequisition')->purchaseSimpleUpdate($purchase);
        $em->getRepository(PurchaseRequisitionItem::class)->updateStockProgressItem($purchaseItem);
        $records = $this->returnResultData($purchaseItem->getRequisition());
        return new Response($records);

    }



    /**
     * Creates a new PurchaseRequisition entity.
     *
     */
    public function updatePurchaseItemAction(Request $request,PurchaseRequisitionItem $purchaseItem)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $quantity =  empty($data['quantity']) ? 0 : $data['quantity'];
        $price = empty($data['price']) ? 0 : $data['price'];
        $description = empty($data['description']) ? 0 : $data['description'];
        $purchaseItem->setName($description);
        $purchaseItem->setPrice($price);
        $purchaseItem->setPurchasePrice($price);
        $purchaseItem->setQuantity($quantity);
        $purchaseSubTotal = ($purchaseItem->getQuantity() * $purchaseItem->getPrice());
        $purchaseItem->setPurchaseSubTotal($purchaseSubTotal);
        $em->persist($purchaseItem);
        $em->flush();
        $em->getRepository(PurchaseRequisitionItem::class)->updateStockProgressItem($purchaseItem);
        $em->getRepository('ProcurementBundle:PurchaseRequisition')->purchaseSimpleUpdate($purchaseItem->getRequisition());
        $records = $this->returnResultData($purchaseItem->getRequisition());
        return new Response($records);
    }

    public function returnResultData(PurchaseRequisition $entity){

        return $invoiceParticulars = $this->renderView('ProcurementBundle:PurchaseRequisition:requisition-item.html.twig', array(
            'entity' => $entity,
        ));

    }


    public function purchaseItemDeleteAction(PurchaseRequisition $purchase, PurchaseRequisitionItem $purchaseItem)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($purchaseItem);
        $em->flush();
        $em->getRepository('ProcurementBundle:PurchaseRequisition')->purchaseModifyUpdate($purchase);
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
            $existMasterItem = $em->getRepository('ProcurementBundle:Product')->findOneBy(array('name'=> $data['masterItem']));
            if(empty($existMasterItem)){
                $existMasterItem = $em->getRepository('ProcurementBundle:Product')->createNewProduct($inventory,$data['masterItem']);
            }
            $checkData = $this->getDoctrine()->getRepository('ProcurementBundle:Item')->checkInstantDuplicateSKU($inventory,$data);
            if($checkData == 0 ) {
                $entity->setInventoryConfig($inventory);
                $entity->setMasterItem($existMasterItem);
                $entity->setName($existMasterItem->getName());
                if($inventory->getIsColor() == 1) {
                    $color = $em->getRepository('ProcurementBundle:ItemColor')->findOneBy(array('name'=> $data['color'] ));
                    $entity->setColor($color);
                }
                if($inventory->getIsSize() == 1) {
                    $size = $em->getRepository('ProcurementBundle:ItemSize')->findOneBy(array('name'=> $data['size'] ));
                    $entity->setSize($size);
                }
                if($inventory->getIsBrand() == 1) {
                    $brand = $em->getRepository( 'ProcurementBundle:ItemBrand' )->findOneBy(array( 'name' => $data['brand'] ));
                    $entity->setBrand($brand);
                }
                if($inventory->getIsVendor() == 1) {
                    $vendorName = $data['vendor'];
                    $vendor = $em->getRepository('ProcurementBundle:Vendor')->findOneBy(array('companyName'=> $vendorName));
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

    public function poIssueAction(Request $request)
    {
		$data = $_REQUEST;
	 	$entities = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisitionItem')->listPoIssueItem($data);
	    $pagination = $this->paginate($entities);
    	return $this->render('ProcurementBundle:PurchaseRequisition:po-item.html.twig', array(
		    'selected' => explode(',', $request->cookies->get('barcodes', '')),
		    'entities' => $pagination,
	    ));
    }

    public function purchaseIssueAction(Request $request)
    {
        $data = $_REQUEST;
        $entities = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisitionItem')->listPoIssueItem($data);
        $pagination = $this->paginate($entities);
        return $this->render('ProcurementBundle:PurchaseRequisition:purchase-item.html.twig', array(
            'selected' => explode(',', $request->cookies->get('barcodes', '')),
            'entities' => $pagination,
        ));
    }

    public function stockIssueAction(Request $request)
    {
        $data = $_REQUEST;
        $entities = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisitionItem')->listPoIssueItem($data);
        $pagination = $this->paginate($entities);
        return $this->render('ProcurementBundle:PurchaseRequisition:issue-item.html.twig', array(
            'selected' => explode(',', $request->cookies->get('barcodes', '')),
            'entities' => $pagination,
        ));
    }

	public function prItemProcessAction(Request $request,$process)
	{
		$data = explode(',',$request->cookies->get('barcodes'));
		$em = $this->getDoctrine()->getManager();
		if(is_null($data) or $process == 'rejected' ) {
			if($process == 'rejected'){
				$this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisitionItem')->prProcess('rejected',$data);
			}
			return $this->redirect($this->generateUrl('pro_purchaserequisition_poissue'));
		}else{
			$this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisitionItem')->prProcess('issued',$data);
			$entities = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisitionItem')->getPoItem($data);
			return $this->render('@Procurement/PurchaseRequisition/po-generate.html.twig', array(
				'entities'      => $entities
			));
		}
	}

    /**
     * Deletes a PurchaseItem entity.
     *
     */
    public function modeUpdateAction(PurchaseRequisitionItem $purchaseItem, $mode)
    {
        $em = $this->getDoctrine()->getManager();
        $purchaseItem->setMode($mode);
        $em->persist($purchaseItem);
        $em->flush();
        return new Response('success');
    }

}
