<?php

namespace Appstore\Bundle\ProcurementBundle\Controller;

use Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrderItem;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisition;
use Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisitionItem;
use Appstore\Bundle\ProcurementBundle\Form\PurchaseOrderType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;

/**
 * PurchaseOrder controller.
 *
 */
class RequisitionController extends Controller
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
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $entities = $em->getRepository('ProcurementBundle:PurchaseOrder')->findWithSearch($inventory,$data);
        $purchaseOverview = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrder')->purchaseOverview($inventory,$data);
        $pagination = $this->paginate($entities);
        return $this->render('ProcurementBundle:PurchaseOrder:index.html.twig', array(
            'entities' => $pagination,
            'purchaseOverview' => $purchaseOverview,
            'searchForm' => $data
        ));
    }


    /**
     * Displays a form to create a new PurchaseOrder entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new PurchaseOrder();
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $entity->setInventoryConfig($inventory);
        $entity->setBranch($this->getUser()->getProfile()->getBranches());
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('pro_purchaseorder_edit', array('id' => $entity->getId())));

    }

    /**
     * Finds and displays a Purchase entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ProcurementBundle:PurchaseOrder')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Purchase entity.');
        }
        return $this->render('ProcurementBundle:PurchaseOrder:show.html.twig', array(
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

        $purchase = $em->getRepository('ProcurementBundle:PurchaseOrder')->find($id);

        if (!$purchase) {
            throw $this->createNotFoundException('Unable to find Purchase entity.');
        }
        $purchaseItem = new PurchaseOrderItem();
        $editForm = $this->createEditForm($purchase);

        return $this->render('ProcurementBundle:PurchaseOrder:new.html.twig', array(
            'entity'      => $purchase,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Purchase entity.
    *
    * @param Purchase $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PurchaseOrder $entity)
    {
        $inventoryConfig =  $this->getUser()->getGlobalOption()->getInventoryConfig();
        $form = $this->createForm(new PurchaseOrderType($inventoryConfig), $entity, array(
            'action' => $this->generateUrl('pro_purchaseorder_update', array('id' => $entity->getId())),
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
     * Edits an existing Purchase entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = $request->request->all();
	    $file = $request->files->all();
	    $entity = $em->getRepository('ProcurementBundle:PurchaseOrder')->find($id);
	    $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrderItem')->updatePurchaseOrderItem($data);
	    $this->getDoctrine()->getRepository('ProcurementBundle:ProcurementAttachFile')->insertPoFile($entity,$data,$file);
	    $em->getRepository('ProcurementBundle:PurchaseOrder')->purchaseSimpleUpdate($entity);
	    $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
        	$entity->setGrandTotal($entity->getTotalAmount() - ($entity->getDiscount() + $entity->getVatAmount()));
        	$entity->setDueAmount($entity->getGrandTotal() - $entity->getAdvanceAmount());
        	$em->flush();
	        return $this->redirect($this->generateUrl('pro_purchaseorder_show',array('id' => $entity->getId())));
        }
        return $this->render('ProcurementBundle:PurchaseOrder:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),

        ));
    }

    public function approveAction(PurchaseOrder $purchase)
    {
		$approve = $_REQUEST['approve'];
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        if($approve == 'checked'){
        	$purchase->setCheckedBy($this->getUser());
        }elseif($approve == 'approved'){
	        $purchase->setApprovedBy($this->getUser());
	        $purchase->setProcess('Receive Issue');
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
    public function deleteAction(PurchaseOrder $purchase)
    {
        if($purchase){
            $em = $this->getDoctrine()->getManager();
            $em->remove($purchase);
            $em->flush();
            return new Response('success');
        }else{
            return new Response('failed');
        }

        exit;
    }


    public function  updatePurchaseStatus(PurchaseOrder $entity,$process){

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
            $item = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrder')->searchAutoComplete($inventory,$item);
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
     * Creates a new PurchaseOrder entity.
     *
     */
    public function createPurchaseOrderItemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
	    $em = $this->getDoctrine()->getManager();
	    $entity = new PurchaseOrder();
	    $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
	    $entity->setInventoryConfig($inventory);
	    $em->persist($entity);
	    $em->flush();
	    $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseRequisitionItem')->insertPoItem($entity,$data);
	    $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrderItem')->insertPoItem($entity,$data);
	    $em->getRepository('ProcurementBundle:PurchaseOrder')->purchaseSimpleUpdate($entity);
	    return $this->redirect($this->generateUrl('pro_purchaseorder_edit', array('id' => $entity->getId())));


    }

	public function purchaseItemUpdateAction($purchase, PurchaseOrderItem $item )
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
	    $em = $this->getDoctrine()->getManager();
	    if(!empty($data['quantity']) and !empty($data['purchasePrice'])) {
		    $item->setPurchasePrice( $data['purchasePrice'] );
		    $item->setQuantity( $data['quantity'] );
		    $item->setPurchaseSubTotal( $data['quantity'] * $data['purchasePrice'] );
		    $em->flush();
		    $this->getDoctrine()->getRepository( 'ProcurementBundle:PurchaseOrder' )->purchaseSimpleUpdate( $item->getPurchase() );
		    return new Response('success');
	     }else{
		    return new Response('invalid');
	     }
	    exit;
    }



    public function purchaseItemDeleteAction(PurchaseRequisition $purchase, PurchaseRequisitionItem $purchaseItem)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($purchaseItem);
        $em->flush();
        $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrderItem')->generatePurchaseVendorItem($purchase);
        $em->getRepository('ProcurementBundle:PurchaseOrder')->purchaseModifyUpdate($purchase);
        $this->get('session')->getFlashBag()->add(
            'error', "Data has been deleted successfully"
        );
        exit;

    }

    public function poIssueAction(Request $request)
    {
		$data = $_REQUEST;
    	$entities = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrderItem')->listPoIssueItem($data);
	    $pagination = $this->paginate($entities);
    	return $this->render('ProcurementBundle:PurchaseOrder:po-item.html.twig', array(
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
				$this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrderItem')->prProcess('rejected',$data);
			}
			return $this->redirect($this->generateUrl('pro_purchaseorder_poissue'));
		}else{
			$this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrderItem')->prProcess('issued',$data);
			$entities = $this->getDoctrine()->getRepository('ProcurementBundle:PurchaseOrderItem')->getPoItem($data);
			return $this->render('@Procurement/PurchaseOrder/po-generate.html.twig', array(
				'entities'      => $entities
			));
		}

	}




}
