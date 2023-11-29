<?php

namespace Appstore\Bundle\AssetsBundle\Controller;

use Appstore\Bundle\AssetsBundle\Entity\DepreciationBatch;
use Appstore\Bundle\AssetsBundle\Entity\Product;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Appstore\Bundle\AssetsBundle\Entity\DepreciationModel;
use Appstore\Bundle\AssetsBundle\Form\DepreciationModelType;

/**
 * DepreciationModel controller.
 *
 */
class DepreciationModelController extends Controller
{

	/**
	 * Lists all DepreciationModel entities.
	 *
	 */
	public function indexAction()
	{
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
        $depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->findOneBy(array("config"=>$config));
        $entities = $em->getRepository( 'AssetsBundle:DepreciationModel' )->findBy(array('config'=>$config),array( 'id' =>'DESC'));
		$existDepreciation = $em->getRepository('AssetsBundle:DepreciationBatch')->existDepreciation($config->getId());

		return $this->render('AssetsBundle:DepreciationModel:index.html.twig', array(
			'depreciation' => $depreciation,
			'existDepreciation' => $existDepreciation,
			'entities' => $entities,
		));
	}
	/**
	 * Creates a new DepreciationModel entity.
	 *
	 */
	public function createAction(Request $request)
	{
		$entity = new DepreciationModel();
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
			return $this->redirect($this->generateUrl('assets_model'));
		}
		$depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->find(1);

