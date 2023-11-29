<?php

namespace Setting\Bundle\ToolBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Setting\Bundle\ToolBundle\Entity\TaxTariffUpload;
use Setting\Bundle\ToolBundle\Form\TaxTariffUploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Setting\Bundle\ToolBundle\Entity\Syndicate;
use Setting\Bundle\ToolBundle\Form\SyndicateType;

/**
 * Syndicate controller.
 *
 */
class TaxUploadController extends Controller
{

    /**
     * @Secure(roles="ROLE_DOMAIN")
     */

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('SettingToolBundle:TaxTariffUpload')->findAll();
        return $this->render('SettingToolBundle:TaxTariffUpload:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * @Secure(roles="ROLE_DOMAIN")
     */

    public function createAction(Request $request)
    {
        $entity = new TaxTariffUpload();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->upload();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',"Data has been added successfully"
            );
            return $this->redirect($this->generateUrl('tarrif_upload'));
        }

        return $this->render('SettingToolBundle:TaxTariffUpload:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a TaxTariffUpload entity.
     *
     * @param TaxTariffUpload $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TaxTariffUpload $entity)
    {
        $form = $this->createForm(new TaxTariffUploadType(), $entity, array(
            'action' => $this->generateUrl('tarrif_upload_create'),
            'method' => 'POST',
            'attr' => array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate',
            )
        ));
        return $form;
    }

    /**
     * @Secure(roles="ROLE_DOMAIN")
     */

    public function newAction()
    {
        $entity = new TaxTariffUpload();
        $form   = $this->createCreateForm($entity);

        return $this->render('SettingToolBundle:TaxTariffUpload:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_DOMAIN")
     */

    public function excelDataImportAction(TaxTariffUpload $entity)
    {

        set_time_limit(0);
        ignore_user_abort(true);
        $em = $this->getDoctrine()->getManager();
        $importer = $this->get('appstore_tax_importer_excel');
        $reader = $this->get('appstore_tax.importer.excel_data_reader');
        $file =  realpath($entity->getAbsolutePath());
        $importer->import($reader->getData($file));
        $entity->setProcess('Migrated');
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'success',"Data has been migration successfully"
        );
        return $this->redirect($this->generateUrl('tarrif_upload'));
    }


    /**
     * @Secure(roles="ROLE_DOMAIN")
     */

    public function deleteAction(TaxTariffUpload $TaxTariffUpload)
    {
        $em = $this->getDoctrine()->getManager();
        if ($TaxTariffUpload) {
            $em->remove($TaxTariffUpload);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('tarrif_upload'));
    }

}
