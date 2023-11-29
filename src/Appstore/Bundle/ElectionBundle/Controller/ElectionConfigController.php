<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Form\ConfigType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * ElectionConfigController.
 *
 */
class ElectionConfigController extends Controller
{


    public function manageAction()
    {


        $em = $this->getDoctrine()->getManager();
        $entity = $this->getUser()->getGlobalOption()->getElectionConfig();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity preparation.');
        }
        $form = $this->createEditForm($entity);
        return $this->render('ElectionBundle:Config:manage.html.twig', array(
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
    private function createEditForm(ElectionConfig $entity)
    {

        $config = $this->getUser()->getGlobalOption()->getElectionConfig();
        $form = $this->createForm(new ConfigType($config), $entity, array(
            'action' => $this->generateUrl('business_config_update'),
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

        /* @var $entity ElectionConfig */
        $entity = $this->getUser()->getGlobalOption()->getElectionConfig();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
           /* if($entity->getRemoveImage() == 1 ){
                $entity->removeUpload();
            }*/
            $entity->upload();
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been created successfully"
            );
            return $this->redirect($this->generateUrl('business_config_manage'));
        }

        return $this->render('ElectionBundle:Config:manage.html.twig', array(
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

