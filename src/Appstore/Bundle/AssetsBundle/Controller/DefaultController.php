<?php

namespace Appstore\Bundle\AssetsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AssetsBundle:Default:index.html.twig', array('name' => $name));
    }
}
