<?php
/**
 * Created by PhpStorm.
 * User: dhaka
 * Date: 8/19/14
 * Time: 5:24 PM
 */

namespace Setting\Bundle\ToolBundle\Event;

use Appstore\Bundle\DmsBundle\Entity\DmsTreatmentPlan;
use Symfony\Component\EventDispatcher\Event;


class DmsTreatmentPlanSmsEvent extends Event
{

    /** @var DmsTreatmentPlan  */

    protected $dmsTreatmentPlan;

    public function __construct(DmsTreatmentPlan $dmsTreatmentPlan)
    {
        $this->dmsTreatmentPlan = $dmsTreatmentPlan;
    }

    /**
     * @return DmsTreatmentPlan
     */
    public function getDmsTreatmentPlan()
    {
        return $this->dmsTreatmentPlan;
    }

}