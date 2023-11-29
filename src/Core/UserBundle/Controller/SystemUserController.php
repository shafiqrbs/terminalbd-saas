<?phpnamespace Core\UserBundle\Controller;use Symfony\Bundle\FrameworkBundle\Controller\Controller;use Symfony\Component\HttpFoundation\Request;use Symfony\Component\HttpFoundation\Response;use Core\UserBundle\Entity\User;use JMS\SecurityExtraBundle\Annotation as JMS;use Doctrine\ORM\Repository;use Symfony\Component\HttpFoundation\File\UploadedFile;class SystemUserController extends Controller{    public function indexAction(Request $request, $page)    {        $request->query->set('page', $page);        return $this->render('UserBundle:Users:index.html.twig', array(            'pagination' =>  $this->getPaginateData()        ));    }    private function getPaginateData()    {        $query = $this->getUserManager()->getUserQuery();        $paginator = $this->get('knp_paginator');        return $paginator->paginate(            $query,            $this->get('request')->query->get('page', 1),            10,            array('distinct'=>false)        );    }    public function updateAction(Request $request)    {        $data = $request->request->all();        $file = $request->files->all();        $user = $this->get('security.context')->getToken()->getUser();        $em = $this->getDoctrine()->getManager();        $entity = $em->getRepository('UserBundle:Profile')->findOneBy(array('user'=> $user));        $this->getDoctrine()->getRepository('UserBundle:Profile')->updateProfile($entity,$data,$file);        exit;    }    public function checkMobileDuplicateAction(Request $request)    {        $data = $request->request->all();        $user = $this->findUserByMobile($data['mobile']);        if ($user && $user->getId() != $data['profile_id'] ) {            $response = 'false';        } else {            $response = 'true';        }        return new Response($response);    }    public function findUserByMobile($mobile)    {        $profile = $this->getDoctrine()->getRepository('UserBundle:Profile')->findBy(array('mobile'=>$mobile));        if(!empty($profile)){            return $profile->getId();        }        return false;    }    public function credentialAction(Request $request)    {        $data = $request->request->all();        $user = $this->getUser()->getId();        $em = $this->getDoctrine()->getManager();        $entity = $em->getRepository('UserBundle:User')->findOneBy(array('id'=> $user));        $entity->setUser($data['username']);        $entity->setEmail($data['email']);        $em = $this->getDoctrine()->getManager();        $em->persist($entity);        $em->flush();    }}