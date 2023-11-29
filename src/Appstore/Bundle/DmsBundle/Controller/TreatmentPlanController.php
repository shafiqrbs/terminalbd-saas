<?php

namespace Appstore\Bundle\DmsBundle\Controller;
use Knp\Snappy\Pdf;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoice;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoiceMedicine;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoiceParticular;
use Appstore\Bundle\DmsBundle\Entity\DmsParticular;
use Appstore\Bundle\DmsBundle\Entity\DmsTreatmentPlan;
use Appstore\Bundle\DmsBundle\Form\InvoiceType;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use Frontend\FrontentBundle\Service\MobileDetect;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\RunAs;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * DmsInvoiceController controller.
 *
 */
class TreatmentPlanController extends Controller
{

    public function paginate($entities)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1)/*page number*/,
            25  /*limit per page*/
        );
        $pagination->setTemplate('SettingToolBundle:Widget:pagination.html.twig');
        return $pagination;
    }

    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $data = $_REQUEST;
        $user = $this->getUser();
        $config = $user->getGlobalOption()->getDmsConfig();
        $treatmentSchedule  = $em->getRepository('DmsBundle:DmsTreatmentPlan')->findTodaySchedule($config,$data);
        $pagination = $this->paginate($treatmentSchedule);
        $assignDoctors = $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getFindWithParticular($config,array('doctor'));
        $treatments = $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getFindDentalServiceParticular($config,array('treatment'));
        return $this->render('DmsBundle:Invoice:treatmentSchedule.html.twig', array(
            'entities' => $pagination,
            'assignDoctors' => $assignDoctors,
            'treatments' => $treatments,
            'searchForm' => $data,
        ));

    }

    public function sendSmsPlanAction($patient, DmsTreatmentPlan $plan)
    {
        $em = $this->getDoctrine()->getManager();
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('setting_tool.post.dms_treatment_plan_sms', new \Setting\Bundle\ToolBundle\Event\DmsTreatmentPlanSmsEvent($plan));
        $plan->setSendSms(1);
        $em->flush();
        exit;
    }

    public function appointmentDateScheduleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $config = $user->getGlobalOption()->getDmsConfig();
        $assignDoctors = $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getFindWithParticular($config,array('doctor'));
        $treatments = $this->getDoctrine()->getRepository('DmsBundle:DmsParticular')->getFindDentalServiceParticular($config,array('treatment'));
        $treatmentSchedule  = $em->getRepository('DmsBundle:DmsTreatmentPlan')->findTodaySchedule($config,$data = array());
        $html =  $this->renderView('DmsBundle:Invoice:schedule-plan.html.twig', array(
            'treatmentSchedule' => $treatmentSchedule,
            'assignDoctors' => $assignDoctors,
            'treatments' => $treatments,
        ));
        return  New Response($html);
        exit;
    }

    public function dateWiseScheduleAction(Request $request)
    {

        $data = $request->request->all();
        $user = $this->getUser();
        $dmsConfig = $user->getGlobalOption()->getDmsConfig();
        $appointments = $this->getDoctrine()->getRepository('DmsBundle:DmsTreatmentPlan')->appointmentDate($dmsConfig,$data);
        return  New Response($appointments);
        exit;
    }

    public function appointmentFreeTimeScheduleAction()
    {

        $user = $this->getUser();
        $data = $_REQUEST;
        $dmsConfig = $user->getGlobalOption()->getDmsConfig();
        $appointments = $this->getDoctrine()->getRepository('DmsBundle:DmsTreatmentPlan')->findFreeAppointmentTime($dmsConfig,$data);
        $arrays = ['12.00 PM','12.15 PM',
            '12.30 PM','12.45 PM','1.00 PM','1.15 PM','1.30 PM','1.45 PM','2.00 PM','2.15 PM',
            '2.30 PM','2.45 PM','3.00 PM','4.15 PM','4.30 PM','4.45 PM','5.00 PM','5.15 PM',
            '5.30 PM','5.45 PM','6.00 PM','6.15 PM','6.30 PM','6.45 PM','7.00 PM','7.15 PM',
            '7.30 PM','7.45 PM','8.00 PM','8.15 PM','8.30 PM','8.45 PM','9.00 PM','9.15 PM','9.30 PM','9.45 PM','10.00 PM','10.15 PM','10.30 PM','10.45 PM','11.00 PM',
            '8.00 AM','8.15 AM','8.30 AM','8.45 AM','9.00 AM','9.15 AM','9.30 AM','9.45 AM','10.00 AM','10.15 AM','10.30 AM',
            '10.45 AM','11.00 AM','11.15 AM','11.30 AM','11.45 AM',
        ];
        $array = array_diff($arrays,$appointments);
        $items  = array();
        $items[]= "<option value=''>-Appointment Time-</option>";
        foreach ($array as $value):
            $items[]= "<option value='{$value}'>{$value}</option>";
        endforeach;
        return new JsonResponse($items);
    }



}

