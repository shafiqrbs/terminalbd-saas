<?php

namespace Appstore\Bundle\HotelBundle\Controller;
use Appstore\Bundle\HotelBundle\Entity\HotelOption;
use Appstore\Bundle\HotelBundle\Form\OptionType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Option controller.
 *
 */
class OptionController extends Controller
{

    /**
     * Lists all HotelOption entities.
     *
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     *
     */
    public function indexAction()
    {

        $entity = new HotelOption();
        $em = $this->getDoctrine()->getManager();
        $option = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $em->getRepository('HotelBundle:HotelOption')->findBy(array('hotelConfig' => $option),array('hotelParticularType' =>'asc' ));
        $form   = $this->createCreateForm($entity);
        return $this->render('HotelBundle:Option:index.html.twig', array(
            'entity' => $entity,
            'entities' => $entities,
            'form'   => $form->createView(),
        ));


    }


    /**
     * Creates a new HotelOption entity.
     *
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $option = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $em->getRepository('HotelBundle:HotelOption')->findBy(array('hotelConfig' => $option),array('hotelParticularType' =>'asc'  ));

        $entity = new HotelOption();
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
            return $this->redirect($this->generateUrl('hotel_option', array('id' => $entity->getId())));
        }

        return $this->render('HotelBundle:Option:index.html.twig', array(
            'entity' => $entity,
            'entities'      => $entities,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a HotelOption entity.
     *
     * @param HotelOption $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(HotelOption $entity)
    {

        $form = $this->createForm(new OptionType(), $entity, array(
            'action' => $this->generateUrl('hotel_option_create', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }



    /**
     * Displays a form to edit an existing HotelOption entity.
     *
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $option = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $em->getRepository('HotelBundle:HotelOption')->findBy(array('hotelConfig' => $option),array('hotelParticularType' =>'asc'  ));

        $entity = $em->getRepository('HotelBundle:HotelOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HotelOption entity.');
        }
        $editForm = $this->createEditForm($entity);

        return $this->render('HotelBundle:Option:index.html.twig', array(
            'entity'      => $entity,
            'entities'      => $entities,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a HotelOption entity.
     *
     * @param HotelOption $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(HotelOption $entity )
    {

        $form = $this->createForm(new OptionType(), $entity, array(
            'action' => $this->generateUrl('hotel_option_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => array(
                'class' => 'horizontal-form',
                'novalidate' => 'novalidate',
            )
        ));


        return $form;
    }
    /**
     * Edits an existing HotelOption entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $option = $this->getUser()->getGlobalOption()->getHotelConfig();
        $entities = $em->getRepository('HotelBundle:HotelOption')->findBy(array('hotelConfig' => $option),array('hotelParticularType' =>'asc' ));
        $entity = $em->getRepository('HotelBundle:HotelOption')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HotelOption entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',"Data has been updated successfully"
            );
            return $this->redirect($this->generateUrl('hotel_option'));
        }

        return $this->render('HotelBundle:Option:index.html.twig', array(
            'entity'      => $entity,
            'entities'      => $entities,
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a HotelOption entity.
     *
     * @Secure(roles="ROLE_HOTEL_STOCK,ROLE_DOMAIN");
     *
     */
    public function deleteAction(HotelOption $entity)
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
        return $this->redirect($this->generateUrl('hotel_option'));
    }

    public function sortedAction(Request $request)
    {
        $data = $request ->request->get('item');
        $this->getDoctrine()->getRepository('HotelBundle:HotelOption')->setHotelOptionSorting($data);
        exit;
    }


}
