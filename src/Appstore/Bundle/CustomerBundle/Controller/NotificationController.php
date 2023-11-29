<?php

namespace Appstore\Bundle\CustomerBundle\Controller;
use Appstore\Bundle\EcommerceBundle\Entity\PreOrderItem;
use Appstore\Bundle\EcommerceBundle\Entity\PreOrderPayment;
use Appstore\Bundle\EcommerceBundle\Form\PreOrderItemType;
use Appstore\Bundle\EcommerceBundle\Form\PreOrderPaymentCustomeerType;
use Appstore\Bundle\EcommerceBundle\Form\PreOrderPaymentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Appstore\Bundle\EcommerceBundle\Entity\PreOrder;
use Appstore\Bundle\EcommerceBundle\Form\PreOrderType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * PreOrder controller.
 *
 */
class NotificationController extends Controller
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
     * Lists all PreOrder entities.
     *
     */
    public function smsAction($shop)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $globalOption = $this->getDoctrine()->getRepository('SettingToolBundle:GlobalOption')->findOneBy(array('slug' => $shop));
        $entities = $this->getDoctrine()->getRepository('DomainUserBundle:CustomerInbox')->findBy(array('createdBy' => $user,'type'=>'sms'), array('updated' => 'desc'));
        $pagination = $this->paginate($entities);
        return $this->render('CustomerBundle:Notification:sms.html.twig', array(
            'entities' => $pagination,
            'globalOption' => $globalOption,
        ));
    }

    public function emailAction($shop)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $globalOption = $this->getDoctrine()->getRepository('SettingToolBundle:GlobalOption')->findOneBy(array('slug' => $shop));
        $entities = $this->getDoctrine()->getRepository('DomainUserBundle:CustomerInbox')->findBy(array('createdBy' => $user,'type'=>'email'), array('updated' => 'desc'));
        $pagination = $this->paginate($entities);
        return $this->render('CustomerBundle:Notification:email.html.twig', array(
            'entities' => $pagination,
            'globalOption' => $globalOption,
        ));
    }

    /**
     * Creates a new PreOrder entity.
     *
     */
    public function createAction(Request $request)
    {

        $entity = new PreOrder();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('preorder_show', array('id' => $entity->getId())));
        }

        return $this->render('CustomerBundle:PreOrder:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to create a new PreOrder entity.
     *
     */
    public function newAction($shop)
    {
        $em = $this->getDoctrine()->getManager();
        $globalOption = $em->getRepository('SettingToolBundle:GlobalOption')->findOneBy(array('slug'=>$shop));
        $entity = new PreOrder();
        $ecommerce = $globalOption->getEcommerceConfig();
        $entity->setGlobalOption($globalOption);
        $entity->setEcommerceConfig($ecommerce);
        $entity->setCustomer($this->getUser());
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('preorder_item', array('shop' => $shop ,'id' => $entity->getId())));

    }

    /**
     * Finds and displays a PreOrder entity.
     *
     */
    public function showAction(PreOrder $entity)
    {
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PreOrder entity.');
        }
        return $this->render('CustomerBundle:PreOrder:show.html.twig', array(
            'entity' => $entity,
            'globalOption' => $entity->getGlobalOption(),

        ));
    }

    /**
     * Displays a form to edit an existing PreOrder entity.
     *
     */
    public function editAction(PreOrder $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PreOrder entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('CustomerBundle:PreOrder:new.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a PreOrder entity.
     *
     * @param PreOrder $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */

    private function createPaymentForm(PreOrderPayment $entity,PreOrder $preOrder)
    {
        $location = $this->getDoctrine()->getRepository('SettingLocationBundle:Location');
        $form = $this->createForm(new PreOrderPaymentCustomeerType($preOrder->getGlobalOption(),$location), $entity, array(
            'action' => $this->generateUrl('preorder_ajax_payment', array('shop' => $preOrder->getGlobalOption()->getSlug(),'id' => $preOrder->getId())),
            'method' => 'POST',
            'attr' => array(
                'id' => 'ecommerce-payment',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }


    public function itemAction(PreOrder $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PreOrder entity.');
        }
        $preOrderItem = new PreOrderItem();
        $form = $this->createItemForm($preOrderItem,$entity);
        $paymentEntity = new  PreOrderPayment();
        $preOrderform = $this->createPaymentForm($paymentEntity,$entity);
        return $this->render('CustomerBundle:PreOrder:item.html.twig', array(
            'entity'      => $entity,
            'globalOption' => $entity->getGlobalOption(),
            'form'   => $form->createView(),
            'paymentForm'   => $preOrderform->createView(),
        ));


    }



    /**
     * Edits an existing PreOrder entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $entity = $em->getRepository('EcommerceBundle:PreOrder')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PreOrder entity.');
        }


        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {


            $em->flush();
            return $this->redirect($this->generateUrl('preorder_item', array('id' => $id)));
        }

        if($data['submitProcess'] == 'confirm'){

         $this->get('session')->getFlashBag()->add('success',"Message has been sent successfully");
         $dispatcher = $this->container->get('event_dispatcher');
         $dispatcher->dispatch('setting_tool.post.preorder_sms', new \Setting\Bundle\ToolBundle\Event\EcommercePreOrderSmsEvent($entity));

         }


    }

    /**
     * Deletes a PreOrder entity.
     *
     */
    public function deleteAction(PreOrder $preOrder)
    {

        if ($preOrder) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($preOrder);
            $em->flush();
            return new Response('success');
        }else{
            return new Response('failed');

        }
    }

    private function createItemForm(PreOrderItem $entity,PreOrder $preOrder)
    {
        $form = $this->createForm(new PreOrderItemType(), $entity, array(
            'action' => $this->generateUrl('preorder_item_create', array('shop' => $preOrder->getGlobalOption()->getSlug(),'id' => $preOrder->getId())),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    public function createItemAction(Request $request,PreOrder $preOrder)
    {
        $globalOption = $preOrder->getEcommerceConfig()->getGlobalOption()->getSlug();
        $preOrderItem = new PreOrderItem();
        $form = $this->createItemForm($preOrderItem,$preOrder);
        $form->handleRequest($request);
        $process = $request->request->get('process');
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $preOrderItem->setPreOrder($preOrder);
            $preOrderItem->setSubTotal($preOrderItem->getQuantity() * $preOrderItem->getUnitPrice());
            $em->persist($preOrderItem);
            $em->flush();
            $this->getDoctrine()->getRepository('EcommerceBundle:PreOrder')->updatePreOder($preOrder,$process );
            return $this->redirect($this->generateUrl('preorder_item', array('shop'=> $globalOption,'id' => $preOrder->getId())));

        }
        $paymentEntity = new  PreOrderPayment();
        $preOrderform = $this->createPaymentForm($paymentEntity,$preOrder);
        return $this->render('CustomerBundle:PreOrder:item.html.twig', array(
            'entity' => $preOrder,
            'globalOption' => $preOrder->getGlobalOption(),
            'form'=> $form->createView(),
            'paymentForm'   => $preOrderform->createView(),
        ));
    }

    public function deleteItemAction(PreOrder $preOrder, PreOrderItem $preOrderItem)
    {
        if ($preOrderItem) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($preOrderItem);
            $em->flush();
            $this->getDoctrine()->getRepository('EcommerceBundle:PreOrder')->updatePreOder($preOrder);
            return new Response('success');
        }else{
            return new Response('failed');
        }

    }


    public function processAction(Request $request , PreOrder $preOrder)
    {
        $data = $_REQUEST;
        $em = $this->getDoctrine()->getManager();
        if(!empty( $data['delivery'])){
            $address = $data['address'];
            $preOrder->setAddress($address);
            $delivery = $data['delivery'];
            $preOrder->setDelivery($delivery);
        }
        $em->persist($preOrder);
        $em->flush();
        return $this->redirect($this->generateUrl('preorder_item', array('shop'=> $preOrder->getGlobalOption()->getSlug(),'id' => $preOrder->getId())));
    }

    public function approveAction(PreOrder $preOrder)
    {

        $em = $this->getDoctrine()->getManager();
        $preOrder->setApprovedBy($this->getUser());
        $preOrder->setProcess('wfc');
        $preOrder->setComment('Confirm your order within short time');
        $em->persist($preOrder);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success',"Customer has been confirmed");
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('setting_tool.post.pre_order_sms', new \Setting\Bundle\ToolBundle\Event\EcommercePreOrderSmsEvent($preOrder));

        return new Response('success');

    }

    public function confirmItemAction(PreOrder $preOrder, PreOrderItem $preOrderItem)
    {
        $em = $this->getDoctrine()->getManager();
        if ($preOrderItem->isStatus() == 1){
            $preOrderItem->setStatus(0);
        }else{
            $preOrderItem->setStatus(1);
        }
        $em->persist($preOrderItem);
        $em->flush();
        $this->getDoctrine()->getRepository('EcommerceBundle:PreOrder')->updatePreOder($preOrder);
        return new Response('success');

    }


    public function paymentAction(Request $request ,PreOrder $preOrder)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = new PreOrderPayment();
        $entity->setPreOrder($preOrder);
        $entity->setTransactionType('Payment');
        $entity->setAmount($data['amount']);
        $accountMobileBank =$this->getDoctrine()->getRepository('AccountingBundle:AccountMobileBank')->find($data['accountMobileBank']);
        $entity->setAccountMobileBank($accountMobileBank);
        $entity->setMobileAccount($data['mobileAccount']);
        $entity->setTransaction($data['transaction']);
        $em->persist($entity);
        $em->flush();
        $this->getDoctrine()->getRepository('EcommerceBundle:PreOrder')->updatePreOderPayment($preOrder);
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('setting_tool.post.pre_order_payment_sms', new \Setting\Bundle\ToolBundle\Event\EcommercePreOrderPaymentSmsEvent($entity));

        return new Response('success');

    }

    public function paymentDeleteAction(PreOrderPayment $entity)
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


    public function invoiceAction(PreOrder $preOrder)
    {
        return $this->render('CustomerBundle:PreOrder:invoice.html.twig', array(
            'entity' => $preOrder
        ));
    }



}
