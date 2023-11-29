<?php

namespace Appstore\Bundle\HotelBundle\Controller;

use Appstore\Bundle\HotelBundle\Entity\HotelParticular;
use Appstore\Bundle\HotelBundle\Entity\HotelPurchase;
use Appstore\Bundle\HotelBundle\Entity\HotelPurchaseItem;
use Appstore\Bundle\HotelBundle\Form\PurchaseType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vendor controller.
 *
 */
class PurchaseController extends Controller
{

    public function paginate($entities)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            25  /*limit per page*/
        );
        $pagination->setTemplate('SettingToolBundle:Widget:pagination.html.twig');
        return $pagination;
    }


    /**
     * Lists all Vendor entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $this->getDoctrine()->getRepository('HotelBundle:HotelPurchase')->findBy(array('hotelConfig' => $config),array('created'=>'DESC'));
        $pagination = $this->paginate($entities);
        return $this->render('HotelBundle:Purchase:index.html.twig', array(
            'entities' => $pagination,
        ));
    }
    /**
     * Creates a new Vendor entity.
     *
     */
    public function createAction(Request $request)
    {
       
    }


    public function newAction()
    {

        $em = $this->getDoctrine()->getManager();
        $entity = new HotelPurchase();
        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entity->setHotelConfig($config);
        $entity->setCreatedBy($this->getUser());
        $receiveDate = new \DateTime('now');
        $entity->setReceiveDate($receiveDate);
        $transactionMethod = $em->getRepository('SettingToolBundle:TransactionMethod')->find(1);
        $entity->setTransactionMethod($transactionMethod);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('hotel_purchase_edit', array('id' => $entity->getId())));

    }


    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entity = $em->getRepository('HotelBundle:HotelPurchase')->findOneBy(array('hotelConfig' => $config , 'id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity.');
        }
        $editForm = $this->createEditForm($entity);
        $particulars = $em->getRepository('HotelBundle:HotelParticular')->getFindWithParticular($config,$type = array('consumable','stock'));

        return $this->render('HotelBundle:Purchase:new.html.twig', array(
            'entity' => $entity,
            'id' => 'purchase',
            'particulars' => $particulars,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Invoice entity.wq
     *
     * @param Invoice $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(HotelPurchase $entity)
    {
        $globalOption = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new PurchaseType($globalOption), $entity, array(
            'action' => $this->generateUrl('hotel_purchase_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
                'id' => 'purchase',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    public function returnResultData(HotelPurchase $invoice,$msg=''){

        $invoiceParticulars = $this->getDoctrine()->getRepository('HotelBundle:HotelPurchaseItem')->getPurchaseItems($invoice);
        $subTotal = $invoice->getSubTotal() > 0 ? $invoice->getSubTotal() : 0;
        $netTotal = $invoice->getNetTotal() > 0 ? $invoice->getNetTotal() : 0;
        $due = $invoice->getDue() > 0 ? $invoice->getDue() : 0;
        $discount = $invoice->getDiscount() > 0 ? $invoice->getDiscount() : 0;
        $data = array(
            'subTotal' => $subTotal,
            'netTotal' => $netTotal,
            'due' => $due,
            'discount' => $discount,
            'invoiceParticulars' => $invoiceParticulars ,
            'msg' => $msg ,
            'success' => 'success'
        );

        return $data;

    }

    public function particularSearchAction(HotelParticular $particular)
    {
	    $unit = !empty($particular->getUnit() && !empty($particular->getUnit()->getName())) ? $particular->getUnit()->getName():'Unit';
	    return new Response(json_encode(array('particularId'=> $particular->getId() ,'price'=> $particular->getSalesPrice(), 'purchasePrice'=> $particular->getPurchasePrice(), 'quantity'=> 1 , 'unit'=> $unit)));
    }

    public function addParticularAction(Request $request, HotelPurchase $invoice)
    {
        $em = $this->getDoctrine()->getManager();
        $particularId = $request->request->get('particularId');
        $quantity = $request->request->get('quantity');
        $price = $request->request->get('purchasePrice');
        $invoiceItems = array('particularId' => $particularId , 'quantity' => $quantity,'price' => $price);
        $this->getDoctrine()->getRepository('HotelBundle:HotelPurchaseItem')->insertPurchaseItems($invoice, $invoiceItems);
        $invoice = $this->getDoctrine()->getRepository('HotelBundle:HotelPurchase')->updatePurchaseTotalPrice($invoice);
        $result = $this->returnResultData($invoice);
        return new Response(json_encode($result));
        exit;
    }

    public function invoiceParticularDeleteAction(HotelPurchase $invoice, HotelPurchaseItem $particular){

        $em = $this->getDoctrine()->getManager();
        if (!$particular) {
            throw $this->createNotFoundException('Unable to find SalesItem entity.');
        }
        $em->remove($particular);
        $em->flush();
        $invoice = $this->getDoctrine()->getRepository('HotelBundle:HotelPurchase')->updatePurchaseTotalPrice($invoice);
        $result = $this->returnResultData($invoice);
        return new Response(json_encode($result));
        exit;
    }

    /**
     * @Secure(roles="ROLE_BUSINESS")
     */
    public function invoiceDiscountUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $discountType = $request->request->get('discountType');
        $discountCal = (float)$request->request->get('discount');
        $invoice = $request->request->get('invoice');
        $entity = $em->getRepository('HotelBundle:HotelPurchase')->find($invoice);
        $subTotal = $entity->getSubTotal();
        if($discountType == 'flat'){
            $total = ($subTotal  - $discountCal);
            $discount = $discountCal;
        }else{
            $discount = ($subTotal*$discountCal)/100;
            $total = ($subTotal  - $discount);
        }
        $vat = 0;
        if($total > $discount ){
            $entity->setDiscountType($discountType);
            $entity->setDiscountCalculation($discountCal);
            $em->flush();
        }
        $entity = $this->getDoctrine()->getRepository('HotelBundle:HotelPurchase')->updatePurchaseTotalPrice($entity);
        $msg = 'Discount successfully';
        $result = $this->returnResultData($entity,$msg);
        return new Response(json_encode($result));
        exit;
    }

    public function updateAction(Request $request, HotelPurchase $entity)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $data = $request->request->all();
            $entity->setProcess('Done');
            $entity->setDue($entity->getNetTotal() - $entity->getPayment());
            $em->flush();
            return $this->redirect($this->generateUrl('hotel_purchase_show', array('id' => $entity->getId())));
        }
        $particulars = $em->getRepository('HotelBundle:HotelParticular')->getFindWithParticular($entity->getHotelConfig(),$type = array('consumable','stock'));
        return $this->render('HotelBundle:Purchase:new.html.twig', array(
            'entity' => $entity,
            'particulars' => $particulars,
            'form' => $editForm->createView(),
        ));
    }


    /**
     * Finds and displays a Vendor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

	    $config = $this->getUser()->getGlobalOption()->getHotelConfig();
	    $entity = $em->getRepository('HotelBundle:HotelPurchase')->findOneBy(array('hotelConfig' => $config , 'id' => $id));


	    if (!$entity) {
            throw $this->createNotFoundException('Unable to find Vendor entity.');
        }
        return $this->render('HotelBundle:Purchase:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    public function approvedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
		$config = $this->getUser()->getGlobalOption()->getHotelConfig();
	    $purchase = $em->getRepository('HotelBundle:HotelPurchase')->findOneBy(array('hotelConfig' => $config , 'id' => $id));
	    if (!empty($purchase) and empty($purchase->getApprovedBy())) {
            $em = $this->getDoctrine()->getManager();
            $purchase->setProcess('Approved');
            $purchase->setApprovedBy($this->getUser());
            if($purchase->getPayment() === 0 ){
	            $purchase->setTransactionMethod(NULL);
	            $purchase->setAsInvestment(false);
            }
		    $em->flush();
            $this->getDoctrine()->getRepository('HotelBundle:HotelPurchaseItem')->updatePurchaseItemPrice($purchase);
            $this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->getPurchaseUpdateQnt($purchase);
		    if($purchase->getAsInvestment() == 1 and $purchase->getPayment() > 0 ){
			    $journal =  $this->getDoctrine()->getRepository('AccountingBundle:AccountJournal')->insertAccountHotelPurchaseJournal($purchase);
			    $this->getDoctrine()->getRepository('AccountingBundle:AccountCash')->insertAccountCash($journal,'Journal');
			  //  $this->getDoctrine()->getRepository('AccountingBundle:Transaction')->insertAccountJournalTransaction($journal);
		    }
            $accountPurchase = $em->getRepository('AccountingBundle:AccountPurchase')->insertHotelAccountPurchase($purchase);
            $em->getRepository('AccountingBundle:Transaction')->purchaseGlobalTransaction($accountPurchase);
            return new Response('success');
        } else {
            return new Response('failed');
        }
        exit;
    }

    /**
     * Deletes a Vendor entity.
     *
     */
    public function deleteAction($id)
    {

    	$config = $this->getUser()->getGlobalOption()->getHotelConfig();
	    $entity = $this->getDoctrine()->getRepository('HotelBundle:HotelPurchase')->findOneBy(array('hotelConfig' => $config , 'id' => $id));

        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Vendor entity.');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('hotel_purchase'));
    }

	public function reverseAction($id)
	{

		/*
		 * Item Remove Total quantity
		 * Stock Details
		 * Purchase Item
		 * Purchase Vendor Item
		 * Purchase
		 * Account Purchase
		 * Account Journal
		 * Transaction
		 * Delete Journal & Account Purchase
		 */
		$config = $this->getUser()->getGlobalOption()->getHotelConfig();
		$purchase = $this->getDoctrine()->getRepository('HotelBundle:HotelPurchase')->findOneBy(array('hotelConfig' => $config , 'id' => $id));

		set_time_limit(0);
		ignore_user_abort(true);

		$em = $this->getDoctrine()->getManager();
		if($purchase->getAsInvestment() == 1 ) {
			$this->getDoctrine()->getRepository('AccountingBundle:AccountJournal')->removeApprovedHotelPurchaseJournal($purchase);
		}
		$this->getDoctrine()->getRepository('AccountingBundle:AccountPurchase')->accountHotelPurchaseReverse($purchase);
		$purchase->setIsReversed(true);
		$purchase->setProcess('created');
		$purchase->setApprovedBy(NULL);
		$em->flush();
		$this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->getPurchaseUpdateQnt($purchase);
		$template = $this->get('twig')->render('HotelBundle:Purchase:purchaseReverse.html.twig', array(
			'entity' => $purchase,
			'config' => $purchase->getHotelConfig(),
		));
		$this->getDoctrine()->getRepository('HotelBundle:HotelReverse')->purchaseReverse($purchase, $template);
		return $this->redirect($this->generateUrl('hotel_purchase_edit',array('id' => $purchase->getId())));
	}


    /**
     * Status a Page entity.
     *
     */
    public function statusAction(Request $request, $id)
    {

    }

    public function autoSearchAction(Request $request)
    {

    }

    public function searchVendorNameAction($vendor)
    {

    }

}
