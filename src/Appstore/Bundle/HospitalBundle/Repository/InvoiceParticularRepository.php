<?php

namespace Appstore\Bundle\HospitalBundle\Repository;
use Appstore\Bundle\HospitalBundle\Controller\InvoiceController;
use Appstore\Bundle\HospitalBundle\Entity\AdmissionPatientParticular;
use Appstore\Bundle\HospitalBundle\Entity\HmsInvoiceTemporaryParticular;
use Appstore\Bundle\HospitalBundle\Entity\Invoice;
use Appstore\Bundle\HospitalBundle\Entity\InvoiceParticular;
use Appstore\Bundle\HospitalBundle\Entity\Particular;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;


/**
 * PathologyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoiceParticularRepository extends EntityRepository
{

	protected function handleSearchBetween($qb,$data)
	{

		$invoice = isset($data['invoice'])? $data['invoice'] :'';
		$commission = isset($data['commission'])? $data['commission'] :'';
		$assignDoctor = isset($data['doctor'])? $data['doctor'] :'';
		$referred = isset($data['referred'])? $data['referred'] :'';
		$process = isset($data['process'])? $data['process'] :'';
		$customerName = isset($data['name'])? $data['name'] :'';
		$customerMobile = isset($data['mobile'])? $data['mobile'] :'';
		$created = isset($data['created'])? $data['created'] :'';
		$deliveryDate = isset($data['deliveryDate'])? $data['deliveryDate'] :'';
		$transactionMethod = isset($data['transactionMethod'])? $data['transactionMethod'] :'';
		$service = isset($data['service'])? $data['service'] :'';
		$cabinGroup = isset($data['cabinGroup'])? $data['cabinGroup'] :'';
		$cabin = isset($data['cabinNo'])? $data['cabinNo'] :'';
		$particular = isset($data['particular'])? $data['particular'] :'';

		if (!empty($invoice)) {
		    $inv = trim($invoice);
			$qb->andWhere($qb->expr()->like("e.invoice", "'%$inv%'"  ));
		}
		if (!empty($customerName)) {
			$qb->join('e.customer','c');
			$qb->andWhere($qb->expr()->like("c.customerId", "'%$customerName%'"  ));
		}

		if (!empty($customerMobile)) {
			$qb->join('e.customer','m');
			$qb->andWhere($qb->expr()->like("m.mobile", "'%$customerMobile%'"  ));
		}
		if (!empty($created)) {
			$compareTo = new \DateTime($created);
			$created =  $compareTo->format('Y-m-d');
			$qb->andWhere("e.created LIKE :created");
			$qb->setParameter('created', $created.'%');
		}

		if (!empty($deliveryDate)) {
			$compareTo = new \DateTime($deliveryDate);
			$created =  $compareTo->format('Y-m-d');
			$qb->andWhere("e.deliveryDateTime LIKE :deliveryDate");
			$qb->setParameter('deliveryDate', $created.'%');
		}

		if(!empty($commission)){
			$qb->andWhere("e.hmsCommission = :commission");
			$qb->setParameter('commission', $commission);
		}
		if(!empty($assignDoctor)){
			$qb->andWhere("e.assignDoctor = :assignDoctor");
			$qb->setParameter('assignDoctor', $assignDoctor);
		}

		if(!empty($referred)){
			$qb->andWhere("e.referredDoctor = :referredDoctor");
			$qb->setParameter('referredDoctor', $referred);
		}

		if(!empty($process)){
			$qb->andWhere("e.process = :process");
			$qb->setParameter('process', $process);
		}

		if(!empty($transactionMethod)){
			$qb->andWhere("e.transactionMethod = :transactionMethod");
			$qb->setParameter('transactionMethod', $transactionMethod);
		}

		if(!empty($service)){
			$qb->andWhere("e.service = :service");
			$qb->setParameter('service', $service);
		}

		if(!empty($particular)){
			$qb->andWhere("p.id = :particular");
			$qb->setParameter('particular', $particular);
		}

		if(!empty($cabin)){
			$qb->andWhere("e.cabin = :cabin");
			$qb->setParameter('cabin', $cabin);
		}
		if(!empty($cabinGroup)){
			$qb->leftJoin('e.cabin','cabin');
			$qb->leftJoin('cabin.serviceGroup','sg');
			$qb->andWhere("sg.id = :cabinGroup");
			$qb->setParameter('cabinGroup', $cabinGroup);
		}
	}

	public function handleDateRangeFind($qb,$data)
    {
        if(empty($data)){
            $datetime = new \DateTime("now");
            $startDate = $datetime->format('Y-m-d 00:00:00');
            $endDate = $datetime->format('Y-m-d 23:59:59');
        }else{
            $datetime = new \DateTime($data['startDate']);
            $startDate = $datetime->format('Y-m-d 00:00:00');
            $datetime = new \DateTime($data['endDate']);
            $endDate= $datetime->format('Y-m-d 23:59:59');
        }

        if (!empty($data['startDate']) ) {
            $qb->andWhere("e.created >= :startDate");
            $qb->setParameter('startDate',$startDate);
        }
        if (!empty($data['endDate'])) {
            $qb->andWhere("e.created <= :endDate");
            $qb->setParameter('endDate', $endDate);
        }
    }

    public function invoicePathologicalReportLists(User $user,$data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        $qb = $this->createQueryBuilder('ip');
        $qb->join('ip.hmsInvoice','e');
        $qb->leftJoin('ip.particular','p');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('p.service = :service')->setParameter('service', 1) ;
        $this->handleSearchBetween($qb,$data);
        $qb->andWhere("e.invoiceMode IN (:invoiceModes)")->setParameter('invoiceModes', array('diagnostic','admission'));
        $qb->andWhere("e.process IN (:process)")->setParameter('process', array('Done','In-progress','Diagnostic','Admitted'));
        $qb->orderBy('e.created','DESC');
        $qb->getQuery();
        return  $qb;
    }

    public function processPathologicalReports($hospital)
    {

        $qb = $this->createQueryBuilder('ip');
        $qb->select('p.id','p.name','p.particularCode');
        $qb->join('ip.hmsInvoice','e');
        $qb->join('ip.particular','p');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('p.service = :service')->setParameter('service', 1) ;
        $qb->andWhere('e.commissionApproved != 1');
        $qb->andWhere("e.process IN (:process)");
        $qb->setParameter('process', array('Done','In-progress','Diagnostic','Admitted'));
        $qb->groupBy('p.id');
        $result = $qb->getQuery()->getArrayResult();
        return  $result;
    }

    public function insertMasterParticular(User $user,Invoice $invoice){

        $entities = $user->getHmsInvoiceTemporaryParticulars();
        $em = $this->_em;
        /* @var $entity HmsInvoiceTemporaryParticular */
        if(!empty($entities)){
            foreach ($entities as $entity) {

                $invoiceParticular = new InvoiceParticular();
                $invoiceParticular->setHmsInvoice($invoice);
	            $invoiceParticular->setQuantity($entity->getQuantity());
                $invoiceParticular->setSalesPrice($entity->getSalesPrice());
                $invoiceParticular->setSubTotal($entity->getSubTotal());
                $invoiceParticular->setParticular($entity->getParticular());
                $invoiceParticular->setReportContent($entity->getParticular()->getReportContent());
	            if($entity->getParticular()->getService()->getSlug() == 'diagnostic'){
		            $datetime = new \DateTime("now");
		            $lastCode = $this->getLastCode($invoice,$datetime);
		            $invoiceParticular->setCode($lastCode+1);
		            $reportCode = sprintf("%s%s", $datetime->format('dmy'), str_pad($invoiceParticular->getCode(),3, '0', STR_PAD_LEFT));
		            $invoiceParticular->setReportCode($reportCode);
	            }
                $invoiceParticular->setEstimatePrice($entity->getEstimatePrice());
                $em->persist($invoiceParticular);
                $em->flush();
            }
        }

    }

    public function insertInvoiceItems($invoice, $data)
    {
        $particular = $this->_em->getRepository('HospitalBundle:Particular')->find($data['particularId']);
        $em = $this->_em;
        $entity = new InvoiceParticular();
        $invoiceParticular = $this->_em->getRepository('HospitalBundle:InvoiceParticular')->findOneBy(array('hmsInvoice'=> $invoice ,'particular' => $particular));
        if(!empty($invoiceParticular)) {
            $entity = $invoiceParticular;
            if ($particular->getService()->getHasQuantity() == 1){
                $entity->setQuantity($invoiceParticular->getQuantity() + $data['quantity']);
            }else{
                $entity->setQuantity(1);
            }
            $entity->setSubTotal($data['price'] * $entity->getQuantity());

        }else{

            if ($particular->getService()->getHasQuantity() == 1){
                $entity->setQuantity($data['quantity']);
            }else{
                $entity->setQuantity(1);
            }
            $entity->setSalesPrice($data['price']);
            $entity->setSubTotal($data['price'] * $data['quantity']);
        }
        $entity->setHmsInvoice($invoice);
        if($particular->getService()->getSlug() == 'diagnostic'){
            $datetime = new \DateTime("now");
            $entity->setCode($invoice);
            $lastCode = $this->getLastCode($invoice,$datetime);
            $entity->setCode($lastCode+1);
            $reportCode = sprintf("%s%s", $datetime->format('dmy'), str_pad($entity->getCode(),3, '0', STR_PAD_LEFT));
            $entity->setReportCode($reportCode);
        }
        $entity->setParticular($particular);
        $entity->setReportContent($particular->getReportContent());
        $entity->setEstimatePrice($particular->getPrice());
        if($particular->getCommission()){
            $entity->setCommission($particular->getCommission() * $entity->getQuantity());
        }
        $em->persist($entity);
        $em->flush();

    }

    public function insertInvoiceParticularMasterUpdate(AdmissionPatientParticular $patientParticular)
    {
        $em = $this->_em;
        $invoice = $patientParticular->getInvoiceTransaction()->getHmsInvoice();
        $particular = $patientParticular->getParticular();
        $entity = $this->findOneBy(array('hmsInvoice' => $invoice,'particular' => $particular,'admissionPatientParticular' => $patientParticular));
        /* @var $entity InvoiceParticular */
        if(empty($entity)) {
            $entity = new InvoiceParticular();
            $entity->setSubTotal($patientParticular->getSubTotal());
            $entity->setQuantity($patientParticular->getQuantity());
            $entity->setHmsInvoice($invoice);
            $entity->setAdmissionPatientParticular($patientParticular);
            $entity->setParticular($particular);
            $entity->setSalesPrice($patientParticular->getSalesPrice());
            $entity->setEstimatePrice($patientParticular->getParticular()->getPrice());
            if ($patientParticular->getParticular()->getCommission()) {
                $entity->setCommission($patientParticular->getParticular()->getCommission() * $entity->getQuantity());
            }
            $em->persist($entity);
            $em->flush();
        }

    }

    public function reverseInvoiceParticularMasterUpdate(AdmissionPatientParticular $patientParticular)
    {
        $em = $this->_em;
        $patientParticular->getId();
        $entity = $this->findOneBy(array('admissionPatientParticular' => $patientParticular));
        if($entity){
            $em->remove($entity);
            $em->flush();
        }
    }


    public function getSalesItems(Invoice $sales)
    {
        $entities = $sales->getInvoiceParticulars();
        $data = '';
        $i = 1;
        foreach ($entities as $entity) {
            $data .= '<tr id="remove-'. $entity->getId() . '">';
            $data .= '<td class="span1"><span class="badge badge-warning toggle badge-custom" id='. $entity->getId() .'" ><span>[+]</span></span></td>';
            $data .= '<td class="span1" >' . $i . '</td>';
            $data .= '<td class="span4" >' . $entity->getParticular()->getName() . '</td>';
            $data .= '<td class="span1" >' . $entity->getQuantity() . '</td>';
            $data .= '<td class="span2" >' . $entity->getSalesPrice() . '</td>';
            $data .= '<td class="span2" >' . $entity->getSubTotal() . '</td>';
            $data .= '<td class="span1" >
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'"  data-url="/hms/invoice/' . $sales->getId() . '/' . $entity->getId() . '/particular-delete" href="javascript:" class="btn red mini particularDelete" ><i class="icon-trash"></i></a>
            </td>';
            $data .= '</tr>';
            $i++;
        }
        return $data;
    }

    public function invoiceParticularLists($user){


    }

    public function hmsInvoiceParticularReverse(Invoice $invoice)
    {

        $em = $this->_em;

        /** @var InvoiceParticular $item */

        foreach($invoice->getInvoiceParticulars() as $item ){

            /** @var Particular  $particular */

            $particular = $item->getParticular();
            if( $particular->getService()->getId() == 4 ){
                $qnt = ($particular->getSalesQuantity() - $item->getQuantity());
                $particular->setSalesQuantity($qnt);
                $em->persist($particular);
                $em->flush();
            }
        }

    }

    public function getLastCode($entity,$datetime)
    {

        $today_startdatetime = $datetime->format('Y-m-d 00:00:00');
        $today_enddatetime = $datetime->format('Y-m-d 23:59:59');


        $qb = $this->createQueryBuilder('ip');
        $qb
            ->select('MAX(ip.code)')
            ->join('ip.hmsInvoice','s')
            ->where('s.hospitalConfig = :hospital')
            ->andWhere('s.updated >= :today_startdatetime')
            ->andWhere('s.updated <= :today_enddatetime')
            ->setParameter('hospital', $entity->getHospitalConfig())
            ->setParameter('today_startdatetime', $today_startdatetime)
            ->setParameter('today_enddatetime', $today_enddatetime);
        $lastCode = $qb->getQuery()->getSingleScalarResult();

        if (empty($lastCode)) {
            return 0;
        }

        return $lastCode;
    }


    public function reportSalesAccessories(GlobalOption $option ,$data)
    {
        $startDate = isset($data['startDate'])  ? $data['startDate'] : '';
        $endDate =   isset($data['endDate'])  ? $data['endDate'] : '';
        $qb = $this->createQueryBuilder('ip');
        $qb->join('ip.particular','p');
        $qb->join('ip.hmsInvoice','i');
        $qb->select('SUM(ip.quantity * p.purchasePrice ) AS totalPurchaseAmount');
        $qb->where('i.hospitalConfig = :hospital');
        $qb->setParameter('hospital', $option->getHospitalConfig());
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

    public function serviceParticularDetails(User $user, $data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        if(!empty($data['service'])){
            $qb = $this->createQueryBuilder('ip');
            $qb->leftJoin('ip.particular','p');
            $qb->leftJoin('ip.hmsInvoice','e');
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
}
