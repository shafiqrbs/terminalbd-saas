<?php

namespace Appstore\Bundle\HotelBundle\Controller;

use Appstore\Bundle\HotelBundle\Entity\HotelConfig;
use Appstore\Bundle\HotelBundle\Form\ConfigType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * HotelConfigController.
 *
 */
class HotelConfigController extends Controller
{


    public function manageAction()
    {


        $em = $this->getDoctrine()->getManager();
        $entity = $this->getUser()->getGlobalOption()->getHotelConfig();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity preparation.');
        }
        $form = $this->createEditForm($entity);
        return $this->render('HotelBundle:Config:manage.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to edit a Invoice entity.wq
     *
     * @param Invoice $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(HotelConfig $entity)
    {

        $config = $this->getUser()->getGlobalOption()->getHotelConfig();
        $form = $this->createForm(new ConfigType($config), $entity, array(
            'action' => $this->generateUrl('hotel_config_update'),
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
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->getUser()->getGlobalOption()->getHotelConfig();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if($entity->getRemoveImage() == 1 ){
                $entity->removeUpload();
            }elseif($entity->getRemoveImage() != 1) {
                $entity->upload();
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Report has been created successfully"
            );
            return $this->redirect($this->generateUrl('hotel_config_manage'));
        }

        return $this->render('HotelBundle:Config:manage.html.twig', array(
            'entity'        => $entity,
            'form'          => $editForm->createView(),
        ));
    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $go = $this->getUser()->getGlobalOption();
            $item = $this->getDoctrine()->getRepository('UserBundle:User')->searchAutoComplete($item,$go);
        }
        return new JsonResponse($item);
    }

    public function searchUserNameAction($user)
    {
        return new JsonResponse(array(
            'id'=>$user,
            'text'=>$user
        ));
    }
}

