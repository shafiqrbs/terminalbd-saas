<?php

namespace Appstore\Bundle\EcommerceBundle\Controller;

use Appstore\Bundle\AccountingBundle\Entity\AccountSales;
use Appstore\Bundle\DomainUserBundle\Entity\Customer;
use Appstore\Bundle\EcommerceBundle\Entity\CourierService;
use Appstore\Bundle\EcommerceBundle\Entity\Item;
use Appstore\Bundle\EcommerceBundle\Entity\Order;
use Appstore\Bundle\EcommerceBundle\Entity\OrderItem;
use Appstore\Bundle\EcommerceBundle\Entity\OrderPayment;
use Appstore\Bundle\EcommerceBundle\Form\MedicineItemType;
use Appstore\Bundle\EcommerceBundle\Form\OrderPaymentType;
use Appstore\Bundle\EcommerceBundle\Form\OrderType;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Knp\Snappy\Pdf;
use Setting\Bundle\ToolBundle\Entity\TransactionMethod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Order controller.
 *
 */
class OrderController extends Controller
{

    public function paginate($entities)
    {

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            25  /*limit per page*/
        );
        return $pagination;
    }


    /**
     * Lists all Item entities.
     *
     * @Secure(roles = "ROLE_DOMAIN_ECOMMERCE_ORDER,ROLE_DOMAIN")
     */

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $globalOption = $this->getUser()->getGlobalOption();
        $data = $_REQUEST;
        $entities = $em->getRepository('EcommerceBundle:Order')->findWithSearch($globalOption->getId(),$data);
        $pagination = $this->paginate($entities);
        $couriers = $this->getDoctrine()->getRepository(CourierService::class)->findBy(array('ecommerceConfig'=>$globalOption->geteCommerceConfig(),'status'=>1));
        return $this->render('EcommerceBundle:Order:index.html.twig', array(
            'entities' => $pagination,
            'couriers' => $couriers,
        ));
    }

    /**
     * Lists all Item entities.
     *
     * @Secure(roles = "ROLE_DOMAIN_ECOMMERCE_ORDER,ROLE_DOMAIN")
     */

    public function archiveAction()
    {
        $em = $this->getDoctrine()->getManager();
        $globalOption = $this->getUser()->getGlobalOption();
        $data = $_REQUEST;
        $entities = $em->getRepository('EcommerceBundle:Order')->findWithArchive($globalOption->getId(),$data);
        $pagination = $this->paginate($entities);
        $couriers = $this->getDoctrine()->getRepository(CourierService::class)->findBy(array('ecommerceConfig'=>$globalOption->geteCommerceConfig(),'status'=>1));
        return $this->render('EcommerceBundle:Order:archive.html.twig', array(
            'entities' => $pagination,
            'couriers' => $couriers,
        ));
    }

    /**
     * Lists all Item entities.
     *
     * @Secure(roles = "ROLE_DOMAIN_ECOMMERCE_ORDER,ROLE_DOMAIN")
     */

    public function newAction()
    {

        $em = $this->getDoctrine()->getManager();
        $entity = new Order();
        $config = $this->getUser()->getGlobalOption();
        $entity->setEcommerceConfig($config->getEcommerceConfig());
        $entity->setGlobalOption($config);
        $entity->setCreatedBy($this->getUser());
        $customer = $em->getRepository('DomainUserBundle:Customer')->defaultCustomer($this->getUser()->getGlobalOption());
        $entity->setCustomer($customer);
        $entity->setCashOnDelivery(true);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('customer_order_edit', array('id' => $entity->getId())));

    }

    /**
     * Lists all Item entities.
     *
     * @Secure(roles = "ROLE_DOMAIN_ECOMMERCE_ORDER,ROLE_DOMAIN")
     */

    public function editAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getEcommerceConfig();
        $order = $em->getRepository('EcommerceBundle:Order')->findOneBy(array('ecommerceConfig'=>$config,'id'=>$id));
        $paymentEntity = new  OrderPayment();
        $orderForm = $this->createEditForm($order);
        $payment = $this->createEditPaymentForm($paymentEntity,$order);

        if( $order->getGlobalOption()->getDomainType() == 'medicine' ) {
            $theme = 'medicine';
        }else{
            $theme = 'ecommerce';
        }
        $config = $order->getEcommerceConfig();
        $salesItemForm = $this->createMedicineSalesItemForm(new OrderItem(),$order);
        $locations = $this->getDoctrine()->getRepository('EcommerceBundle:DeliveryLocation')->findBy(array('ecommerceConfig' => $config,'status'=>1),array('name'=>'ASC'));
        $timePeriods = $this->getDoctrine()->getRepository('EcommerceBundle:TimePeriod')->findBy(array('ecommerceConfig' => $config,'status'=>1),array('name'=>'ASC'));
        $couriers = $this->getDoctrine()->getRepository('EcommerceBundle:CourierService')->findBy(array('ecommerceConfig' => $config,'status'=>1),array('name'=>'ASC'));
        return $this->render("EcommerceBundle:Order/{$theme}:new.html.twig", array(
            'globalOption' => $order->getGlobalOption(),
            'entity'                => $order,
            'couriers'              => $couriers,
            'locations'             => $locations,
            'timePeriods'           => $timePeriods,
            'orderForm'             => $orderForm->createView(),
            'salesItem'             => $salesItemForm->createView(),
            'paymentForm'           => $payment->createView(),
        ));

    }


    /**
     * Finds and displays a Order entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EcommerceBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        if( $entity->getGlobalOption()->getDomainType() == 'medicine' ) {
            $theme = 'medicine';
        }else{
            $theme = 'ecommerce';
        }
        return $this->render("EcommerceBundle:Order/{$theme}:show.html.twig", array(
            'globalOption' => $entity->getGlobalOption(),
            'entity'      => $entity,
        ));

    }


    /**
    * Creates a form to edit a Order entity.
    *
    * @param Order $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Order $entity)
    {
        $globalOption = $entity->getGlobalOption();
        $location = $this->getDoctrine()->getRepository('SettingLocationBundle:Location');
        $form = $this->createForm(new OrderType($globalOption,$location), $entity, array(
            'action' => $this->generateUrl('customer_order_confirm', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'id' => 'orderProcess',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Creates a form to edit a PreOrder entity.
     *
     * @param Order $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditPaymentForm(OrderPayment $entity,Order $order)
    {
        $location = $this->getDoctrine()->getRepository('SettingLocationBundle:Location');
        $form = $this->createForm(new OrderPaymentType($order->getGlobalOption(),$location), $entity, array(
            'action' => $this->generateUrl('customer_order_ajax_payment', array('id' => $order->getId())),
            'method' => 'POST',
            'attr' => array(
                'id' => 'ecommerce-payment',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Lists all Item entities.
     *
     * @Secure(roles = "ROLE_DOMAIN_ECOMMERCE_ORDER,ROLE_DOMAIN")
     */

    public function paymentAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('EcommerceBundle:Order')->find($id);
        $paymentEntity = new  OrderPayment();
        $orderForm = $this->createEditForm($order);
        $payment = $this->createEditPaymentForm($paymentEntity,$order);

        if( $order->getGlobalOption()->getDomainType() == 'medicine' ) {
            $theme = 'medicine';
        }else{
            $theme = 'ecommerce';
        }
        $salesItemForm = $this->createMedicineSalesItemForm(new OrderItem(),$order);
        return $this->render("EcommerceBundle:Order/{$theme}:payment.html.twig", array(
            'globalOption' => $order->getGlobalOption(),
            'entity'                => $order,
            'orderForm'             => $orderForm->createView(),
            'salesItem'             => $salesItemForm->createView(),
            'paymentForm'           => $payment->createView(),
        ));

    }


    private function createMedicineSalesItemForm(OrderItem $orderItem,Order $order )
    {

        $form = $this->createForm(new MedicineItemType(), $orderItem, array(
            'action' => $this->generateUrl('customer_order_item',array('id' => $order->getId())),
            'method' => 'POST',
            'attr' => array(
                'class' => 'form-horizontal',
                'id' => 'orderItem',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Displays a form to edit an existing OrderItem entity.
     *
     */
    public function orderItemAddAction(Order $entity , Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $orderItem = new OrderItem();
        $data = $request->request->all()['orderItem'];
        $stockId = $data['itemName'];
        $product = $this->getDoctrine()->getRepository('EcommerceBundle:Item')->find($stockId);
        $unit = ($product->getProductUnit()) ? $product->getProductUnit()->getName() :'';
        $brand = ($product->getBrand()) ? $product->getBrand()->getName() :'';
        $category = ($product->getCategory()) ? $product->getCategory()->getName() :'';
        $size = !empty($product->getSize()) ? $product->getSize()->getName(): '';
        $orderItem->setOrder($entity);
        $orderItem->setItem($product);
        $orderItem->setItemName($product->getWebName());
        $orderItem->setBrandName($brand);
        $orderItem->setCategoryName($category);
        $orderItem->setUnitName($unit);
        $orderItem->setQuantity($data['quantity']);
        $orderItem->setPrice($data['price']);
        $orderItem->setSubTotal($data['price'] * $data['quantity']);
        $em->persist($orderItem);
        $em->flush();
        $em->getRepository('EcommerceBundle:Order')->updateOrder($entity);
        $array = $this->renderCatrtItem($entity);
        return new Response($array);

    }

    public function renderCatrtItem(Order $entity)
    {
        if( $entity->getGlobalOption()->getDomainType() == 'medicine' ) {
            $theme = 'medicine';
        }else{
            $theme = 'ecommerce';
        }
        $html =  $this->renderView("EcommerceBundle:Order/ecommerce:ajaxOrderItem.html.twig", array(
            'globalOption' => $entity->getGlobalOption(),
            'entity' => $entity,
        ));
        return $html;

    }


    public function stockDetailsAction(Item $stock)
    {
        $unit = ($stock->getProductUnit()) ? $stock->getProductUnit()->getName() : '';
        return new Response(json_encode(array('unit' => $unit , 'price' => $stock->getSalesPrice())));
    }

    public function autoSearchAction(Request $request)
    {
        $item = trim($_REQUEST['q']);
        if ($item) {
            $inventory = $this->getUser()->getGlobalOption()->getEcommerceConfig();
            $item = $this->getDoctrine()->getRepository('EcommerceBundle:Item')->searchWebStock($item,$inventory);
        }
        return new JsonResponse($item);
    }

    public function autoCustomerSearchAction(Request $request)
    {
        $q = $_REQUEST['term'];
        $option = $this->getUser()->getGlobalOption();
        $entities = $this->getDoctrine()->getRepository('EcommerceBundle:Order')->searchEcommerceCustomer($option,$q);
        $items = array();
        foreach ($entities as $entity):
            $items[] = ['id' => $entity['id'],'value' => $entity['text']];
        endforeach;
        return new JsonResponse($items);
    }

    public function orderUpdateCustomerAction()
    {
        $data = $_REQUEST;
        $order = $data['order'];
        $userId = $data['customer'];
        $em = $this->getDoctrine()->getManager();
        /* @var $entity Order */
        $entity = $this->getDoctrine()->getRepository("EcommerceBundle:Order")->find($order);
        $user = $this->getDoctrine()->getRepository("UserBundle:User")->find($userId);
        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(array('user'=>$user->getId()));

        $entity->setCustomerName($user->getProfile()->getName());
        $entity->setCustomerMobile($user->getProfile()->getMobile());
        $entity->setAddress($user->getProfile()->getAddress());
        if(empty($customer)){
            $customer = $this->getDoctrine()->getRepository(Customer::class)->insertEcommerceCustomer($user->getProfile());
        }
        $entity->setCustomer($customer);
        $em->flush();
        return new Response('success');
    }

    public function archiveProcessAction(Order $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $entity->setIsArchive(1);
        $entity->setApprovedBy($this->getUser());
        $em->flush();
        $this->getDoctrine()->getRepository(AccountSales::class)->insertEcommerceSales($entity);
        return new Response('success');
    }

    public function inlineOrderUpdateAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EcommerceBundle:Order')->find($data['pk']);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PurchaseItem entity.');
        }
        $setName = 'set'.$data['name'];
        $entity->$setName($data['value']);
        $em->persist($entity);
        $em->flush();
        if($entity->getShippingCharge() > 0 ){
            $em->getRepository('EcommerceBundle:Order')->updateOrder($entity);
        }
        return new Response('success');

    }

    public function paymentAjaxUpdateAction(Request $request ,Order $order)
    {

        $data =$_REQUEST;
        $shippingCharge = $data['shippingCharge'];
        $discount = $data['discount'];
        $em = $this->getDoctrine()->getManager();
        $order->setShippingCharge($shippingCharge);
        $order->setDiscount($discount);
        $total = ($order->getSubTotal() + $order->getVat() + $order->getShippingCharge() - $order->getDiscount());
        $order->setTotal($total);
        $em->persist($order);
        $em->flush();
        $this->getDoctrine()->getRepository('EcommerceBundle:Order')->updateOrderPayment($order);
        $data = array('discount'=>$order->getDiscount(),'shippingCharge'=>$order->getShippingCharge(),'vat' => $order->getVat(),'total' => $order->getTotal(),'receive' => $order->getReceive(),'due' => ($order->getTotal() - $order->getReceive()));
        return new Response ( json_encode($data));
    }

    public function paymentProcessAction(Request $request ,Order $order)
    {
        $payment = $request->request->all();
        $data = $payment['ecommerce_payment'];
        $this->updateOrderInformation($order, $data);
        $em = $this->getDoctrine()->getManager();
        if(isset($data['transactionType']) and $data['transactionType'] and $order->getTotal() > 0){
            $entity = new OrderPayment();
            $entity->setOrder($order);
            if($data['transactionType'] == 'Return'){
                $entity->setTransactionType('Return');
                $entity->setAmount('-'.$data['amount']);
            }elseif($data['transactionType'] == 'Receive'){
                $entity->setTransactionType('Receive');
                $entity->setAmount($data['amount']);
            }
            if(!empty($data['accountMobileBank'])){
                $accountMobileBank =$this->getDoctrine()->getRepository('AccountingBundle:AccountMobileBank')->find($data['accountMobileBank']);
                $entity->setAccountMobileBank($accountMobileBank);
                $method = $this->getDoctrine()->getRepository(TransactionMethod::class)->findOneBy(array('slug'=>'mobile'));
                $entity->setTransactionMethod($method);
            }
            $entity->setMobileAccount($data['mobileAccount']);
            $entity->setTransaction($data['transaction']);
            $em->persist($entity);
            $em->flush();
            $this->getDoctrine()->getRepository('EcommerceBundle:Order')->updateOrderPayment($order);
            return $this->redirect($this->generateUrl('customer_order_edit',array('id' => $order->getId())));
        }
        $cashDelivery = isset($payment['cashOnDelivery']) and $payment['cashOnDelivery'] == 1 ? $payment['cashOnDelivery'] : 0;
        if($cashDelivery == 1 ) {
            $order->setCashOnDelivery(true);
        }else {
            $order->setCashOnDelivery(false);
        }
        $em->persist($order);
        $em->flush();
        return $this->redirect($this->generateUrl('customer_order_edit',array('id' => $order->getId())));

    }

    /**
     * Lists all Item entities.
     *
     * @Secure(roles = "ROLE_DOMAIN_ECOMMERCE_ORDER,ROLE_DOMAIN")
     */


    public function updateOrderInformation(Order $order,$data)
    {

        $em = $this->getDoctrine()->getManager();
        if (!empty($data['customerMobile'])) {
            $mobile = $this->get('settong.toolManageRepo')->specialExpClean($data['customerMobile']);
            $user = $this->getDoctrine()->getRepository('UserBundle:User')->newExistingCustomerForSales($order->getGlobalOption(),$mobile,$data);
            $order->setCreatedBy($user);
            $order->setCustomerName($user->getProfile()->getName());
            $order->setCustomerMobile($user->getProfile()->getMobile());
            $order->setAddress($user->getProfile()->getAddress());
        }
        if (isset($data['deliveryDate']) and $data['deliveryDate']) {
            $date = new \DateTime($data['deliveryDate']);
            $order->setDeliveryDate($date);
        }
        if (isset($data['deliverySlot']) and $data['deliverySlot']) {
            $order->setDeliverySlot($data['deliverySlot']);
        }
        if (isset($data['trackingNo']) and $data['trackingNo']) {
            $order->setTrackingNo($data['trackingNo']);
        }
        if (isset($data['timePeriod']) and $data['timePeriod']) {
            $timePeriod = $this->getDoctrine()->getRepository('EcommerceBundle:TimePeriod')->find($data['timePeriod']);
            $order->setTimePeriod($timePeriod);
        }
        if (isset($data['courier']) and $data['courier']) {
            $courier = $this->getDoctrine()->getRepository('EcommerceBundle:CourierService')->find($data['courier']);
            $order->setCourier($courier);
        }
        if (isset($data['shippingCharge']) and $data['shippingCharge']) {
            $order->setShippingCharge($data['shippingCharge']);
        }
        if (isset($data['discountAmount']) and $data['discountAmount']) {
            $order->setDiscountAmount($data['discountAmount']);
        }
        if (isset($data['process']) and $data['process']) {
            $order->setProcess($data['process']);
        }
        $em->persist($order);
        $em->flush();
        $this->getDoctrine()->getRepository('EcommerceBundle:Order')->updateOrder($order);
        if($order->getProcess() == "delivered"){
            if($order->getEcommerceConfig()->getStockApplication()->getSlug() == 'inventory' and $order->getEcommerceConfig()->isInventoryStock() == 1){
                $this->getDoctrine()->getRepository('InventoryBundle:Sales')->insertEcommerceSales($order);
            }else{
                $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->insertEcommerceSales($order);
            }
        }
        return new Response('success');
    }


    /**
     * Displays a form to edit an existing OrderItem entity.
     *
     */
    public function inlineUpdateAction(Request $request,OrderItem $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $entity->setQuantity($data['value']);
        $entity->setSubTotal($entity->getPrice() *  floatval($data['value']));
        $em->flush();
        $em->getRepository('EcommerceBundle:Order')->updateOrder($entity->getOrder());
        return new Response('success');
    }

    /**
     * Displays a form to edit an existing OrderItem entity.
     *
     */
    public function inlineItemAddAction(Request $request,OrderItem $entity)
    {
        return new Response('success');
    }


    /**
     * Displays a form to edit an existing OrderItem entity.
     *
     */
    public function inlineDisableAction(Request $request,OrderItem $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if($entity->getStatus() == 1){
            $entity->setStatus(false);
        }else{
            $entity->setStatus(true);
        }
        $em->flush();
        $em->getRepository('EcommerceBundle:Order')->updateOrder($entity->getOrder());
        return new Response('success');

    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     */
    public function shippingChargeAction(Request $request,Order $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $entity->setShippingCharge($data['value']);
        $em->flush();
        $em->getRepository('EcommerceBundle:Order')->updateOrder($entity);
        return new Response('success');
    }


    public function itemUpdateAction(Order $order , OrderItem $item)
    {

        $em = $this->getDoctrine()->getManager();
        if (!$item) {
            throw $this->createNotFoundException('Unable to find Expenditure entity.');
        }
        $data = $_REQUEST;
        $item->setQuantity($data['quantity']);
        $item->setSubTotal($item->getPrice() * $data['quantity']);
        $em->persist($item);
        $em->flush();
        $this->getDoctrine()->getRepository('EcommerceBundle:Order')->updateOrder($order);

        $this->get('session')->getFlashBag()->add(
            'success',"Item has been updated successfully"
        );
        return new Response('success');

    }

    public function itemDeleteAction(Order $order , OrderItem $item)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        $this->getDoctrine()->getRepository('EcommerceBundle:Order')->updateOrder($order);
        $this->get('session')->getFlashBag()->add(
            'error',"Data has been deleted successfully"
        );
        return new Response('su');
    }

    /**
     * Lists all Item entities.
     *
     * @Secure(roles = "ROLE_DOMAIN_ECOMMERCE_ORDER,ROLE_DOMAIN")
     */

    public function confirmAction(Request $request ,Order $order)
    {
        $em = $this->getDoctrine()->getManager();
        $process = $order->getProcess();
        $paymentEntity = new  OrderPayment();
        $orderForm = $this->createEditForm($order);
        $payment = $this->createEditPaymentForm($paymentEntity,$order);
        $orderForm->handleRequest($request);
        if ($orderForm->isValid()) {
            $em->persist($order);
            if($order->getProcess() == 'sms'){
                $order->setProcess($process);
            }
            $em->flush();
            if($order->getProcess() == 'confirm'){
                $em->getRepository('EcommerceBundle:OrderItem')->updateOrderItem($order);
                $em->getRepository('EcommerceBundle:Order')->updateOrder($order);
                $em->getRepository('EcommerceBundle:OrderPayment')->updateOrderPayment($order);
                $em->getRepository('EcommerceBundle:Order')->updateOrderPayment($order);
                if($order->getDeliveryDate()){
                    $this->get('session')->getFlashBag()->add('success',"Customer has been confirmed");
                    $dispatcher = $this->container->get('event_dispatcher');
                    $dispatcher->dispatch('setting_tool.post.order_confirm_sms', new \Setting\Bundle\ToolBundle\Event\EcommerceOrderSmsEvent($order));
                }
            }else{
                $this->get('session')->getFlashBag()->add('success',"Message has been sent successfully");
                $dispatcher = $this->container->get('event_dispatcher');
                $dispatcher->dispatch('setting_tool.post.order_comment_sms', new \Setting\Bundle\ToolBundle\Event\EcommerceOrderSmsEvent($order));

            }
            if($order->getProcess() == "delivered"){
                if($order->getEcommerceConfig()->getStockApplication()->getSlug() == 'inventory' and $order->getEcommerceConfig()->isInventoryStock() == 1){
                    $this->getDoctrine()->getRepository('InventoryBundle:Sales')->insertEcommerceSales($order);
                }else{
                    $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->insertEcommerceSales($order);
                }
            }
            return $this->redirect($this->generateUrl('customer_order_payment',array('id' => $order->getId())));
        }
        return $this->render('EcommerceBundle:Order:payment.html.twig', array(
            'entity'                => $order,
            'orderForm'             => $orderForm->createView(),
            'paymentForm'           => $payment->createView(),
        ));


    }

    public function orderProcessSourceAction()
    {

        $items[]=array('value' => 'created','text'=> 'Created');
        $items[]=array('value' => 'wfc','text'=> 'Waiting for Confirm');
        $items[]=array('value' => 'confirm','text'=> 'Confirm');
        $items[]=array('value' => 'shipped','text'=> 'Shipped');
        $items[]=array('value' => 'delivered','text'=> 'Delivered');
        $items[]=array('value' => 'returned','text'=> 'Returned');
        $items[]=array('value' => 'cancel','text'=> 'Cancel');
        $items[]=array('value' => 'delete','text'=> 'Delete');
        return new JsonResponse($items);

    }

    public function inlineProcessUpdateAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('EcommerceBundle:Order')->find($data['pk']);
        if (!$order) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }
        $order->setProcess($data['value']);
        $em->flush();
        if($order->getProcess() == 'confirm') {
            $em->getRepository('EcommerceBundle:OrderItem')->updateOrderItem($order);
            $em->getRepository('EcommerceBundle:Order')->updateOrder($order);
            $em->getRepository('EcommerceBundle:OrderPayment')->updateOrderPayment($order);
            $em->getRepository('EcommerceBundle:Order')->updateOrderPayment($order);
            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch('setting_tool.post.order_confirm_sms', new \Setting\Bundle\ToolBundle\Event\EcommerceOrderSmsEvent($order));
        }elseif($order->getProcess() == "delivered"){
            if($order->getEcommerceConfig()->getStockApplication()->getSlug() == 'inventory' and $order->getEcommerceConfig()->isInventoryStock() == 1){
                $this->getDoctrine()->getRepository('InventoryBundle:Sales')->insertEcommerceSales($order);
            }else{
                $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->insertEcommerceSales($order);
            }
        }
        return new Response('success');


    }

    public function purchaseItemSelectAction(OrderItem $orderItem)
    {

        $entities = $this->getDoctrine()->getRepository('EcommerceBundle:OrderItem')->getPurchaseVendorItemList($orderItem);
        $items = array();
        $items[]=array('value' => '','text'=> 'Add Inventory Item');
        foreach ($entities as $entity):
            $items[]=array('value' => $entity['id'],'text'=> $entity['barcode'].'('.$entity['quantity'].')');
        endforeach;
        return new JsonResponse($items);
    }

    public function purchaseItemAddAction(Request $request , OrderItem $entity)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $purchaseItem = $this->getDoctrine()->getRepository('InventoryBundle:PurchaseItem')->find($data['value']);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }
        $entity->setPurchaseItem($purchaseItem);
        $em->flush();
        return new Response('success');
    }


    public function confirmItemAction(Order $order, OrderItem $orderItem)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $orderItem->setStatus($data['status']);
        $em->persist($orderItem);
        $em->flush();
        $this->getDoctrine()->getRepository('EcommerceBundle:Order')->updateOrder($order);
        return new Response('success');

    }

    public function paymentDeleteAction(OrderPayment $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Expenditure entity.');
        }
        $em->remove($entity);
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'error',"Data has been deleted successfully"
        );
        return new Response('success');
    }

    public function confirmPaymentAction(OrderPayment $payment, $process)
    {
        $em = $this->getDoctrine()->getManager();
        if(empty($payment->getApprovedBy())){
            $payment->setStatus($process);
            $payment->setApprovedBy($this->getUser());
            $em->persist($payment);
            $em->flush();
            $this->getDoctrine()->getRepository('EcommerceBundle:Order')->updateOrderPayment($payment->getOrder());
            if($payment->getTransactionType() == 'Receive'){
                $this->getDoctrine()->getRepository('AccountingBundle:AccountSales')->insertEcommerceOrderPayable($payment);
            }elseif($payment->getTransactionType() == 'Return'){
                $this->getDoctrine()->getRepository('AccountingBundle:AccountJournal')->insertEcommerceOrderPayable($payment);
            }
            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch('setting_tool.post.order_payment_confirm_sms', new \Setting\Bundle\ToolBundle\Event\EcommerceOrderPaymentSmsEvent($payment));

        }
        return new Response('success');

    }

    public function deleteAction(Order $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Expenditure entity.');
        }
        $entity->setIsDelete(1);
        $em->persist($entity);
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'error',"Data has been deleted successfully"
        );
        return new Response('success');
    }


    public function getBarcode($invoice)
    {
        $barcode = new BarcodeGenerator();
        $barcode->setText($invoice);
        $barcode->setType(BarcodeGenerator::Code128);
        $barcode->setScale(1);
        $barcode->setThickness(32);
        $barcode->setFontSize(7);
        $code = $barcode->generate();
        $data = '';
        $data .= '<img src="data:image/png;base64,' . $code . '" />';
        return $data;
    }

    public function pdfAction(Order $order)
    {


        /* @var Order $order */

        $amountInWords = $this->get('settong.toolManageRepo')->intToWords($order->getGrandTotalAmount());
        $barcode = $this->getBarcode($order->getInvoice());
        $html = $this->renderView( 'CustomerBundle:Order:invoice.html.twig', array(
            'globalOption' => $order->getGlobalOption(),
            'entity' => $order,
            'amountInWords' => $amountInWords,
            'barcode' => $barcode,
            'print' => ''
        ));

        $wkhtmltopdfPath = 'xvfb-run --server-args="-screen 0, 1280x1024x24" /usr/bin/wkhtmltopdf --use-xserver';
        $snappy          = new Pdf($wkhtmltopdfPath);
        $pdf             = $snappy->getOutputFromHtml($html);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="online-invoice-'.$order->getInvoice().'.pdf"');
        echo $pdf;
        return new Response('');

    }

    public function printAction(Order $order)
    {
        $amountInWords = $this->get('settong.toolManageRepo')->intToWords($order->getGrandTotalAmount());
        $barcode = $this->getBarcode($order->getInvoice());
        return $this->render('EcommerceBundle:Order:invoice.html.twig', array(
            'globalOption' => $order->getGlobalOption(),
            'entity' => $order,
            'amountInWords' => $amountInWords,
            'barcode' => $barcode,
            'print' => ''
        ));

    }

    public function downloadAttachFileAction(Order $order)
    {

        $file = $order->getWebPath();
        if (file_exists($file))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }


}
