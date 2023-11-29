<?php

namespace Appstore\Bundle\DmsBundle\Repository;
use Appstore\Bundle\DmsBundle\Controller\InvoiceController;
use Appstore\Bundle\DmsBundle\Entity\AdmissionPatientDmsParticular;
use Appstore\Bundle\DmsBundle\Entity\DmsConfig;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoice;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoiceAccessories;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoiceMedicine;
use Appstore\Bundle\DmsBundle\Entity\Invoice;
use Appstore\Bundle\DmsBundle\Entity\InvoiceDmsParticular;
use Appstore\Bundle\DmsBundle\Entity\DmsParticular;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;


/**
 * DmsInvoiceParticularRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DmsInvoiceAccessoriesRepository extends EntityRepository
{

    public function handleDateRangeFind($qb,$data)
    {
        if(empty($data)){
            $datetime = new \DateTime("now");
            $startDate = $datetime->format('Y-m-d 00:00:00');
            $endDate = $datetime->format('Y-m-d 23:59:59');
        }elseif(!empty($data['startDate']) and !empty($data['endDate'])){
            $start = new \DateTime($data['startDate']);
            $startDate = $start->format('Y-m-d 00:00:00');
            $end = new \DateTime($data['endDate']);
            $endDate = $end->format('Y-m-d 23:59:59');
        }
        if (!empty($startDate) ) {
            $qb->andWhere("e.updated >= :startDate");
            $qb->setParameter('startDate', $startDate);
        }
        if (!empty($endDate)) {
            $qb->andWhere("e.updated <= :endDate");
            $qb->setParameter('endDate', $endDate);
        }
    }

    public function findInvoiceAccessories($config)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.dmsConfig = :config')->setParameter('config', $config);
        $qb->orderBy('e.updated','DESC');
        $result = $qb->getQuery();
        return $result;
    }

    public function insertAccessories($dmsConfig,$data)
    {

        $em = $this->_em;
        $entity = new DmsInvoiceAccessories();
        $quantity = !empty($data['quantity']) ? $data['quantity'] :1;
        $entity->setQuantity($quantity);
        $accessoriesId = $data['accessories'];
        $accessories = $em->getRepository('DmsBundle:DmsParticular')->find($accessoriesId);
        $entity->setDmsParticular($accessories);
        $entity->setSubTotal($quantity * $accessories->getPrice());
        $entity->setPrice($accessories->getPrice());
        $entity->setDmsConfig($dmsConfig);
        $em->persist($entity);
        $em->flush();

    }

    public function insertInvoiceAccessories(DmsInvoice $invoice, $data)
    {

        $em = $this->_em;
        $entity = new DmsInvoiceAccessories();
        $quantity = !empty($data['quantity']) ? $data['quantity'] :1;
        $entity->setQuantity($quantity);
        $accessoriesId = $data['accessories'];
        $accessories = $em->getRepository('DmsBundle:DmsParticular')->find($accessoriesId);
        $entity->setDmsParticular($accessories);
        $entity->setPrice($accessories->getPurchasePrice());
        $entity->setSubTotal($quantity * $accessories->getPrice());
        $entity->setDmsInvoice($invoice);
        $entity->setDmsConfig($invoice->getDmsConfig());
        $em->persist($entity);
        $em->flush();
    }




    public function getInvoiceAccessories(DmsInvoice $sales)
    {
        $entities = $sales->getDmsInvoiceAccessories();
        $data = '';
        $date = '';
        $i = 1;
        /** @var $entity DmsInvoiceAccessories */
        foreach ($entities as $entity) {

            $data .= '<tr id="accessories-'.$entity->getId().'">';
            $data .= '<td class="numeric" >' . $i . '</td>';
            $data .= '<td class="numeric" >' . $entity->getUpdated()->format('d-m-Y') . '</td>';
            $data .= '<td class="numeric" >' . $entity->getDmsParticular()->getParticularCode().' - '.$entity->getDmsParticular()->getName(). '</td>';
            $data .= '<td class="numeric" >' . $entity->getQuantity(). '</td>';
            $data .= '<td class="numeric" >' . $entity->getPrice(). '</td>';
            $data .= '<td class="numeric" >' . $entity->getPrice() * $entity->getQuantity(). '</td>';
            $data .= '<td class="numeric" id="approved-'.$entity->getId().'" >
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'"  data-url="/dms/invoice/'. $entity->getId(). '/accessories-approved" href="javascript:" class="btn blue mini approveAccessories" >Approve</a>
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'"  data-url="/dms/invoice/'. $entity->getId(). '/accessories-delete" href="javascript:" class="btn red mini deleteAccessories" ><i class="icon-trash"></i></a>
            </td>';
            $data .= '</tr>';
            $i++;
        }
        return $data;
    }

    public function reportAccessoriesOut(DmsConfig $config , $data)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('SUM(e.subTotal) as subTotal');
        $qb->where('e.dmsConfig = :config')->setParameter('config', $config);
        $this->handleDateRangeFind($qb,$data);
        $result = $qb->getQuery()->getOneOrNullResult();
        return $result['subTotal'];
    }

    public function getAccessoriesItemOut(DmsConfig $config , $data)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.dmsParticular','particular');
        $qb->select('SUM(e.subTotal) as subTotal');
        $qb->addSelect('SUM(e.quantity) as quantity');
        $qb->addSelect('particular.name as particularName');
        $qb->addSelect('particular.id');
        $qb->where('particular.dmsConfig = :config')->setParameter('config', $config);
        $this->handleDateRangeFind($qb,$data);
        $qb->groupBy('particular.id');
        $results = $qb->getQuery()->getArrayResult();
        return $results;

    }




}
