<?php

namespace Appstore\Bundle\AssetsBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;

/**
 * Depreciation controller.
 *
 */
class ProductLedgerController extends Controller
{
	public function paginate($entities)
	{

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$entities,
			$this->get('request')->query->get('page', 1)/*page number*/,
			25  /*limit per page*/
		);
		return $pagination;
	}


	/**
	 * Lists all Item entities.
	 */

	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$entities = $em->getRepository('AssetsBundle:ProductLedger')->findWithSearch($data);
		$pagination = $this->paginate($entities);
		return $this->render('AssetsBundle:ProductLedger:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}
/**
	 * Lists all Item entities.
	 */

	public function ProductLedgerAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$entities = $em->getRepository('AssetsBundle:ProductLedger')->findWithSearch($data);
		$pagination = $this->paginate($entities);
		return $this->render('AssetsBundle:ProductLedger:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}
/**
	 * Lists all Item entities.
	 */

	public function itemAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$entities = $em->getRepository('AssetsBundle:ProductLedger')->findWithSearch($data);
		$pagination = $this->paginate($entities);
		return $this->render('AssetsBundle:ProductLedger:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}
/**
	 * Lists all Item entities.
	 */

	public function categoryAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$entities = $em->getRepository('AssetsBundle:ProductLedger')->findWithSearch($data);
		$pagination = $this->paginate($entities);
		return $this->render('AssetsBundle:ProductLedger:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}
	public function branchAction()
	{
		$em = $this->getDoctrine()->getManager();
		$data = $_REQUEST;
		$entities = $em->getRepository('AssetsBundle:ProductLedger')->findWithSearch($data);
		$pagination = $this->paginate($entities);
		return $this->render('AssetsBundle:ProductLedger:index.html.twig', array(
			'entities' => $pagination,
			'searchForm' => $data
		));
	}

    public function pdfProductLedgerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $entities = $em->getRepository('AssetsBundle:ProductLedger')->findWithSearch($data);
        $pagination = $entities->getQuery()->getResult();
        $serialNo = $data['serialNo'];
        $product = $this->getDoctrine()->getRepository('AssetsBundle:Product')->findOneBy(array('serialNo'=>$serialNo));
        $html = $this->renderView(
            'AssetsBundle:ProductLedger:pdfProductLedger.html.twig', array(
                'entities' => $pagination,
                'product' => $product,
                'searchForm' => $data
            )
        );
        $wkhtmltopdfPath = 'xvfb-run --server-args="-screen 0, 1280x1024x24" /usr/bin/wkhtmltopdf --use-xserver';
        $snappy          = new Pdf($wkhtmltopdfPath);
        $pdf             = $snappy->getOutputFromHtml($html);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="ledgerPdf.pdf"');
        echo $pdf;
        return new Response('');
    }


}
