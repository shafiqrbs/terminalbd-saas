<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core\UserBundle\Controller;

use Core\UserBundle\Entity\User;
use Desktop\Bundle\Service\MobileDetect;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller managing the resetting of the password
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class ResettingController extends Controller
{
    /**
     * Request reset user password: show form
     */
    public function requestAction()
    {
        $detect = new \Frontend\FrontentBundle\Service\MobileDetect();
        if($detect->isMobile() OR  $detect->isTablet() ) {
            $theme = 'Resetting/Mobile';
        }else{
            $theme = 'Resetting';
        }
        return $this->render('UserBundle:'.$theme.':reset.html.twig');
    }

    /**
     * Request reset user password: submit form and send email
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->request->get('username');


        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->render('FOSUserBundle:Resetting:request.html.twig', array(
                'invalid_username' => $username
            ));
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return $this->render('FOSUserBundle:Resetting:passwordAlreadyRequested.html.twig');
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

        return new RedirectResponse($this->generateUrl('fos_user_resetting_check_email',
            array('email' => $this->getObfuscatedEmail($user))
        ));
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction(Request $request)
    {
        $email = $request->query->get('email');

        if (empty($email)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
        }

        return $this->render('FOSUserBundle:Resetting:checkEmail.html.twig', array(
            'email' => $email,
        ));
    }

    /**
     * Reset user password
     */
    public function resetAction(Request $request, $token)
    {

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Resetting:reset.html.twig', array(
            'token' => $token,
            'form' => $form->createView(),
        ));
    }

    /**
     * Get the truncated email displayed when requesting the resetting.
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }

        return $email;
    }

    public function forgetPasswordAction(Request $request)
    {

        $intlMobile = $request->request->get('username');
        $mobile = $this->get('settong.toolManageRepo')->specialExpClean($intlMobile);
        $profile = $this->getDoctrine()->getRepository('UserBundle:Profile')->findOneBy(array('mobile' => $mobile));
        if(empty($profile)){

            $detect = new \Frontend\FrontentBundle\Service\MobileDetect();
            if($detect->isMobile() OR  $detect->isTablet() ) {
                $theme = 'Resetting/Mobile';
            }else{
                $theme = 'Resetting';
            }
            return $this->render('UserBundle:'.$theme.':reset.html.twig', array(
                'invalid_username' => $mobile
            ));

        }else{

            $entity = $profile->getUser();
            $a = mt_rand(1000,9999);
            $entity->setPlainPassword($a);
            $this->get('fos_user.user_manager')->updateUser($entity);
            $this->get('session')->getFlashBag()->add(
                'success',"Send sms on your mobile"
            );
            $dispatcher = $this->container->get('event_dispatcher');
            $dispatcher->dispatch('setting_tool.post.change_password', new \Setting\Bundle\ToolBundle\Event\PasswordChangeSmsEvent($entity,$a));
            return $this->redirect($this->generateUrl('homepage'));

        }



    }

}
