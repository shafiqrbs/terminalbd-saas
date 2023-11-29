<?php

namespace Appstore\Bundle\EducationBundle\Controller;
use Appstore\Bundle\EducationBundle\Entity\EducationConfig;
use Appstore\Bundle\EducationBundle\Entity\EducationFees;
use Appstore\Bundle\EducationBundle\Entity\EducationFeesItem;
use Appstore\Bundle\EducationBundle\Form\FeesType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * FeesController controller.
 *
 */
class FeesController extends Controller
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
     * Lists all Particular entities.
     * @Secure(roles="ROLE_education_fees,ROLE_DOMAIN");
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository('EducationBundle:EducationFees')->findWithSearch($config,$data);
        $pagination = $this->paginate($entities);
        return $this->render('EducationBundle:Fees:index.html.twig', array(
            'pagination' => $pagination,
            'config' => $config,
            'searchForm' => $data,
        ));

    }

    /**
     * Displays a form to create a new Vendor entity.
     * @Secure(roles="ROLE_EDUCATION_ADMIN,ROLE_DOMAIN");
     */

    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new EducationFees();
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entity->setEducationConfig($config);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('education_fees_edit',array('id' => $entity->getId())));
    }


    /**
     * Creates a new EducationFees entity.
     *
     */
    public function createAction(Request $request)
    {

    }

    /**
     * Creates a form to create a Particular entity.
     *
     * @param Particular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EducationFees $entity)
    {

        $option = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new FeesType($option), $entity, array(
            'action' => $this->generateUrl('education_fees_create', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }


    /**
     * Displays a form to edit an existing Particular entity.
     *
     * @Secure(roles="ROLE_education_fees,ROLE_DOMAIN");
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EducationBundle:EducationFees')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $data = array('type'=>'service');
        //$stocks = $this->getDoctrine()->getRepository('EducationBundle:EducationStock')->findWithSearch($config,$data);
        $stocks = $this->getDoctrine()->getRepository('EducationBundle:EducationStock')->findAll();
        $feesItems = array();
        /* @var $item EducationFeesItem */
        if(!empty($entity->getFeesItems())){
            foreach ($entity->getFeesItems() as $item){
                $feesItems[$item->getStock()->getId()] = $item;
            }
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('EducationBundle:Fees:new.html.twig', array(
            'entity'                => $entity,
            'stocks'                => $stocks,
            'feesItems'             => $feesItems,
            'form'                  => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Particular entity.
     *
     * @param Particular $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(EducationFees $entity)
    {
        $option = $this->getUser()->getGlobalOption();
        $form = $this->createForm(new FeesType($option), $entity, array(
            'action' => $this->generateUrl('education_fees_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }
    /**
     * Edits an existing Particular entity.
     *
     */
    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EducationBundle:EducationFees')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }
        $data = $request->request->all();
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $total = $this->getDoctrine()->getRepository('EducationBundle:EducationFees')->process($entity,$data);
            $entity->setAmount($total);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
            return $this->redirect($this->generateUrl('education_fees'));
        }
        $stocks = $this->getDoctrine()->getRepository('EducationBundle:EducationStock')->findAll();
        $feesItems = array();
        /* @var $item EducationFeesItem */
        if(!empty($entity->getFeesItems())){
            foreach ($entity->getFeesItems() as $item){
                $feesItems[$item->getStock()->getId()] = $item;
            }
        }
        return $this->render('EducationBundle:Fees:new.html.twig', array(
            'entity'      => $entity,
            'stocks'                => $stocks,
            'feesItems'             => $feesItems,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Particular entity.
     * @Secure(roles="ROLE_education_fees,ROLE_DOMAIN");
     */

    public function deleteAction(EducationFees $entity)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        try {

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'error',"Data has been deleted successfully"
            );

        } catch (ForeignKeyConstraintViolationException $e) {
            $this->get('session')->getFlashBag()->add(
                'notice',"Data has been relation another Table"
            );
        }catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add(
                'notice', 'Please contact system administrator further notification.'
            );
        }
        return $this->redirect($this->generateUrl('education_fees'));
    }


    /**
     * Status a Page entity.
     *
     */

    public function statusAction(EducationFees $entity)
    {

        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find District entity.');
        }
        $status = $entity->getStatus();
        if($status == 1){
            $entity->setStatus(0);
        } else{
            $entity->setStatus(1);
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'success',"Status has been changed successfully"
        );
        return $this->redirect($this->generateUrl('education_fees'));
    }

    public function inlineUpdateAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EducationBundle:EducationFees')->find($data['pk']);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find particular entity.');
        }
        if('openingQuantity' == $data['name']) {
            $setField = 'set' . $data['name'];
            $quantity = abs($data['value']);
            $entity->$setField($quantity);
            // $remainingQuantity = $entity->getRemainingQuantity() + $quantity;
            $this->getDoctrine()->getRepository('EducationBundle:EducationFees')->remainingQnt($entity);
            // $entity->setRemainingQuantity($remainingQuantity);
        }else{
            $setField = 'set' . $data['name'];
            $entity->$setField(abs($data['value']));
        }
        $em->flush();
        exit;

    }

    public function production(EducationFees $particular)
    {

    }

    public function transfer(EducationFees $particular)
    {

    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $inventory = $this->getUser()->getGlobalOption()->getEducationConfig();
            $item = $this->getDoctrine()->getRepository('EducationBundle:EducationFees')->searchAutoComplete($inventory,$item);
        }
        return new JsonResponse($item);
    }

    public function searchNameAction($Fees)
    {
        return new JsonResponse(array(
            'id'=> $Fees,
            'text'=> $Fees
        ));
    }

    public function FeesQuantityUpdateAction()
    {
        set_time_limit(0);
        ignore_user_abort(true);
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getBussinessConfig();
        $items = $this->getDoctrine()->getRepository('MedicineBundle:MedicineFees')->findBy(array('medicineConfig'=>$config));

        /* @var EducationFees $item */

        foreach ($items as $item){
            $this->getDoctrine()->getRepository('MedicineBundle:MedicineFees')->updateRemovePurchaseQuantity($item,'');
            $this->getDoctrine()->getRepository('MedicineBundle:MedicineFees')->updateRemovePurchaseQuantity($item,'sales');
            $this->getDoctrine()->getRepository('MedicineBundle:MedicineFees')->updateRemovePurchaseQuantity($item,'sales-return');
            $this->getDoctrine()->getRepository('MedicineBundle:MedicineFees')->updateRemovePurchaseQuantity($item,'purchase-return');
            $this->getDoctrine()->getRepository('MedicineBundle:MedicineFees')->updateRemovePurchaseQuantity($item,'damage');
        }
        return $this->redirect($this->generateUrl('medicine_fees'));
    }


}
