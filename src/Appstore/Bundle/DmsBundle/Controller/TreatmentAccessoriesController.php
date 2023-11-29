<?php

namespace Appstore\Bundle\DmsBundle\Controller;

use Appstore\Bundle\DmsBundle\Entity\DmsInvoiceAccessories;
use Appstore\Bundle\DmsBundle\Entity\DmsParticular;
use Appstore\Bundle\DmsBundle\Entity\DmsPurchase;
use Appstore\Bundle\DmsBundle\Entity\DmsPurchaseItem;
use Appstore\Bundle\DmsBundle\Form\PurchaseType;
use Appstore\Bundle\DmsBundle\Form\TreatmentAccessoriesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Treatment Accessories Controller.
 *
 */
class TreatmentAccessoriesController extends Controller
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
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $entities = $this->getDoctrine()->getRepository('DmsBundle:DmsInvoiceAccessories')->findInvoiceAccessories($config);
        $pagination = $this->paginate($entities);
        $entity = new DmsInvoiceAccessories();
        $editForm = $this->createCreateForm($entity);
        return $this->render('DmsBundle:TreatmentAccessories:index.html.twig', array(
            'entities' => $pagination,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a new Particular entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new DmsInvoiceAccessories();
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $entities = $this->getDoctrine()->getRepository('DmsBundle:DmsInvoiceAccessories')->findInvoiceAccessories($config);
        $pagination = $this->paginate($entities);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setDmsConfig($config);
            $entity->setPrice($entity->getDmsParticular()->getPrice());
            $entity->setSubTotal($entity->getDmsParticular()->getPrice() + $entity->getQuantity());
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('dms_treatment_accessories'));
        }

        return $this->render('DmsBundle:TreatmentAccessories:index.html.twig', array(
            'entity' => $entity,
            'entities' => $pagination,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Particular entity.
     *
     * @param DmsParticular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(DmsInvoiceAccessories $entity)
    {
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $form = $this->createForm(new TreatmentAccessoriesType($config), $entity, array(
            'action' => $this->generateUrl('dms_treatment_accessories_create', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }


    public function deleteAccessoriesAction(DmsInvoiceAccessories $accessories){

        $em = $this->getDoctrine()->getManager();
        if (!$accessories) {
            throw $this->createNotFoundException('Unable to find SalesItem entity.');
        }
        $em->remove($accessories);
        $em->flush();
        exit;
    }

    public function approvedAccessoriesAction(DmsInvoiceAccessories $accessories){

        $em = $this->getDoctrine()->getManager();
        if (!$accessories) {
            throw $this->createNotFoundException('Unable to find SalesItem entity.');
        }
        $accessories->setStatus(1);
        $em->flush();
        $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getSalesUpdateQnt($accessories);
        exit;
    }


}
