<?php

namespace Appstore\Bundle\AssetsBundle\Controller;

use Appstore\Bundle\AssetsBundle\Entity\Purchase;
use Appstore\Bundle\AssetsBundle\Entity\PurchaseItem;
use Appstore\Bundle\AssetsBundle\Entity\StockIssue;
use Appstore\Bundle\AssetsBundle\Entity\StockIssueItem;
use Appstore\Bundle\AssetsBundle\Form\PurchaseType;
use Appstore\Bundle\AssetsBundle\Form\StockIssueType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vendor controller.
 *
 */
class StockIssueController extends Controller
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
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig()->getId();
        $data = $_REQUEST;
        $entities = $this->getDoctrine()->getRepository('AssetsBundle:StockIssue')->findWithSearch($config,$data);
        $pagination = $this->paginate($entities);
        return $this->render('AssetsBundle:StockIssue:index.html.twig', array(
            'entities' => $pagination,
            'searchForm' => $data,
        ));
    }

    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new StockIssue();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
        $entity->setConfig($config);
        $entity->setCreatedBy($this->getUser());
        $entity->setUpdated($entity->getCreated());
        $entity->setDeliveryDate($entity->getCreated());
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('assets_stockissue_edit', array('id' => $entity->getId())));
    }


    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig()->getId();
        $entity = $em->getRepository('AssetsBundle:StockIssue')->findOneBy(array('config' => $config , 'id' => $id));
        $products = $em->getRepository('AssetsBundle:Item')->findAll(array('config' => $config));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity.');
        }
        $editForm = $this->createEditForm($entity);
        return $this->render("AssetsBundle:StockIssue:new.html.twig",array(
            'entity' => $entity,
            'products' => $products,
            'id' => 'purchase',
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Invoice entity.wq
     *
     * @param StockIssue $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(StockIssue $entity)
    {
        $globalOption = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new StockIssueType($globalOption), $entity, array(
            'action' => $this->generateUrl('assets_stockissue_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
                'id' => 'purchase',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    public function returnResultData(StockIssue $invoice,$msg=''){

       // $invoiceParticulars = $this->getDoctrine()->getRepository('AssetsBundle:StockIssueItem')->getPurchaseItems($invoice);
        $invoiceParticulars =  $this->renderView("AssetsBundle:StockIssue:item.html.twig",array(
            'entity' => $invoice,
        ));
        return $invoiceParticulars;

    }


    public function addParticularAction(Request $request, $id)
    {

        /* @var $stockissue StockIssue */
        $stockissue = $this->getDoctrine()->getRepository('AssetsBundle:StockIssue')->find($id);
        $em = $this->getDoctrine()->getManager();
        $item = $request->request->get('item');
        $pItem = '';//$request->request->get('productItem');
        $quantity = $request->request->get('quantity');
        $item = $this->getDoctrine()->getRepository('AssetsBundle:Item')->find($item);
        $purchaseItem = $this->getDoctrine()->getRepository('AssetsBundle:PurchaseItem')->find($pItem);
        $exist = $this->getDoctrine()->getRepository(StockIssueItem::class)->findOneBy(array('stockIssue'=>$stockissue,'item'=>$item));
        if(empty($exist)){
            $entity = new StockIssueItem();
            $subTotal = $quantity * $item->getSalesPrice();
            $entity->setConfig($stockissue->getConfig());
            $entity->setStockIssue($stockissue);
            $entity->setItem($item);
            if($purchaseItem){
                $entity->setPurchaseItem($purchaseItem);
            }
            $entity->setPrice($item->getSalesPrice());
            $entity->setQuantity($quantity);
            $entity->setSubTotal($subTotal);
            $em->persist($entity);
            $em->flush();
            $invoice = $this->getDoctrine()->getRepository('AssetsBundle:StockIssueItem')->updatePurchaseTotalPrice($stockissue);
            $result = $this->returnResultData($invoice);
        }{
            $result = $this->returnResultData($stockissue);
        }
        return new Response($result);

    }


    public function itemDeleteAction($entity, StockIssueItem $particular){

        $em = $this->getDoctrine()->getManager();
        if (!$particular) {
            throw $this->createNotFoundException('Unable to find SalesItem entity.');
        }
        $em->remove($particular);
        $em->flush();
        $stockReceive = $this->getDoctrine()->getRepository(StockIssue::class)->find($entity);
        $this->getDoctrine()->getRepository('AssetsBundle:StockIssueItem')->updatePurchaseTotalPrice($stockReceive);
        $result = $this->returnResultData($stockReceive);
        return new Response($result);

    }

   
    public function updateAction(Request $request, StockIssue $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $globalOption = $this->getUser()->getGlobalOption();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AccountVendor entity.');
        }
        $form = $this->createEditForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $entity->setProcess('Complete');
            $em->flush();
            return $this->redirect($this->generateUrl('assets_stockissue_show',array('id'=>$entity->getId())));
        }
        $products = $em->getRepository('AssetsBundle:Item')->findAll(array('config' => $entity->getConfig()));
        return $this->render("AssetsBundle:StockIssue:new.html.twig",array(
            'entity' => $entity,
            'id' => 'StockIssue',
            'products' => $products,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a Vendor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
        $entity = $em->getRepository('AssetsBundle:StockIssue')->findOneBy(array('config' => $config , 'id' => $id));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StockIssue entity.');
        }
        return $this->render('AssetsBundle:StockIssue:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    public function approvedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();

        /* @var $stockissue StockIssue */
        $stockissue = $em->getRepository('AssetsBundle:StockIssue')->findOneBy(array('config' => $config , 'id' => $id));
        if (!empty($stockissue) and empty($stockissue->getApprovedBy())) {
            $em = $this->getDoctrine()->getManager();
            $stockissue->setProcess('Approved');
            $stockissue->setApprovedBy($this->getUser());
            $accountConfig = $this->getUser()->getGlobalOption()->getAccountingConfig()->isAccountClose();
            if($accountConfig == 1){
                $datetime = new \DateTime("yesterday 30:30:30");
                $stockissue->setCreated($datetime);
                $stockissue->setUpdated($datetime);
            }
            $em->flush();
            return new Response('success');

        } else {

            return new Response('failed');
        }

    }

    /**
     * Deletes a Vendor entity.
     *
     */
    public function deleteAction($id)
    {

        $config = $this->getUser()->getGlobalOption();
        $entity = $this->getDoctrine()->getRepository('AssetsBundle:StockIssue')->findOneBy(array('globalOption' => $config , 'id' => $id));

        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Vendor entity.');
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('assets_stockissue'));
    }



    public function reverseAction($id)
    {


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
