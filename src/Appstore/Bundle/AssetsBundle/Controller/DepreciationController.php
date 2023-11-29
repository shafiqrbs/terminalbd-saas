<?php

namespace Appstore\Bundle\AssetsBundle\Controller;

use Appstore\Bundle\AssetsBundle\Entity\Depreciation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Appstore\Bundle\AssetsBundle\Form\DepreciationType;

/**
 * Depreciation controller.
 *
 */
class DepreciationController extends Controller
{


	public function editAction()
	{
		$em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
		$entity = $em->getRepository( 'AssetsBundle:Depreciation' )->findOneBy(array('config'=>$config));
		if (!$entity) {
            $entity = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->insertDefaultDepreciation($config);
		}
		$editForm = $this->createEditForm($entity);
		return $this->render('AssetsBundle:Depreciation:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a Depreciation entity.
	 *
	 * @param Depreciation $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(Depreciation $entity)
	{
		$form = $this->createForm(new DepreciationType(), $entity, array(
			'action' => $this->generateUrl('assets_depreciation_update'),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing Depreciation entity.
	 *
	 */
	public function updateAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
		$entity = $em->getRepository( 'AssetsBundle:Depreciation' )->findOneBy(array('config' => $config));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Depreciation entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been updated successfully"
			);
			$this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->updateDefaultDepreciation($entity);
			return $this->redirect($this->generateUrl('assets_depreciation'));
		}
		return $this->render('AssetsBundle:Depreciation:new.html.twig', array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		));
	}


}
