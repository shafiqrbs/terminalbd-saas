<?php

namespace Appstore\Bundle\AssetsBundle\Controller;

use Appstore\Bundle\AssetsBundle\Entity\ItemImport;
use Appstore\Bundle\AssetsBundle\Form\ItemImportType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Appstore\Bundle\InventoryBundle\Entity\ItemColor;
use Setting\Bundle\ToolBundle\Form\ColorType;

/**
 * ItemColor controller.
 *
 */
class ItemImportController extends Controller
{


    public function paginate($entities)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            25  /*limit per page*/
        );
        $pagination->setTemplate('MedicineBundle:Widget:pagination.html.twig');
        return $pagination;
    }



    /**
     * Lists all ItemImport entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
        $entities = $em->getRepository('AssetsBundle:ItemImport')->findBy(array('assetsConfig'=>$config),array('updated'=>'DESC'));
        return $this->render('AssetsBundle:ItemImport:index.html.twig', array(
            'entities' => $entities,
        ));
    }


    /**
     * Creates a new ItemImport entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ItemImport();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $data = $request->request->all();
        $config = $this->getUser()->getGlobalOption()->getAssetsConfig();
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            /** @var $file UploadedFile */

            $file = $entity->getFile();
            $fileName = $file->getClientOriginalName();
            $imgName =  uniqid(). '.' .$fileName;
            // moves the file to the directory where brochures are stored
            $file->move(
                $entity->getUploadDir(),
                $imgName
            );
            $entity->setPath($imgName);
            $entity->setAssetsConfig($config);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            //	$this->getDoctrine()->getRepository('AssetsBundle:ItemImport')->fileUpload($entity,$data);
            return $this->redirect($this->generateUrl('itemstock_import'));
        }

        return $this->render('AssetsBundle:ItemImport:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ItemImport entity.
     *
     * @param ItemImport $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ItemImport $entity)
    {
        $form = $this->createForm(new ItemImportType(), $entity, array(
            'action' => $this->generateUrl('itemstock_import_create'),
            'method' => 'POST',
        ));
        return $form;
    }

    /**
     * Displays a form to create a new ItemImport entity.
     *
     */
    public function newAction()
    {
        $entity = new ItemImport();
        $form   = $this->createCreateForm($entity);

        return $this->render('AssetsBundle:ItemImport:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function excelDataImportAction(ItemImport $excelImporter)
    {

        //set_time_limit(0);
        ini_set('max_execution_time', 0);
        ignore_user_abort(true);
        $em = $this->getDoctrine()->getManager();
        $importer = $this->get('assest_item_stock_update_importer_excel');
        $importer->setImport($excelImporter);
        $reader = $this->get('assest_item_stock.importer.excel_data_reader');
        $file =  realpath($excelImporter->getAbsolutePath());
        $data = $reader->getData($file);
        if($importer->isValid($data)) {
            $importer->import($data);
            $excelImporter->setProgress('migrated');
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been migration successfully"
            );
            $em->flush();
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',"Excel File memo no null or exist in your system"
            );
            $em->flush();
        }
        return $this->redirect($this->generateUrl('itemstock_import'));
    }


    public function excelDataImportStockAction(ItemImport $excelImporter)
    {

        //set_time_limit(0);
        ini_set('max_execution_time', 0);
        ignore_user_abort(true);
        $em = $this->getDoctrine()->getManager();
        $importer = $this->get('medicine_stock_update_importer_excel');
        $importer->setItemImport($excelImporter);
        $reader = $this->get('medicine_stock_update.importer.excel_data_reader');
        $file =  realpath($excelImporter->getAbsolutePath());
        $data = $reader->getData($file);
        if($importer->isValid($data)) {
            $importer->import($data);
            $excelImporter->setProgress('migrated');
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been migration successfully"
            );
            $em->flush();
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',"Excel File memo no null or exist in your system"
            );
            $em->flush();
        }
        return $this->redirect($this->generateUrl('itemstock_import'));
    }

    public function deleteAction(ItemImport $excelImporter)
    {
        $em = $this->getDoctrine()->getManager();
        if ($excelImporter) {
            $excelImporter->removeUpload();
            $em->remove($excelImporter);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('itemstock_import'));
    }



    /**
     * Creates a new Vendor entity.
     *
     */
    public function uploadAction(Request $request) {

        $data = explode( ',', $request->cookies->get( 'barcodes' ) );
        $em = $this->getDoctrine()->getManager();
        if ( is_null( $data ) ) {
            return $this->redirect( $this->generateUrl( 'medicine_stock_short_item' ) );
        }
        $medicines = array();

        foreach ($data as $key => $value ){
            $medicine = $em->getRepository('AssetsBundle:ItemImport')->find($value);
            if($medicine){
                $medicines[$value] = $medicine;
            }
        }
        return $this->render('AssetsBundle:ItemImport:upload.html.twig', array(
            'medicines' => $medicines,
        ));
    }

    /**
     * Creates a new Vendor entity.
     *
     */
    public function uploadCreateAction(Request $request) {

        $data = explode( ',', $request->cookies->get( 'barcodes' ) );
        $em = $this->getDoctrine()->getManager();
        if ( is_null( $data ) ) {
            return $this->redirect( $this->generateUrl( 'itemstock_import'));
        }
        $file = $request->files->get('file');
        if($file){
            foreach ($data as $key => $value ){
                $entity = $em->getRepository('AssetsBundle:ItemImport')->find($value);
                if($entity){
                    if($entity->getWebPath()){
                        $entity->removeUpload();
                    }
                    $img = $file;
                    $fileName = $img->getClientOriginalName();
                    $imgName = uniqid() . '.' . $fileName;
                    $path = $entity->getUploadDir() . $imgName;
                    if (!file_exists($entity->getUploadDir())) {
                        mkdir($entity->getUploadDir(), 0777, true);
                    }
                    $this->get('helper.imageresizer')->resizeImage(480, $path, $img);
                    $entity->setPath($imgName);
                    $em->persist($entity);
                    $em->flush();
                }
            }
        }
        return $this->redirect( $this->generateUrl( 'itemstock_import'));
    }

}
