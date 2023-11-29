<?php

namespace Appstore\Bundle\AssetsBundle\Controller;


use Appstore\Bundle\AssetsBundle\Entity\Brand;
use Appstore\Bundle\AssetsBundle\Entity\Club;
use Appstore\Bundle\AssetsBundle\Form\BrandType;
use Appstore\Bundle\AssetsBundle\Form\ClubType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Brand controller.
 *
 */
class ClubController extends Controller
{

    /**
     * Lists all Brand entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
        $entities = $em->getRepository('AssetsBundle:Club')->findBy(array('config'=>$config),array('name'=>'asc'));
        return $this->render('AssetsBundle:Club:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Brand entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Club();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
            $entity->setConfig($config);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('assets_club'));
        }

        return $this->render('AssetsBundle:Club:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Brand entity.
     *
     * @param Brand $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Club $entity)
    {
        $form = $this->createForm(new ClubType(), $entity, array(
            'action' => $this->generateUrl('assets_club_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * Displays a form to create a new Brand entity.
     *
     */
    public function newAction()
    {
        $entity = new Club();
        $form   = $this->createCreateForm($entity);
        return $this->render('AssetsBundle:Club:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Brand entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AssetsBundle:Club')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }
       return $this->render('AssetsBundle:Club:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Brand entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AssetsBundle:Club')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('AssetsBundle:Club:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Brand entity.
    *
    * @param Brand $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Club $entity)
    {
        $form = $this->createForm(new ClubType(), $entity, array(
            'action' => $this->generateUrl('assets_club_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
         return $form;
    }
    /**
     * Edits an existing Brand entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AssetsBundle:Club')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
            return $this->redirect($this->generateUrl('assets_club', array('id' => $id)));
        }

        return $this->render('AssetsBundle:Club:new.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Brand entity.
     *
     */
    public function deleteAction(Club $entity)
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

        return $this->redirect($this->generateUrl('assets_club'));
    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
          $config = $this->getUser()->getGlobalOption();
          $item = $this->getDoctrine()->getRepository('AssetsBundle:Club')->searchAutoComplete($item,$config);
        }
        return new JsonResponse($item);
    }

    public function searchBrandNameAction($brand)
    {
        return new JsonResponse(array(
            'id'=> $brand,
            'text'=> $brand
        ));
    }
}
