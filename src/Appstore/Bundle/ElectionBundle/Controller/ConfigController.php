<?php

namespace Appstore\Bundle\ElectionBundle\Controller;

use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionParticular;
use Appstore\Bundle\ElectionBundle\Form\ConfigType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * ElectionConfigController.
 *
 */
class ConfigController extends Controller
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

        $location = $this->getDoctrine()->getRepository('SettingLocationBundle:Location');
        $form = $this->createForm(new ConfigType($location), $entity, array(
            'action' => $this->generateUrl('election_config_update'),
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
	    $file = $request->files->all();

	    if ($editForm->isValid()) {
	        if($entity->getRemoveImage() == 1 ){
		        $entity->removeUpload();
	        }
	        if(isset($data['removeLogo']) || (isset($file['logo']) && !empty($entity->getLogo()))  ){
		        exit;
	        	$entity->removeLogo();
		        $entity->setLogo(NULL);
	        }

	        $entity->upload();
        	$em->flush();
	        $this->getDoctrine()->getRepository('ElectionBundle:ElectionConfig')->fileUploader($entity,$file);

	        $this->get('session')->getFlashBag()->add(
                'success',"Data has been created successfully"
            );
            $this->getDoctrine()->getRepository('ElectionBundle:ElectionLocation')->initialDistrict($entity);
            return $this->redirect($this->generateUrl('election_config_manage'));
        }

        return $this->render('ElectionBundle:Config:manage.html.twig', array(
            'entity'        => $entity,
            'form'          => $editForm->createView(),
        ));
    }

    public function restoreMasterParticularAction()
    {

	    $em = $this->getDoctrine()->getManager();
    	$config = $this->getUser()->getGlobalOption()->getElectionConfig();
		$entities = $this->getDoctrine()->getRepository('ElectionBundle:ElectionParticularMaster')->findBy(array('status'=>1));
	    foreach ($entities as $entity){
	        $exist = $this->getDoctrine()->getRepository( 'ElectionBundle:ElectionParticular' )->findOneBy(array( 'electionConfig' => $config , 'defineSlug' => $entity->getSlug(), 'particularType' => $entity->getParticularType()));
	        if(empty($exist)){
		        $particular = new ElectionParticular();
		        $particular->setElectionConfig($config);
		        $particular->setParticularType($entity->getParticularType());
		        $particular->setName($entity->getName());
		        $particular->setDefineSlug($entity->getSlug());
		        $particular->setStatus(true);
		        $em->persist($particular);
		        $em->flush();
	        }
	   }
	   return $this->redirect($this->generateUrl('election_config_manage'));
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

