<?php

namespace Appstore\Bundle\SettingToolBundle\Controller;



use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * TaxTariff controller.
 *
 */
class TaxTariffController extends Controller
{

    public function paginate($entities)
    {
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            50  /*limit per page*/
        );
        $pagination->setTemplate('SettingToolBundle:Widget:pagination.html.twig');
        return $pagination;
    }


    /**
     * Lists all TaxTariff entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption();
        $data = $_REQUEST;
        $entities = $em->getRepository('SettingToolBundle:TaxTariff')->findWidthSearch($data);
        $pagination = $this->paginate($entities);
        return $this->render('SettingToolBundle:TaxTariff:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new TaxTariff entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Brand();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $config = $this->getUser()->getGlobalOption();
            $entity->setGlobalOption($config);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('TaxTariff'));
        }

        return $this->render('SettingToolBundle:TaxTariffUpload:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a TaxTariff entity.
     *
     * @param Brand $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TaxTariff $entity)
    {
        $form = $this->createForm(new TaxTariffType(), $entity, array(
            'action' => $this->generateUrl('taxtariff_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Displays a form to create a new TaxTariff entity.
     *
     */
    public function newAction()
    {
        $entity = new TaxTariff();
        $form   = $this->createCreateForm($entity);

        return $this->render('SettingToolBundle:TaxTariff:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TaxTariff entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Brand.php')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaxTariff entity.');
        }

       return $this->render('SettingToolBundle:TaxTariff:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing TaxTariff entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Brand.php')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaxTariff entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('SettingToolBundle:TaxTariff:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a TaxTariff entity.
    *
    * @param Brand $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Brand $entity)
    {
        $form = $this->createForm(new TaxTariffType(), $entity, array(
            'action' => $this->generateUrl('taxtariff_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
         return $form;
    }
    /**
     * Edits an existing TaxTariff entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SettingToolBundle:TaxTariff')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaxTariff entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
            return $this->redirect($this->generateUrl('TaxTariff_edit', array('id' => $id)));
        }

        return $this->render('SettingToolBundle:TaxTariff:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a TaxTariff entity.
     *
     */
    public function deleteAction(TaxTariff $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
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

        return $this->redirect($this->generateUrl('TaxTariff'));
    }

    public function inlineUpdateAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SettingToolBundle:TaxTariff')->find($data['pk']);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PurchaseItem entity.');
        }
        if(!empty($data['name']) and 0 < (float)$data['value']){
            $process = 'set'.$data['name'];
            $entity->$process((float)$data['value']);
            $total = $this->getDoctrine()->getRepository('SettingToolBundle:TaxTariff')->totalTax($entity);
            $entity->setTotalTaxIncidence($total);
            $em->flush();
        }
        exit;

    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $config = $this->getUser()->getGlobalOption();
            $item = $this->getDoctrine()->getRepository('SettingToolBundle:TaxTariff')->searchAutoComplete($item,$config);
        }
        return new JsonResponse($item);
    }

    public function searchNameAction($name)
    {
        return new JsonResponse(array(
            'id'=> $name,
            'text'=> $name
        ));
    }

}