		return $this->render('AssetsBundle:DepreciationModel:new.html.twig', array(
			'entity' => $entity,
			'depreciation' => $depreciation,
			'form'   => $form->createView(),
		));
	}

	/**
	 * Creates a form to create a DepreciationModel entity.
	 *
	 * @param DepreciationModel $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCreateForm(DepreciationModel $entity)
	{
		$option = $this->getUser()->getGlobalOption();
		$depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->find(1);
		$em = $this->getDoctrine()->getRepository('AssetsBundle:Category');
		$form = $this->createForm(new DepreciationModelType($em,$option,$depreciation), $entity, array(
			'action' => $this->generateUrl('assets_model_create'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}

	/**
	 * Displays a form to create a new DepreciationModel entity.
	 *
	 */
	public function newAction()
	{
		$entity = new DepreciationModel();
		$form   = $this->createCreateForm($entity);
		$depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->find(1);

		return $this->render('AssetsBundle:DepreciationModel:new.html.twig', array(
			'entity' => $entity,
			'depreciation' => $depreciation,
			'form'   => $form->createView(),
		));
	}


	/**
	 * Displays a form to edit an existing DepreciationModel entity.
	 *
	 */



	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AssetsBundle:DepreciationModel' )->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find DepreciationModel entity.');
		}

		$editForm = $this->createEditForm($entity);
		$depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->find(1);

		return $this->render('AssetsBundle:DepreciationModel:new.html.twig', array(
			'entity'      => $entity,
			'depreciation'      => $depreciation,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Creates a form to edit a DepreciationModel entity.
	 *
	 * @param DepreciationModel $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(DepreciationModel $entity)
	{
        $option = $this->getUser()->getGlobalOption();
		$em = $this->getDoctrine()->getRepository('AssetsBundle:Category');
		$depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->find(1);

		$form = $this->createForm(new DepreciationModelType($em,$option,$depreciation), $entity, array(
			'action' => $this->generateUrl('assets_model_update', array('id' => $entity->getId())),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form-horizontal',
				'novalidate' => 'novalidate',
			)
		));
		return $form;
	}
	/**
	 * Edits an existing DepreciationModel entity.
	 *
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository( 'AssetsBundle:DepreciationModel' )->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find DepreciationModel entity.');
		}

		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->flush();
			$this->get('session')->getFlashBag()->add(
				'success',"Data has been updated successfully"
			);
			return $this->redirect($this->generateUrl('assets_model'));
		}
		$depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->find(1);

		return $this->render('AssetsBundle:DepreciationModel:new.html.twig', array(
			'entity'      => $entity,
			'depreciation'      => $depreciation,
			'form'   => $editForm->createView(),
		));
	}

	/**
	 * Deletes a DepreciationModel entity.
	 *
	 */
	public function deleteAction(DepreciationModel $entity)
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
		return $this->redirect($this->generateUrl('assets_model'));
	}

    /**
     * Finds and displays a DepreciationModel entity.
     *
     */
    public function depreciationAction(DepreciationModel $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig()->getId();
        set_time_limit(0);
        ignore_user_abort(true);

        $data = array();
        if(!empty($entity)){
            if($entity->getItem() and $entity->getCategory()) {
                $data = array('item' => $entity->getItem()->getId(),'category' => $entity->getCategory()->getId());
            }elseif($entity->getItem()){
                $data = array('item' => $entity->getItem()->getId());
            }elseif($entity->getCategory()){
                $data = array('category' => $entity->getCategory()->getId());
            }
        }
        $this->getDoctrine()->getRepository('AssetsBundle:Product')->updateDepreciationModelProduct($entity,$data);
        $this->get('session')->getFlashBag()->add(
            'success',"Depreciation rate has been added successfully"
        );
        return $this->redirect($this->generateUrl('assets_model'));
    }

    public function generateAction(DepreciationModel $entity)
	{
		$em = $this->getDoctrine()->getManager();
		set_time_limit(0);
		ignore_user_abort(true);
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig()->getId();
		$depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->findOneBy(array('config'=>$config));
		$data = array('depreciation' => $entity->getId(),'effectedDate' => date("d-m-Y"));
		$products = $this->getDoctrine()->getRepository('AssetsBundle:Product')->findWithSearch($config,$data);

		/* @var $product Product */

		if($products->getQuery()->getResult()){

            $batch = $this->getDoctrine()->getRepository('AssetsBundle:DepreciationBatch')->insertBatch($entity);
			foreach ($products->getQuery()->getResult() as $product):

			if($depreciation->getPolicy() == 'straight-line'){

                if($product->getStraightLineValue() > 0){
                    $product->getStraightLineValue();
	                $currentBookValue = ($product->getBookValue() - $product->getStraightLineValue());
					$product->setBookValue(doubleval($currentBookValue));
					$depValue = ($product->getDepreciationValue() + $product->getStraightLineValue());
					$product->setDepreciationValue($depValue);
                    $nextDepreciation = $this->updateNextDepreciationDate($product->getDepreciationEffectedDate()->format('Y-m-d'));
                    $product->setDepreciationEffectedDate($nextDepreciation);
                    $em->persist($product);
					$em->flush();
					$this->getDoctrine()->getRepository('AssetsBundle:ProductLedger')->insertProductDepreciation($batch,$product,$product->getStraightLineValue());

				}else{

					$this->generateStraightLineValue($entity,$batch,$product);
				}

			}else{

				if($product->getReducingBalancePercentage()){
				    $product->getReducingBalancePercentage();
					$depValue = ($product->getBookValue() * $product->getReducingBalancePercentage())/100;
					$bookValue = ($product->getBookValue() - $depValue);
					$product->setBookValue($bookValue);
					$depValue = ($product->getDepreciationValue() + $depValue);
					$product->setDepreciationValue($depValue);
                    $nextDepreciation = $this->updateNextDepreciationDate($product->getDepreciationEffectedDate()->format('Y-m-d'));
                    $product->setDepreciationEffectedDate($nextDepreciation);
                    $em->persist($product);
                    $em->flush();
					$this->getDoctrine()->getRepository('AssetsBundle:ProductLedger')->insertProductDepreciation($batch,$product,$depValue);

				}else{

					$this->generateReducingBalance($entity,$batch,$product);
				}
			}
			endforeach;
			$this->getDoctrine()->getRepository('AssetsBundle:DepreciationBatch')->updateBatch($batch);
			$this->get('session')->getFlashBag()->add(
				'success',"Depreciation has been generated successfully"
			);
		}
		return $this->redirect($this->generateUrl('assets_model'));
	}

	public function generateStraightLineValue(DepreciationModel $model, DepreciationBatch $batch ,Product $product)
	{
        $em = $this->getDoctrine()->getManager();
	    $depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->find(1);
		$straightLine = (($product->getPurchasePrice() - $product->getSalvageValue()) / $model->getDepreciationYear());
		$straightValue = 0;

		if( $depreciation->getDepreciationPulse() == 'monthly'){
			$month = ($model->getDepreciationYear()*12);
			$straightValue = ($straightLine/$month);
		}elseif( $depreciation->getDepreciationPulse() == 'quarterly'){
			$month = ($model->getDepreciationYear()*4);
			$straightValue = ($straightLine/$month);
		}elseif( $depreciation->getDepreciationPulse() == 'half-year'){
			$month = ($model->getDepreciationYear()*2);
			$straightValue = ($straightLine/$month);
		}else{
			$straightValue = $straightLine;
		}
		$bookValue = ($product->getPurchasePrice() - ($product->getSalvageValue() + $straightValue));
		$product->setBookValue(doubleval($bookValue));
		$product->setDepreciationValue(doubleval($straightValue));
		$product->setStraightLineValue($straightValue);
		$straightPercentage = (($straightValue * 100)/$product->getPurchasePrice());
		$product->setStraightLinePercentage($straightPercentage);
		$product->setDepreciationRate($straightPercentage);
        $nextDepreciation = $this->updateNextDepreciationDate($product->getDepreciationEffectedDate()->format('Y-m-d'));
        $product->setDepreciationEffectedDate($nextDepreciation);
        $em->persist($product);
		$em->flush();
		$this->getDoctrine()->getRepository('AssetsBundle:ProductLedger')->insertProductDepreciation($batch,$product,$straightValue);

	}

	public function generateReducingBalance(DepreciationModel $model , DepreciationBatch $batch ,Product $product)
	{
        $em = $this->getDoctrine()->getManager();

        $depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->find(1);
		$straightValue = 0;
		if( $depreciation->getDepreciationPulse() == 'monthly'){
			$rate = ($model->getRate()/12);
		}elseif( $depreciation->getDepreciationPulse() == 'quarterly'){
			$rate = ($model->getRate()/4);
		}elseif( $depreciation->getDepreciationPulse() == 'half-year'){
			$rate = ($model->getRate()/2);
		}else{
			$rate = $model->getRate();
		}

		$depValue = ((($product->getPurchasePrice() - $product->getSalvageValue()) * $rate)/100);
		$bookValue = ($product->getPurchasePrice() - ($product->getSalvageValue() + $depValue));
		$product->setBookValue(doubleval($bookValue));
		$product->setDepreciationValue(doubleval($depValue));
		$product->setReducingBalancePercentage($rate);
		$product->setDepreciationRate($rate);
		$nextDepreciation = $this->updateNextDepreciationDate($product->getDepreciationEffectedDate()->format('Y-m-d'));
		$product->setDepreciationEffectedDate($nextDepreciation);
		$em->persist($product);
		$em->flush();
		$this->getDoctrine()->getRepository('AssetsBundle:ProductLedger')->insertProductDepreciation($batch,$product,$depValue);

	}

	public function updateNextDepreciationDate($depreciationDate)
    {

        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
        $depreciation = $this->getDoctrine()->getRepository('AssetsBundle:Depreciation')->findOneBy(array('config'=>$config));
        if( $depreciation->getDepreciationPulse() == 'monthly'){
            $effectiveDate = date('Y-m-t', strtotime("+1 month -1 day", strtotime($depreciationDate)));
		}elseif($depreciation->getDepreciationPulse() == 'quarterly'){
            $effectiveDate = date('Y-m-t', strtotime("+4 months -1 day", strtotime($depreciationDate)));
        }elseif($depreciation->getDepreciationPulse() == 'half-year'){
            $effectiveDate = date('Y-m-t', strtotime("+6 months -1 day", strtotime($depreciationDate)));
        }else{
            $effectiveDate = date('Y-m-t', strtotime("+12 months -1 day", strtotime($depreciationDate)));
        }
        return $date = new  \DateTime($effectiveDate);

    }




}
