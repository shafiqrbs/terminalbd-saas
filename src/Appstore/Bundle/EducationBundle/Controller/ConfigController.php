<?php

namespace Appstore\Bundle\EducationBundle\Controller;

use Appstore\Bundle\EducationBundle\Entity\EducationConfig;
use Appstore\Bundle\EducationBundle\Entity\EducationParticular;
use Appstore\Bundle\EducationBundle\Form\ConfigType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * EducationConfigController.
 *
 */
class ConfigController extends Controller
{


    public function manageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getUser()->getGlobalOption()->getEducationConfig();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity preparation.');
        }
        $form = $this->createEditForm($entity);
        return $this->render('EducationBundle:Config:manage.html.twig', array(
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
    private function createEditForm(EducationConfig $entity)
    {

        $form = $this->createForm(new ConfigType(), $entity, array(
            'action' => $this->generateUrl('education_config_update'),
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

        /* @var $entity EducationConfig */

		$entity = $this->getUser()->getGlobalOption()->getEducationConfig();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
	    $file = $request->files->all();

	    if ($editForm->isValid()) {
	        if($entity->getRemoveImage() == 1 ){
		        $entity->removeUpload();
	        }
	        $entity->upload();
        	$em->flush();
	        $this->get('session')->getFlashBag()->add(
                'success',"Data has been created successfully"
            );
            return $this->redirect($this->generateUrl('education_config_manage'));
        }

        return $this->render('EducationBundle:Config:manage.html.twig', array(
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

