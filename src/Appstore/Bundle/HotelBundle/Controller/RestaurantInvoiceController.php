<?php

namespace Appstore\Bundle\HotelBundle\Controller;
use Appstore\Bundle\HotelBundle\Entity\HotelConfig;
use Appstore\Bundle\HotelBundle\Entity\HotelInvoiceTransaction;
use Appstore\Bundle\HotelBundle\Form\InvoiceTransactionType;
use Appstore\Bundle\HotelBundle\Form\RestaurantInvoiceType;
use Knp\Snappy\Pdf;
use Appstore\Bundle\HotelBundle\Entity\HotelInvoice;
use Appstore\Bundle\HotelBundle\Entity\HotelInvoiceParticular;
use Appstore\Bundle\HotelBundle\Entity\HotelParticular;
use Appstore\Bundle\HotelBundle\Form\InvoiceType;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * HotelInvoiceController controller.
 *
 */

class RestaurantInvoiceController extends Controller
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
     * Lists all HotelCategory entities.
     *
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     *
     */

    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $user = $this->getUser();
        $entities = $em->getRepository('HotelBundle:HotelInvoice')->invoiceLists( $user,'restaurant',$data);
        $pagination = $this->paginate($entities);

        return $this->render('HotelBundle:RestaurantInvoice:index.html.twig', array(
            'entities' => $pagination,
            'salesTransactionOverview' => '',
            'previousSalesTransactionOverview' => '',
            'searchForm' => $data,
        ));

    }


    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new HotelInvoice();
        $option = $this->getUser()->getGlobalOption();
        $hotelConfig = $option->getHotelConfig();
        $entity->setCreatedBy($this->getUser());
        $customer = $em->getRepository('DomainUserBundle:Customer')->defaultCustomer($this->getUser()->getGlobalOption());
        $entity->setCustomer($customer);
        $transactionMethod = $em->getRepository('SettingToolBundle:TransactionMethod')->find(1);
        $entity->setTransactionMethod($transactionMethod);
        $entity->setHotelConfig($hotelConfig);
        $entity->setPaymentStatus('Pending');
        $entity->setInvoiceFor('restaurant');
        $entity->setCreatedBy($this->getUser());
        $em->persist($entity);
        $em->flush();
        $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceTransactionSummary')->insertTransactionSummary($entity);
	    return $this->redirect($this->generateUrl('hotel_restaurantinvoice_edit', array('id' => $entity->getId())));

    }

    /**
     * Creates a form to edit a Invoice entity.wq
     *
     * @param HotelInvoice $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(HotelInvoice $entity)
    {
        $globalOption = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new RestaurantInvoiceType($globalOption), $entity, array(
            'action' => $this->generateUrl('hotel_restaurantinvoice_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
                'id' => 'invoiceForm',
                'novalidate' => 'novalidate',
                'enctype' => 'multipart/form-data',

            )
        ));
        return $form;
    }

    /**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entity = $em->getRepository('HotelBundle:HotelInvoice')->findOneBy(array('hotelConfig' => $hotelConfig , 'id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity.');
        }
        $editForm = $this->createEditForm($entity);
        if (in_array($entity->getProcess(), array('Hold'))) {
            return $this->redirect($this->generateUrl('hotel_restaurantinvoice_show', array('id' => $entity->getId())));
        }
	    $date = date('d-m-Y');
	    $bookings = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceParticular')->getCheckinRoom($hotelConfig,$date);
	    $particulars = $em->getRepository('HotelBundle:HotelParticular')->getAvailableRoom($hotelConfig, $type = array('food','stock','pre-production','post-production'),array());
        return $this->render("HotelBundle:RestaurantInvoice:new.html.twig", array(
            'entity' => $entity,
            'bookings' => $bookings,
            'particulars' => $particulars,
            'form' => $editForm->createView(),
        ));
    }

	/**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */

    public function updateAction(Request $request, HotelInvoice $entity)
    {

        $em = $this->getDoctrine()->getManager();
        $globalOption = $this->getUser()->getGlobalOption();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hotel Ivoice entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $data = $request->request->all();
        if ($editForm->isValid()) {

        	if(!empty($data['roomName'])){
        		$hip = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceParticular')->find($data['roomName']);
        		$room = $hip->getParticular();
        		$entity->setRoomName($room);
        		$entity->setCustomer($hip->getHotelInvoice()->getCustomer());
	        }
	        if (empty($entity->getRoomName()) and $entity->getTotal() > 0) {
                $entity->setReceived($entity->getTotal());
                $entity->setDue(0);
                $entity->setPaymentStatus('Paid');
            } else {
                $entity->setPaymentStatus('Due');
                $entity->setDue($entity->getTotal() - $entity->getReceived());
            }
            $amountInWords = $this->get('settong.toolManageRepo')->intToWords($entity->getReceived());
            $entity->setPaymentInWord($amountInWords);
            $em->flush();
            $this->getDoctrine()->getRepository('HotelBundle:HotelParticular')->insertInvoiceProductItem($entity);
	        if(!empty($entity->getRoomName())){
	            $this->getDoctrine()->getRepository('HotelBundle:HotelInvoice')->insertRestaurantTransaction($entity,$hip);
	        }else{
	            $this->getDoctrine()->getRepository('HotelBundle:HotelInvoice')->insertTransaction($entity);
	        }
	        return $this->redirect($this->generateUrl('hotel_restaurantinvoice_new'));
        }
        $hotelConfig = $entity->getHotelConfig();
	    $date = date('d-m-Y');
	    $bookings = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceParticular')->getCheckinRoom($hotelConfig,$date);
	    $particulars = $em->getRepository('HotelBundle:HotelParticular')->getFindWithParticular($hotelConfig, $type = array('production','stock','service','virtual'));
	    return $this->render("HotelBundle:RestaurantInvoice:new.html.twig", array(
            'entity'        => $entity,
            'bookings'   => $bookings,
            'particulars'   => $particulars,
            'form'          => $editForm->createView(),
        ));
    }

	/**
	 * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
	 */

	public function invoiceDiscountUpdateAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$discountType = $request->request->get('discountType');
		$discountCal = (float)$request->request->get('discount');
		$invoice = $request->request->get('invoice');
		$entity = $em->getRepository('HotelBundle:HotelInvoice')->find($invoice);
		$subTotal = $entity->getSubTotal();
		if($discountType == 'flat'){
			$total = ($subTotal - $discountCal);
			$discount = $discountCal;
		}else{
			$discount = ($subTotal * $discountCal)/100;
			$total = ($subTotal  - $discount);
		}
		$vat = 0;
		if($subTotal > $discount ){
			$entity->setDiscountType($discountType);
			$entity->setDiscountCalculation($discountCal);
			$entity->setDiscount(round($discount));
			$entity->setTotal(round($total + $vat));
			$entity->setDue(round($total + $vat));
			$em->flush();
		}

		$msg = 'Discount successfully';
		$result = $this->returnResultData($entity,$msg);
		return new Response(json_encode($result));
		exit;
	}


    /**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */
    public function showAction(HotelInvoice $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
        if ($hotelConfig->getId() == $entity->getHotelConfig()->getId()) {
            return $this->render('HotelBundle:RestaurantInvoice:show.html.twig', array(
                'entity' => $entity,
            ));
        } else {
            return $this->redirect($this->generateUrl('hotel_restaurantinvoice'));
        }

    }

    /**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */

    public function deleteAction(HotelInvoice $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity.');
        }
        $em->remove($entity);
        $em->flush();
        return new Response(json_encode(array('success' => 'success')));
        exit;
    }

    public function returnResultData(HotelInvoice $entity, $msg=''){

        $invoiceParticulars = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceParticular')->getFoodSalesItems($entity);
        $invoiceTransactions = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceTransaction')->getHotelInvoiceTransactionItems($entity);

        $subTotal = $entity->getSubTotal() > 0 ? $entity->getSubTotal() : 0;
        $netTotal = $entity->getTotal() > 0 ? $entity->getTotal() : 0;
        $received = $entity->getReceived() > 0 ? $entity->getReceived() : 0;
        $vat = $entity->getVat() > 0 ? $entity->getVat() : 0;
        $due = $entity->getDue() > 0 ? $entity->getDue() : 0;
        $discount = $entity->getDiscount() > 0 ? $entity->getDiscount() : 0;
        $data = array(
           'subTotal' => $subTotal,
           'netTotal' => $netTotal,
           'payment' => $received ,
           'due' => $due,
           'vat' => $vat,
           'discount' => $discount,
           'invoiceParticulars' => $invoiceParticulars ,
           'invoiceTransactions' => $invoiceTransactions ,
           'msg' => $msg ,
           'process' => $entity->getProcess(),
           'success' => 'success'
       );

       return $data;

    }

	private function createPaymentReceiveForm(HotelInvoice $entity)
	{
		$globalOption = $this->getUser()->getGlobalOption();
		$form = $this->createForm(new InvoiceTransactionType($globalOption), $entity, array(
			'action' => $this->generateUrl('hotel_restaurantinvoice_payment_receive', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form-horizontal',
				'id' => 'invoicePaymentForm',
				'novalidate' => 'novalidate',
				'enctype' => 'multipart/form-data',

			)
		));
		return $form;
	}


	/**
	 * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
	 */

	public function paymentAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
		$entity = $em->getRepository('HotelBundle:HotelInvoice')->findOneBy(array('hotelConfig' => $hotelConfig , 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Invoice entity.');
		}
		$editForm = $this->createPaymentReceiveForm($entity);
		if (in_array($entity->getProcess(), array('Cancel','Check-out'))) {
			return $this->redirect($this->generateUrl('hotel_restaurantinvoice_show', array('id' => $entity->getId())));
		}
		$particulars = $em->getRepository('HotelBundle:HotelParticular')->getFindWithParticular($hotelConfig, $type = array('room','package','post-production','pre-production','stock','service','virtual'));
		return $this->render("HotelBundle:RestaurantInvoice:payment.html.twig", array(
			'entity' => $entity,
			'particulars' => $particulars,
			'form' => $editForm->createView(),
		));
	}

	public function paymentReceiveAction(Request $request, HotelInvoice $entity)
	{
		$em = $this->getDoctrine()->getManager();
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Hotel Ivoice entity.');
		}
		$editForm = $this->createPaymentReceiveForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$data = $request->request->all();
			$em->flush();
			$this->getDoctrine()->getRepository('HotelBundle:HotelInvoice')->insertPaymentTransaction($entity,$data);
		}
		if ((!empty($entity)) ) {
			$result = $this->returnResultData($entity);
			return new Response(json_encode($result));
		} else {
			return new Response(json_encode(array('success'=>'failed')));
		}
		exit;

	}

	public function paymentApproveAction(HotelInvoiceTransaction $entity)
	{
		$em = $this->getDoctrine()->getManager();
		if(!empty($entity) and $entity->getProcess() == 'In-progress' and $entity->getPayment() > 0) {
			$entity->setProcess('Done');
			$em->flush();
			$this->getDoctrine()->getRepository('HotelBundle:HotelInvoice')->updatePaymentReceive($entity->getHotelInvoice());
			if($entity->getPayment() > 0 ){
				$accountInvoice = $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->insertHotelAccountInvoice($entity);
				$this->getDoctrine()->getRepository('AccountingBundle:Transaction')->hotelSalesTransaction($entity, $accountInvoice);
			}
			return new Response('success');
		}else{
			return new Response('failed');
		}
		exit;
	}

	public function checkoutAction(HotelInvoice $entity)
	{
		$em = $this->getDoctrine()->getManager();
		if(!empty($entity) and $entity->getProcess() == 'Check-in') {
			$entity->setProcess('Check-out');
			$em->flush();
			if($entity->getDue() > 0){
				$this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->checkOutHotelAccountInvoice($entity);
			}
			//if(!empty($entity->getHotelConfig()->isNotification() == 1) and  !empty($this->getUser()->getGlobalOption()->getSmsSenderTotal())) {
				$dispatcher = $this->container->get('event_dispatcher');
				$dispatcher->dispatch('setting_tool.post.hotel_book_sms', new \Setting\Bundle\ToolBundle\Event\HotelInvoiceSmsEvent($entity));
			//}
			return new Response('success');
		}else{
			return new Response('failed');
		}
		exit;
	}

	public function paymentDeleteAction(HotelInvoiceTransaction $entity)
	{
		$em = $this->getDoctrine()->getManager();
		$em->remove($entity);
		$em->flush();
		exit;
	}

	public function paymentPrintAction(HotelInvoiceTransaction $entity)
	{

		$em = $this->getDoctrine()->getManager();

		/* @var $hotelConfig HotelConfig */

		$hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
		if ($hotelConfig->getId() == $entity->getHotelConfig()->getId()) {

			if($hotelConfig->isCustomInvoicePrint() == 1){
				$template = $hotelConfig->getGlobalOption()->getSlug();
			}else{
				$template = !empty($hotelConfig->getInvoiceType()) ? $hotelConfig->getInvoiceType():'print';
			}
			$result = $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->customerOutstanding($hotelConfig->getGlobalOption(), $data = array('mobile'=>$entity->getCustomer()->getMobile()));
			$balance = empty($result) ? 0 :$result[0]['customerBalance'];
			return  $this->render("HotelBundle:Print:{$template}.html.twig",
				array(
					'config' => $hotelConfig,
					'entity' => $entity,
					'balance' => $balance,
					'print' => 'print',
				)
			);

		}
	}

	public function particularSearchAction(HotelParticular $particular)
	{
		$unit = !empty($particular->getUnit() && !empty($particular->getUnit()->getName())) ? $particular->getUnit()->getName():'Unit';
		return new Response(json_encode(array('purchasePrice'=> $particular->getPurchasePrice(), 'salesPrice'=> $particular->getSalesPrice(),'quantity'=> 1,'unit' => $unit,'msg'=>'valid')));

	}


	/**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */

    public function addParticularAction(Request $request, HotelInvoice $invoice)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
	    $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceParticular')->insertFoodItem($invoice, $data);
        $invoice = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoice')->updateInvoiceTotalPrice($invoice);
        $msg = 'Particular added successfully';
        $result = $this->returnResultData($invoice,$msg);
        return new Response(json_encode($result));
        exit;

    }

    /**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */

    public function invoiceParticularDeleteAction(HotelInvoice $invoice, HotelInvoiceParticular $particular){

        $em = $this->getDoctrine()->getManager();
        if (!$particular) {
            throw $this->createNotFoundException('Unable to find SalesItem entity.');
        }
        $em->remove($particular);
        $em->flush();
	    $invoice = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoice')->updateInvoiceTotalPrice($invoice);
        $result = $this->returnResultData($invoice,$msg ='');
        return new Response(json_encode($result));
        exit;
    }



    /**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */

    public function invoiceReverseAction(HotelInvoice $invoice)
    {
        $hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entity = $this->getDoctrine()->getRepository('HotelBundle:HotelReverse')->findOneBy(array('hotelConfig' => $hotelConfig, 'hmsInvoice' => $invoice));
        return $this->render('HotelBundle:Reverse:show.html.twig', array(
            'entity' => $entity,
        ));

    }

    /**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */


    public function invoiceReverseShowAction(HotelInvoice $invoice)
    {
        $hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entity = $this->getDoctrine()->getRepository('HotelBundle:HmsReverse')->findOneBy(array('hotelConfig' => $hotelConfig, 'hmsInvoice' => $invoice));
        return $this->render('HotelBundle:Reverse:show.html.twig', array(
            'entity' => $entity,
        ));

    }

    /**
     * @Secure(roles="ROLE_HOTEL_RESTAURANT_INVOICE,ROLE_DOMAIN");
     */

    public function deleteEmptyInvoiceAction()
    {
        $hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoice')->findBy(array('hotelConfig' => $hotelConfig, 'process' => 'Created'));
        $em = $this->getDoctrine()->getManager();
        foreach ($entities as $entity) {
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('hotel_restaurantinvoice'));
    }

    public function invoicePrintPdfAction(HotelInvoice $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
        if ($hotelConfig->getId() == $entity->getHotelConfig()->getId()) {

            /** @var  $invoiceParticularArr */
            $invoiceParticularArr = array();

            /** @var $row HotelInvoiceParticular */
            if (!empty($entity->getInvoiceParticulars())) {
                foreach ($entity->getInvoiceParticulars() as $row):
                    if (!empty($row->getHotelParticular())) {
                        $invoiceParticularArr[$row->getHotelParticular()->getId()] = $row;
                    }
                endforeach;
            }

            $services = $em->getRepository('HotelBundle:HotelService')->findBy(array('hotelConfig' => $hotelConfig, 'serviceShow' => 1, 'status' => 1), array('serviceSorting' => 'ASC'));
            $treatmentSchedule = $em->getRepository('HotelBundle:HotelTreatmentPlan')->findTodaySchedule($hotelConfig);

            if ($hotelConfig->isCustomPrescription() == 1) {
                $template = $hotelConfig->getGlobalOption()->getSlug();
            } else {
                $template = 'print';
            }

            $html = $this->renderView(
                'HotelBundle:Print:dental-care.html.twig', array(
                    'entity' => $entity,
                    'invoiceParticularArr' => $invoiceParticularArr,
                    'services' => $services,
                    'treatmentSchedule' => $treatmentSchedule,
                )
            );

            $wkhtmltopdfPath = 'xvfb-run --server-args="-screen 0, 1280x1024x24" /usr/bin/wkhtmltopdf --use-xserver';
            $snappy = new Pdf($wkhtmltopdfPath);
            $pdf = $snappy->getOutputFromHtml($html);

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="incomePdf.pdf"');
            echo $pdf;

            return new Response('');
        }
    }

    public function autoParticularSearchAction()
    {
        $q = $_REQUEST['term'];
        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceParticular')->searchAutoComplete($config,$q);
        $items = array();
        foreach ($entities as $entity):
            $items[]=array('value' => $entity['id']);
        endforeach;
        return new JsonResponse($items);

    }

    public function invoiceItemUpdateAction(Request $request)
    {

        $data = $request->request->all();
        $invoice = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoiceParticular')->updateInvoiceItems($data);
        $invoice = $this->getDoctrine()->getRepository('HotelBundle:HotelInvoice')->updateInvoiceTotalPrice($invoice);
        $msg = 'Particular added successfully';
        $result = $this->returnResultData($invoice,$msg);
        return new Response(json_encode($result));
        exit;
    }

    public function getBarcode($value)
    {
        $barcode = new BarcodeGenerator();
        $barcode->setText($value);
        $barcode->setType(BarcodeGenerator::Code39Extended);
        $barcode->setScale(1);
        $barcode->setThickness(25);
        $barcode->setFontSize(8);
        $code = $barcode->generate();
        $data = '';
        $data .= '<img src="data:image/png;base64,'.$code .'" />';
        return $data;
    }

    public function invoicePrintAction(HotelInvoice $entity)
    {

        $em = $this->getDoctrine()->getManager();

        /* @var $hotelConfig HotelConfig */

        $hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
        if ($hotelConfig->getId() == $entity->getHotelConfig()->getId()) {

            if($hotelConfig->isCustomInvoicePrint() == 1){
                $template = $hotelConfig->getGlobalOption()->getSlug();
	        }else{
                $template = !empty($hotelConfig->getInvoiceType()) ? $hotelConfig->getInvoiceType():'print';
            }
	        $amountInWords = $this->get('settong.toolManageRepo')->intToWords($entity->getReceived());
	        $result = $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->customerOutstanding($hotelConfig->getGlobalOption(), $data = array('mobile'=>$entity->getCustomer()->getMobile()));
	        $balance = empty($result) ? 0 :$result[0]['customerBalance'];
            return  $this->render("HotelBundle:Print:{$template}.html.twig",
                array(
                    'config' => $hotelConfig,
                    'entity' => $entity,
                    'balance' => $balance,
                    'amountInWords' => $amountInWords,
                    'print' => 'print',
                )
            );

        }

    }

	public function invoiceChalanAction(HotelInvoice $entity)
	{

		$em = $this->getDoctrine()->getManager();

		/* @var $hotelConfig HotelConfig */

		$hotelConfig = $this->getUser()->getGlobalOption()->getHotelConfig();
		if ($hotelConfig->getId() == $entity->getHotelConfig()->getId()) {

			if($hotelConfig->isCustomInvoicePrint() == 1){
				$template = $hotelConfig->getGlobalOption()->getSlug();
			}else{
				$template = !empty($hotelConfig->getInvoiceType()) ? $hotelConfig->getInvoiceType():'print';
			}
			$result = $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->customerOutstanding($hotelConfig->getGlobalOption(), $data = array('mobile'=>$entity->getCustomer()->getMobile()));
			$balance = empty($result) ? 0 :$result[0]['customerBalance'];
			return  $this->render("HotelBundle:Print:{$template}.html.twig",
				array(
					'config' => $hotelConfig,
					'entity' => $entity,
					'balance' => $balance,
					'print' => 'chalan',
				)
			);

		}

	}

}

