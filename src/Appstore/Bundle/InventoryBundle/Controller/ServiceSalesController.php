<?php

namespace Appstore\Bundle\InventoryBundle\Controller;

use Appstore\Bundle\InventoryBundle\Entity\ServiceSales;
use Appstore\Bundle\InventoryBundle\Form\ServiceSalesType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Appstore\Bundle\InventoryBundle\Entity\ItemAttribute;
use Appstore\Bundle\InventoryBundle\Entity\ItemGallery;
use Appstore\Bundle\InventoryBundle\Entity\ItemKeyValue;
use Appstore\Bundle\InventoryBundle\Form\ServiceItemType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Appstore\Bundle\InventoryBundle\Entity\PurchaseVendorItem;
use Symfony\Component\HttpFoundation\Response;

/**
 * ServiceSalesController controller.
 *
 */
class ServiceSalesController extends Controller
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
     * Lists all PurchaseVendorItem entities.
     * @Secure(roles="ROLE_DOMAIN_SERVICE_MANAGER")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $entities = $em->getRepository('InventoryBundle:ServiceSales')->findBy(array('inventoryConfig' => $inventory));
        $pagination = $this->paginate($entities);
        return $this->render('InventoryBundle:ServiceSales:index.html.twig', array(
            'entities' => $pagination,
        ));
    }
    /**
     * Creates a new PurchaseVendorItem entity.
     * @Secure(roles="ROLE_DOMAIN_SERVICE_MANAGER")
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ServiceSales();
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setInventoryConfig($inventory);
            $entity->setCustomer($entity->getCustomer());
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been inserted successfully"
            );
            return $this->redirect($this->generateUrl('inventory_servicesales_add',array('id'=>$entity->getId())));
        }
        $ecommerceConfig = $this->getUser()->getGlobalOption()->getEcommerceConfig();
        return $this->render('InventoryBundle:ServiceItem:new.html.twig', array(
            'entity' => $entity,
            'ecommerceConfig' => $ecommerceConfig,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Creates a form to create a PurchaseVendorItem entity.
     *
     * @param PurchaseVendorItem $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ServiceSales $entity)
    {
        $globalOption = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new ServiceSalesType($globalOption), $entity, array(
            'action' => $this->generateUrl('inventory_servicesales_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'action',
                'novalidate' => 'novalidate',
            )


        ));
        return $form;
    }

    /**
     * Displays a form to create a new PurchaseVendorItem entity.
     * @Secure(roles="ROLE_DOMAIN_SERVICE_MANAGER")
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new ServiceSales();
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $entity->setInventoryConfig($inventory);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('inventory_servicesales_add',array('id'=>$entity->getId())));
    }

    /**
     * Finds and displays a PurchaseVendorItem entity.
     * @Secure(roles="ROLE_DOMAIN_SERVICE_MANAGER")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('InventoryBundle:PurchaseVendorItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PurchaseVendorItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('InventoryBundle:ServiceItem:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ServiceSales entity.
     * @Secure(roles="ROLE_DOMAIN_SERVICE_MANAGER")
     */

    public function editAction(ServiceSales $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PurchaseVendorItem entity.');
        }
        $editForm = $this->ServiceSalesType($entity);
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $entities = $em->getRepository('InventoryBundle:PurchaseVendorItem')->findItemWithSearch($inventory,$data);
        return $this->render('InventoryBundle:ServiceSales:new.html.twig', array(
            'entity'        => $entity,
            'entities'      => $entities,
            'form'          => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a ServiceSales entity.
     *
     * @param ServiceSales $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ServiceSales $entity)
    {
        $globalOption = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new ServiceSalesType($globalOption), $entity, array(
            'action' => $this->generateUrl('inventory_servicesales_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'action',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing PurchaseVendorItem entity.
     * @Secure(roles="ROLE_DOMAIN_SERVICE_MANAGER")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $entity = $em->getRepository('InventoryBundle:PurchaseVendorItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PurchaseVendorItem entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if(!empty($entity->upload())) {
                $entity->removeUpload();
            }
            $entity->upload();
            $em->flush();
            $this->getDoctrine()->getRepository('InventoryBundle:ItemMetaAttribute')->insertProductAttribute($entity,$data);
            $this->getDoctrine()->getRepository('InventoryBundle:ItemKeyValue')->insertItemKeyValue($entity,$data);
            $this->getDoctrine()->getRepository('InventoryBundle:ItemGallery')->insertProductGallery($entity,$data);
            return $this->redirect($this->generateUrl('inventory_serviceitem_edit', array('id' => $id)));
        }
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $sizes = $em->getRepository('InventoryBundle:ItemSize')->findBy(array('inventoryConfig'=>$inventory, 'status'=>1),array('name'=>'ASC'));
        $colors = $em->getRepository('InventoryBundle:ItemColor')->findBy(array('inventoryConfig'=>$inventory, 'status'=>1),array('name'=>'ASC'));
        $ecommerceConfig = $this->getUser()->getGlobalOption()->getEcommerceConfig();
        return $this->render('InventoryBundle:ServiceItem:edit.html.twig', array(
            'entity'      => $entity,
            'ecommerceConfig'      => $ecommerceConfig,
            'sizes'       => $sizes,
            'colors'      => $colors,
            'form'   => $editForm->createView(),
        ));
    }


    /**
     * Deletes a PurchaseVendorItem entity.
     * @Secure(roles="ROLE_DOMAIN_SERVICE_MANAGER")
     */
    public function deleteAction(PurchaseVendorItem $vendorItem)
    {

        if($vendorItem){
            $em = $this->getDoctrine()->getManager();
            $vendorItem->deleteImageDirectory();
            $em->remove($vendorItem);
            $em->flush();
            return new Response('success');
        }else{
            return new Response('failed');
        }
    }

    /**
     * Creates a form to delete a PurchaseVendorItem entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('inventory_serviceitem_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }

    /**
     * Status a PurchaseVendorItem entity.
     *
     */
    public function webStatusAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('InventoryBundle:PurchaseVendorItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find District entity.');
        }

        $status = $entity->getIsWeb();
        if($status == 1){
            $entity->setIsWeb(0);
        } else{
            $entity->setIsWeb(1);
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'success',"Status has been changed successfully"
        );
        return $this->redirect($this->generateUrl('inventory_purchasevendoritem'));
    }



    public function uploadItemImageAction(PurchaseVendorItem $item)
    {
        $entity = new ItemGallery();
        $option = $this->getUser()->getGlobalOption();
        $entity ->upload($option->getId(),$item->getId());
    }

    public function subItemDeleteAction(ServiceItemItem $goodsItem)
    {
        if($goodsItem){
            $em = $this->getDoctrine()->getManager();
            $em->remove($goodsItem);
            $em->flush();
            return new Response('success');
        }else{
            return new Response('failed');
        }
    }

    public function keyValueDeleteAction(ItemKeyValue $itemKeyValue)
    {
        if($itemKeyValue){
            $em = $this->getDoctrine()->getManager();
            $em->remove($itemKeyValue);
            $em->flush();
            return new Response('success');
        }else{
            return new Response('failed');
        }
    }

    public function itemCopyAction(PurchaseVendorItem $item)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = new PurchaseVendorItem();
        $entity->setInventoryConfig($item->getInventoryConfig());
        $entity->setName($item->getName());
        $entity->setCategory($item->getCategory());
        $entity->setSubProduct(true);
        $entity->setQuantity($item->getQuantity());
        $entity->setPurchasePrice($item->getPurchase());
        $entity->setSalesPrice($item->getSalesPrice());
        $entity->setSize($item->getSize());
        $entity->setColor($item->getColor());
        $entity->setCountry($item->getCountry());
        $entity->setSource('goods');
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('inventory_serviceitem_edit', array('id' => $entity->getId())));


    }

    public function discountSelectAction()
    {
        $getEcommerceConfig = $this->getUser()->getGlobalOption()->getEcommerceConfig();
        $entities = $this->getDoctrine()->getRepository('EcommerceBundle:Discount')->findBy(
            array('ecommerceConfig'=>$getEcommerceConfig,'status'=>1)
        );
        $items = array();
        foreach ($entities as $entity):
            $items[]=array('value' => $entity->getId(),'text'=> $entity->getName().'-'. ucfirst($entity->getType()));
        endforeach;
        return new JsonResponse($items);


    }

    public function tagSelectAction()
    {
        $getEcommerceConfig = $this->getUser()->getGlobalOption()->getEcommerceConfig();
        $entities = $this->getDoctrine()->getRepository('EcommerceBundle:Promotion')->getTypeBasePromotion($getEcommerceConfig->getId(),'Tag');
        $items = array();
        foreach ($entities as $entity):
            $items[]=array('value' => $entity->getId(),'text'=> $entity->getName());
        endforeach;
        return new JsonResponse($items);


    }

    public function inlineItemUpdateAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('InventoryBundle:PurchaseVendorItem')->find($data['pk']);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PurchaseItem entity.');
        }
        $setName = 'set'.$data['name'];
        if($data['name'] == 'Discount'){

            $setValue = $em->getRepository('EcommerceBundle:Discount')->find($data['value']);
            $discountPrice = $em->getRepository('InventoryBundle:PurchaseVendorItem')->getCulculationDiscountPrice($entity,$setValue);
            $entity->setDiscountPrice($discountPrice);
            $em->getRepository('InventoryBundle:ServiceItemItem')->subItemDiscountPrice($entity,$setValue);

        }else{
            $setValue = $em->getRepository('EcommerceBundle:Promotion')->find($data['value']);
        }
        $entity->$setName($setValue);
        $em->flush();
        exit;

    }

    public function addAction(ServiceSales $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $inventory = $this->getUser()->getGlobalOption()->getInventoryConfig();
        $entities = $em->getRepository('InventoryBundle:PurchaseVendorItem')->findItemWithSearch($inventory,$data);
        return $this->render('InventoryBundle:ServiceSales:edit.html.twig', array(
            'entity'      => $entity,
            'entities'      => $entities,
        ));
    }




}
