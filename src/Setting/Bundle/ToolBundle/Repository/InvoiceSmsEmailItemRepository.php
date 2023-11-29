<?php

namespace Setting\Bundle\ToolBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\InvoiceSmsEmailItem;

/**
 * InvoiceSmsEmailItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoiceSmsEmailItemRepository extends EntityRepository
{
    public function insertItem($invoice,$data)
    {

        $em = $this->_em;
        foreach($data['check'] as $row){
            $entity = new InvoiceSmsEmailItem();
            $smsEmailPricing = $this->_em->getRepository('SettingToolBundle:SmsEmailPricing')->find($row);
            $entity->setInvoiceSmsEmail($invoice);
            $entity->setSmsEmailPricing($smsEmailPricing);
            $entity->setAmount($smsEmailPricing->getAmount());
            $em->persist($entity);
        }
        $em->flush();
    }
}
