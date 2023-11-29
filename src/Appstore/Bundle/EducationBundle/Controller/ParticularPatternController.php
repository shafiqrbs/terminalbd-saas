<?php

namespace Appstore\Bundle\EducationBundle\Controller;

use Appstore\Bundle\EducationBundle\Entity\EducationParticularPattern;
use Appstore\Bundle\EducationBundle\Form\ParticularPatternType;
use Appstore\Bundle\EducationBundle\Form\ParticularType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Particular controller.
 *
 */
class ParticularPatternController extends Controller
{

    /**
     * Lists all Particular entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new EducationParticularPattern();
        $form = $this->createCreateForm($entity);
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticularPattern' )->findBy(array( 'educationConfig' => $config));
        return $this->render('EducationBundle:ParticularPattern:index.html.twig', array(
            'entities' => $entities,
            'config' => $config,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Creates a new EducationParticularPattern entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new EducationParticularPattern();
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticularPattern' )->findBy(array( 'educationConfig' => $config));
        $form = $this->createCreateForm($entity);
        $data = $request->request->all();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(isset($data['class']) and !empty($data['class'])){
                $entity->setStudentClass($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['class']));
            }
            if(isset($data['shift']) and !empty($data['shift'])){
                $entity->setStudentShift($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['shift']));
            }
            if(isset($data['section']) and !empty($data['section'])){
                $entity->setStudentSection($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['section']));
            }
            if(isset($data['class']) and !empty($data['class'])){
                $entity->setStudentClass($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['class']));
            }
            if(isset($data['group']) and !empty($data['group'])){
                $entity->setStudentGroup($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['group']));
            }
            if(isset($data['department']) and !empty($data['department'])){
                $entity->setStudentDepartment($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['department']));
            }
            if(isset($data['division']) and !empty($data['division'])){
                $entity->setStudentDivision($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['division']));
            }
            if(isset($data['branch']) and !empty($data['branch'])){
                $entity->setStudentBranch($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['branch']));
            }
            if(isset($data['version']) and !empty($data['version'])){
                $entity->setStudentVersion($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['version']));
            }
            if(isset($data['semester']) and !empty($data['semester'])){
                $entity->setStudentSemester($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['semester']));
            }
            if(isset($data['subject']) and !empty($data['subject'])){
                $entity->setStudentSubject($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['subject']));
            }
            if(isset($data['session']) and !empty($data['session'])){
                $entity->setStudentSession($this->getDoctrine()->getRepository('EducationBundle:EducationParticular')->find($data['session']));
            }
            $config = $this->getUser()->getGlobalOption()->getEducationConfig();
            $entity->setEducationConfig($config);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been inserted successfully"
            );
            return $this->redirect($this->generateUrl('education_particularpattern'));
        }
        return $this->render('EducationBundle:ParticularPattern:index.html.twig', array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Particular entity.
     *
     * @param EducationParticularPattern $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EducationParticularPattern $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getEducationConfig();
    	$form = $this->createForm(new ParticularPatternType($config), $entity, array(
            'action' => $this->generateUrl('education_particularpattern_create'),
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
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticularPattern' )->findBy(array( 'educationConfig' => $config),array( 'particularType' =>'ASC'));

        $entity = $em->getRepository( 'EducationBundle:EducationParticularPattern' )->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('EducationBundle:ParticularPattern:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Particular entity.
    *
    * @param Particular $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EducationParticularPattern $entity)
    {
	    $config = $this->getUser()->getGlobalOption()->getEducationConfig();
    	$form = $this->createForm(new ParticularType($config), $entity, array(
            'action' => $this->generateUrl('education_particularpattern_update', array('id' => $entity->getId())),
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
        $config = $this->getUser()->getGlobalOption()->getEducationConfig();
        $entities = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticularPattern' )->findBy(array( 'educationConfig' => $config),array( 'particularType' =>'ASC'));

        $entity = $em->getRepository( 'EducationBundle:EducationParticularPattern' )->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Particular entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been changed successfully"
            );
            return $this->redirect($this->generateUrl('education_particularpattern'));
        }

        return $this->render('EducationBundle:ParticularPattern:index.html.twig', array(
            'entities'      => $entities,
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Particular entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository( 'EducationBundle:EducationParticularPattern' )->find($id);

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

        return $this->redirect($this->generateUrl('education_particularpattern'));
    }


    /**
     * Status a Page entity.
     *
     */
    public function statusAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository( 'EducationBundle:EducationParticularPattern' )->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Education Particular entity.');
        }
        $status = $entity->isStatus();
        if($status == 1){
            $entity->setStatus(false);
        } else{
            $entity->setStatus(true);
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'success',"Status has been changed successfully"
        );
        return $this->redirect($this->generateUrl('education_particularpattern'));
    }

    public function autoSearchAction(Request $request)
    {
        $item = $_REQUEST['q'];
        if ($item) {
            $inventory = $this->getUser()->getGlobalOption()->getEducationConfig();
            $item = $this->getDoctrine()->getRepository( 'EducationBundle:EducationParticularPattern' )->searchAutoComplete($item,$inventory);
        }
        return new JsonResponse($item);
    }

    public function searchParticularNameAction($vendor)
    {
        return new JsonResponse(array(
            'id' => $vendor,
            'text'  =>  $vendor
        ));
    }

}
