<?php

namespace Frontend\FrontentBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendEcommerceController extends Controller
{


    public function indexAction()
    {

        $globalOption               = $this->getDoctrine()->getRepository('SettingToolBundle:GlobalOption')->find(2);
        $entities                   = $this->getDoctrine()->getRepository('SyndicateComponentBundle:Education')->getVendorList();
        $sliders                    = $this->getDoctrine()->getRepository('SettingContentBundle:SiteSlider')->findBy(array('status'=>1),array('id'=>'DESC'));
        $siteContent                = $this->getDoctrine()->getRepository('SettingContentBundle:SiteContent')->findBy(array('status'=>'1'),array('created'=>'desc'));
           return $this->render('FrontendBundle:Main/Ecommerce:index.html.twig', array(
            'globalOption'           => $globalOption,
            'entities'          => $entities,
            'siteContent'       => $siteContent,
            'sliders'       => $sliders,

        ));
    }

}
