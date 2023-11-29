<?php

namespace Appstore\Bundle\HotelBundle\Controller;

use Appstore\Bundle\HotelBundle\Form\CategoryType;
use Appstore\Bundle\HotelBundle\Entity\Category;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     *
     */
    public function indexAction()
    {

        $entity = new Category();
        $em = $this->getDoctrine()->getManager();
        $option = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $em->getRepository('HotelBundle:Category')->findBy(array('hotelConfig' => $option),array( 'name' =>'asc' ));
        $form   = $this->createCreateForm($entity);
        return $this->render('HotelBundle:Category:index.html.twig', array(
            'entity' => $entity,
            'entities' => $entities,
            'form'   => $form->createView(),
        ));


    }


    /**
     * Creates a new Category entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $option = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $em->getRepository('HotelBundle:Category')->findBy(array('hotelConfig' => $option),array( 'name' =>'asc' ));

        $entity = new Category();
        $globalOption = $this->getUser()->getGlobalOption();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $entity->setHotelConfig($globalOption->getHotelConfig());
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('hotel_category', array('id' => $entity->getId())));
        }

        return $this->render('HotelBundle:Category:index.html.twig', array(
            'entity' => $entity,
            'entities'      => $entities,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Category entity.
     *
     * @param Category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Category $entity)
    {

        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('hotel_category_create', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }



    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $option = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $em->getRepository('HotelBundle:Category')->findBy(array('hotelConfig' => $option),array( 'name' =>'asc' ));

        $entity = $em->getRepository('HotelBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('HotelBundle:Category:index.html.twig', array(
            'entity'      => $entity,
            'entities'      => $entities,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Category entity.
     *
     * @param Category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Category $entity )
    {

        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('hotel_category_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));


        return $form;
    }
    /**
     * Edits an existing Category entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $option = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $em->getRepository('HotelBundle:Category')->findBy(array('hotelConfig' => $option),array( 'name' =>'asc' ));
        $entity = $em->getRepository('HotelBundle:Category')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
            return $this->redirect($this->generateUrl('hotel_category'));
        }

        return $this->render('HotelBundle:Category:index.html.twig', array(
            'entity'      => $entity,
            'entities'      => $entities,
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Category entity.
     *
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     *
     */
    public function deleteAction(Category $entity)
    {
        $em = $this->getDoctrine()->getManager();
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
        return $this->redirect($this->generateUrl('hotel_category'));
    }

    public function sortedAction(Request $request)
    {
        $data = $request ->request->get('item');
        $this->getDoctrine()->getRepository('HotelBundle:Category')->setCategorySorting($data);
        exit;
    }


}
