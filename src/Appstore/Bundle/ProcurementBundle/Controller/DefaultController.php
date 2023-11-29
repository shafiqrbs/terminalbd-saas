<?php

namespace Appstore\Bundle\ProcurementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ProcurementBundle:Default:index.html.twig', array('name' => $name));
    }
}
