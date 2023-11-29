<?php

namespace Appstore\Bundle\DmsBundle\Controller;

use Appstore\Bundle\DmsBundle\Entity\DentalService;
use Appstore\Bundle\DmsBundle\Entity\DmsConfig;
use Appstore\Bundle\DmsBundle\Entity\DmsService;
use Appstore\Bundle\DmsBundle\Form\ConfigType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * DmsConfigController.
 *
 */
class DmsConfigController extends Controller
{


    public function manageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getUser()->getGlobalOption()->getDmsConfig();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Invoice entity preparation.');
        }
        $form = $this->createEditForm($entity);
        $pagination = $em->getRepository('DmsBundle:DmsService')->getServiceForPrescription($entity);
        return $this->render('DmsBundle:Config:manage.html.twig', array(
            'entity' => $entity,
            'pagination' => $pagination,
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
    private function createEditForm(DmsConfig $entity)
    {

        $hospitalConfig = $this->getUser()->getGlobalOption()->getDmsConfig();
        $form = $this->createForm(new ConfigType($hospitalConfig), $entity, array(
            'action' => $this->generateUrl('dms_config_update'),
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

        $entity = $this->getUser()->getGlobalOption()->getDmsConfig();
        $pagination = $em->getRepository('DmsBundle:DmsService')->getServiceForPrescription($entity);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Report has been created successfully"
            );
            $data = $request->request->all();
            $this->getDoctrine()->getRepository('DmsBundle:DmsService')->prescriptionServiceUpdate($entity,$data);
            if($entity->isCustomPrescription() == 1){
               /*// $print = '../src/Appstore/Bundle/DmsBundle/Resources/views/Print/print.html.twig';
               // $copy = '../src/Appstore/Bundle/DmsBundle/Resources/views/Print/'.$entity->getGlobalOption()->getSlug().'.html.twig';

                $srcfile='src/Appstore/Bundle/DmsBundle/Resources/views/Print/print.html.twig';
                echo $dstfile='src/Appstore/Bundle/DmsBundle/Resources/views/Print/'.$entity->getGlobalOption()->getSlug().'.html.twig';
               // mkdir(dirname($dstfile), 0777, true);
              //  copy($srcfile, $dstfile);


               // copy($print,$copy);
               // chmod($copy,0777);*/
            }
            return $this->redirect($this->generateUrl('dms_config_manage'));
        }

        return $this->render('DmsBundle:Config:manage.html.twig', array(
            'entity'        => $entity,
            'pagination'    => $pagination,
            'form'          => $editForm->createView(),
        ));
    }

    public function resetDefaultServiceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getDmsConfig();
        $entities = $this->getDoctrine()->getRepository('DmsBundle:DentalService')->findBy(array('status'=>1));
        /* @var $entity DentalService */
        foreach ($entities as $entity){
            $exist = $this->getDoctrine()->getRepository('DmsBundle:DmsService')->findOneBy(array('dmsConfig'=>$config,'dentalService'=>$entity));
            if(empty($exist)){
                $service = new DmsService();
                $service->setName(trim($entity->getName()));
                if($entity->getSlug() != 'other-service'){
                    $service->setDentalService($entity);
                }
                $service->setSlug(trim($entity->getSlug()));
                $service->setServiceFormat(trim($entity->getSlug()));
                $service->setStatus(1);
                $service->setDmsConfig($config);
                $em->persist($service);
                $em->flush();
            }

        }
        return $this->redirect($this->generateUrl('dms_config_manage'));
    }

}

