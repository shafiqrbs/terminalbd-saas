<?php

namespace Appstore\Bundle\DoctorPrescriptionBundle\Repository;
use Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsConfig;
use Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsInvoice;
use Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsInvoiceParticular;
use Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsParticular;
use Appstore\Bundle\HospitalBundle\Entity\InvoiceParticular;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;


/**
 * DpsInvoiceParticularRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DpsInvoiceParticularRepository extends EntityRepository
{

    public function handleDateRangeFind($qb,$data)
    {
        if(empty($data)){
            $datetime = new \DateTime("now");
            $data['startDate'] = $datetime->format('Y-m-d 00:00:00');
            $data['endDate'] = $datetime->format('Y-m-d 23:59:59');
        }else{
            $data['startDate'] = date('Y-m-d',strtotime($data['startDate']));
            $data['endDate'] = date('Y-m-d',strtotime($data['endDate']));
        }

        if (!empty($data['startDate']) ) {
            $qb->andWhere("e.created >= :startDate");
            $qb->setParameter('startDate', $data['startDate'].' 00:00:00');
        }
        if (!empty($data['endDate'])) {
            $qb->andWhere("e.created <= :endDate");
            $qb->setParameter('endDate', $data['endDate'].' 23:59:59');
        }
    }



    public function fileUpload(DpsInvoice $invoice,$data,$file)
    {
        $em = $this->_em;
        if(isset($file['file'])){
            $particular = new DpsInvoiceParticular();
            $dpsService = $this->_em->getRepository('DoctorPrescriptionBundle:DpsService')->find($data['dpsService']);
            $particular->setDpsInvoice($invoice);
            $particular->setDpsService($dpsService);
            $img = $file['file'];
            $fileName = $img->getClientOriginalName();
            $imgName =  uniqid(). '.' .$fileName;
            $img->move($particular->getUploadDir(), $imgName);
            $particular->setMetaValue($data['investigation']);
            $particular->setPath($imgName);
            $em->persist($particular);
            $em->flush();
        }
    }

    public function insertInvoiceParticularSingle(DpsInvoice $invoice, $data)
    {
        $em = $this->_em;
        $service = $this->_em->getRepository('DoctorPrescriptionBundle:DpsService')->findOneBy(array('slug'=>$data['service']));
        $entity = new DpsInvoiceParticular();
        $entity->setDpsService($service);
        $entity->setMetaValue($data['procedure']);
        $entity->setDiseases($data['diseases']);
        $entity->setDpsInvoice($invoice);
        $em->persist($entity);
        $em->flush();

    }

    public function getInvoiceServices(DpsInvoice $invoice)
    {
        $em = $this->_em;
        $qb = $this->createQueryBuilder('ip');
        $qb->join('ip.dpsParticular','p');
        $qb->join('p.service','ds');
        $qb->select('ds.id AS serviceId');
        $qb->where('ip.dpsInvoice = :hospital');
        $qb->setParameter('hospital', $invoice->getid());
        $qb->groupBy('p.service');
        $res = $qb->getQuery()->getArrayResult();
        $arrs = array();
        foreach ($res as $re){
            $arrs[] = $re['serviceId'];
        }
        return $arrs;
    }

    public function insertInvoiceParticularReturn(DpsInvoice $invoice, $data)
    {
        $em = $this->_em;
        $service = $this->_em->getRepository('DoctorPrescriptionBundle:DpsService')->findOneBy(array('slug' => $data['service']));
        $invoiceParticulars = $this->findBy(array('dpsInvoice'=>$invoice,'dpsService'=>$service ));
        $data ='';
        foreach ($invoiceParticulars as $invoiceParticular ):
        $data .='<tr id="remove-'.$invoiceParticular->getId().'">';
        $data .='<td  class="numeric">'.$invoiceParticular->getMetaValue().'</td>';
        $data .='<td  class="numeric">'.$invoiceParticular->getDiseases().'</td>';
        $data .='<td class="numeric">';
        $data .='<a href="javascript:" class="btn red mini particularDelete" data-tab="'.$service->getSlug().'" data-id="'. $invoiceParticular->getId().'" id="'. $invoiceParticular->getId().'" data-url="/dps/invoice/'.$invoice->getInvoice().'/'.$invoiceParticular->getId().'/particular-delete" ><i class="icon-trash"></i></a>';
        $data .='</td>';
        $data .='</tr>';

        endforeach;

        return $data;
    }


    public function insertInvoiceInvestigationUpload(DpsInvoice $invoice, $data)
    {
        $em = $this->_em;

        $service = $this->_em->getRepository('DoctorPrescriptionBundle:DpsService')->find($data['dpsService']);
        $invoiceParticulars = $this->findBy(array('dpsInvoice'=> $invoice,'dpsService' => $service ));
        $data ='';
        /* @var $invoiceParticular DpsInvoiceParticular */
        foreach ($invoiceParticulars as $invoiceParticular ):

            $date = $invoiceParticular->getCreated()->format('d-m-Y');
            $data .='<tr id="remove-'.$invoiceParticular->getId().'">';
            $data .='<td>'.$date.'</td>';
            $data .='<td>'.$invoiceParticular->getMetaValue().'</td>';
            $data .='<td><a target="_blank" href="/'.$invoiceParticular->getWebPath().'">View Image</a></td>';
            $data .='<td class="numeric">';
            $data .='<a href="javascript:" class="btn red mini particularDelete" data-tab="'.$service->getSlug().'" data-id="'. $invoiceParticular->getId().'" id="'. $invoiceParticular->getId().'" data-url="/dps/invoice/'.$invoice->getInvoice().'/'.$invoiceParticular->getId().'/particular-delete" ><i class="icon-trash"></i></a>';
            $data .='</td>';
            $data .='</tr>';

        endforeach;

        return $data;
    }
    public function insertInvoiceItems(DpsInvoice $invoice, $data)
    {
        $em = $this->_em;
        if(!empty($data['metaKey'])) {
            $this->removeInvoiceParticularPreviousCheck($invoice);
            foreach ($data['metaKey'] as $key => $val) {
                $particular = $this->_em->getRepository('DoctorPrescriptionBundle:DpsParticular')->find($val);
                $entity = new DpsInvoiceParticular();
                $invoiceDpsParticular = $this->_em->getRepository('DoctorPrescriptionBundle:DpsInvoiceParticular')->findOneBy(array('dpsInvoice' => $invoice, 'dpsParticular' => $particular));
                if (!empty($invoiceDpsParticular)) {
                    $entity = $invoiceDpsParticular ;
                    $entity->setMetaValue(trim($data['metaValue'][$key]));
                } else {
                    $entity->setDpsParticular($particular);
                    $entity->setMetaValue(trim($data['metaValue'][$key]));
                }
                $entity->setDpsInvoice($invoice);
                $em->persist($entity);
                $em->flush();
            }
            $this->updateMetaCheckValue($invoice,$data);
        }
    }

    public function updateMetaCheckValue($invoice,$data)
    {
        $em = $this->_em;
        foreach ($data['metaKey'] as $key => $val) {
            if(isset($data['metaCheck'][$key]) and $data['metaCheck'][$key] > 0) {
                $particular = $data['metaCheck'][$key];
                $invoiceDpsParticular = $this->_em->getRepository('DoctorPrescriptionBundle:DpsInvoiceParticular')->findOneBy(array('dpsInvoice' => $invoice, 'dpsParticular' => $particular));
                $invoiceDpsParticular->setMetaCheck($data['metaCheck'][$key]);
                $em->flush();
            }
        }

    }

    public function removeInvoiceParticularPreviousCheck(DpsInvoice $invoice)
    {
        $em = $this->_em;
        $update = $em->createQuery("UPDATE DoctorPrescriptionBundle:DpsInvoiceParticular e SET e.metaCheck = 0 WHERE e.dpsService IS NULL and  e.dpsInvoice = ".$invoice->getId());
        $update->execute();
    }

    public function removeInvoiceParticularCheckItem(DpsInvoice $invoice)
    {
        $em = $this->_em;
        $remove = $em->createQuery("DELETE DoctorPrescriptionBundle:DpsInvoiceParticular e WHERE e.dpsService IS NULL and  e.dpsInvoice = ".$invoice->getId());
        $remove->execute();
    }

    public function getSalesItems(DpsInvoice $sales)
    {
        $entities = $sales->getInvoiceParticulars();
        $data = '';
        $i = 1;
        foreach ($entities as $entity) {
            $data .= '<tr id="remove-'. $entity->getId() . '">';
            $data .= '<td class="span1"><span class="badge badge-warning toggle badge-custom" id='. $entity->getId() .'" ><span>[+]</span></span></td>';
            $data .= '<td class="span1" >' . $i . '</td>';
            $data .= '<td class="span1" >' . $entity->getDpsParticular()->getDpsParticularCode() . '</td>';
            $data .= '<td class="span4" >' . $entity->getDpsParticular()->getName() . '</td>';
            $data .= '<td class="span2" >' . $entity->getDpsParticular()->getService()->getName() . '</td>';
            $data .= '<td class="span1" >' . $entity->getQuantity() . '</td>';
            $data .= '<td class="span2" >' . $entity->getSalesPrice() . '</td>';
            $data .= '<td class="span2" >' . $entity->getSubTotal() . '</td>';
            $data .= '<td class="span1" >
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'" title="Are you sure went to delete ?" data-url="/dps/invoice/' . $sales->getId() . '/' . $entity->getId() . '/particular-delete" href="javascript:" class="btn red mini particularDelete" ><i class="icon-trash"></i></a>
            </td>';
            $data .= '</tr>';
            $i++;
        }
        return $data;
    }



    public function reportSalesAccessories(GlobalOption $option ,$data)
    {
        $startDate = isset($data['startDate'])  ? $data['startDate'] : '';
        $endDate =   isset($data['endDate'])  ? $data['endDate'] : '';
        $qb = $this->createQueryBuilder('ip');
        $qb->join('ip.particular','p');
        $qb->join('ip.dpsInvoice','i');
        $qb->select('SUM(ip.quantity * p.purchasePrice ) AS totalPurchaseAmount');
        $qb->where('i.hospitalConfig = :hospital');
        $qb->setParameter('hospital', $option->getDpsConfig());
        $qb->andWhere("i.process IN (:process)");
        $qb->setParameter('process', array('Done','Paid','In-progress','Diagnostic','Admitted','Release','Death','Released','Dead'));
        if (!empty($data['startDate']) ) {
            $qb->andWhere("i.updated >= :startDate");
            $qb->setParameter('startDate', $startDate.' 00:00:00');
        }
        if (!empty($data['endDate'])) {
            $qb->andWhere("i.updated <= :endDate");
            $qb->setParameter('endDate', $endDate.' 23:59:59');
        }
        $res = $qb->getQuery()->getOneOrNullResult();
        return $res['totalPurchaseAmount'];

    }

    public function serviceDpsParticularDetails(User $user, $data)
    {

        $hospital = $user->getGlobalOption()->getDpsConfig()->getId();
        $startDate = isset($data['startDate'])  ? $data['startDate'] : '';
        $endDate =   isset($data['endDate'])  ? $data['endDate'] : '';
        if(!empty($data['service'])){

            $qb = $this->createQueryBuilder('ip');
            $qb->leftJoin('ip.particular','p');
            $qb->leftJoin('ip.dpsInvoice','e');
            $qb->select('SUM(ip.quantity) AS totalQuantity');
            $qb->addSelect('SUM(ip.quantity * p.purchasePrice ) AS purchaseAmount');
            $qb->addSelect('SUM(ip.quantity * ip.salesPrice ) AS salesAmount');
            $qb->addSelect('p.name AS serviceName');
            $qb->where('e.hospitalConfig = :hospital');
            $qb->setParameter('hospital', $hospital);
            $qb->andWhere('p.service = :service');
            $qb->setParameter('service', $data['service']);
            $qb->andWhere("e.process IN (:process)");
            $qb->setParameter('process', array('Done','Paid','In-progress','Diagnostic','Admitted','Release','Death'));
            $this->handleDateRangeFind($qb,$data);
            $qb->groupBy('p.id');
            $res = $qb->getQuery()->getArrayResult();
            return $res;

        }else{

            return false;
        }

    }


    public function searchAutoComplete(DpsConfig $config,$q)
    {
        $query = $this->createQueryBuilder('e');
        $query->join('e.dpsInvoice', 'i');
        $query->select('e.metaValue as id');
        $query->where($query->expr()->like("e.metaValue", "'$q%'"  ));
        $query->andWhere("i.dpsConfig = :config");
        $query->setParameter('config', $config->getId());
        $query->groupBy('e.metaValue');
        $query->orderBy('e.metaValue', 'ASC');
        $query->setMaxResults( '10' );
        return $query->getQuery()->getResult();
    }

    public function searchProcedureDiseasesComplete(DpsConfig $config,$q)
    {
        $query = $this->createQueryBuilder('e');
        $query->join('e.dpsInvoice', 'i');
        $query->select('e.diseases as id');
        $query->where($query->expr()->like("e.diseases", "'$q%'"  ));
        $query->andWhere("i.dpsConfig = :config");
        $query->setParameter('config', $config->getId());
        $query->groupBy('e.diseases');
        $query->orderBy('e.diseases', 'ASC');
        $query->setMaxResults( '10' );
        return $query->getQuery()->getResult();
    }
}
