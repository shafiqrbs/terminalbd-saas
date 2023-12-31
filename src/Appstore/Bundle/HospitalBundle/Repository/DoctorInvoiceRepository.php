<?php

namespace Appstore\Bundle\HospitalBundle\Repository;
use Appstore\Bundle\HospitalBundle\Entity\HospitalConfig;
use Appstore\Bundle\HospitalBundle\Entity\Invoice;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;


/**
 * PathologyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DoctorInvoiceRepository extends EntityRepository
{


    /**
     * @param $qb
     * @param $data
     */

    protected function handleSearchBetween($qb,$data)
    {
        $invoice = isset($data['hmsInvoice'])? $data['hmsInvoice'] :'';
        $commission = isset($data['commission'])? $data['commission'] :'';
        $assignDoctor = isset($data['assignDoctor'])? $data['assignDoctor'] :'';
        $process = isset($data['process'])? $data['process'] :'';
        $startDate = isset($data['startDate'])? $data['startDate'] :'';
        $endDate = isset($data['endDate'])? $data['endDate'] :'';
        $transactionMethod = isset($data['transactionMethod'])? $data['transactionMethod'] :'';

        if (!empty($invoice)) {
            $qb->andWhere($qb->expr()->like("hmsInvoice.invoice", "'%$invoice%'"  ));
        }
        if(!empty($commission)){
            $qb->andWhere("e.hmsCommission = :commission");
            $qb->setParameter('commission', $commission);
        }
        if(!empty($assignDoctor)){
            $qb->andWhere("e.assignDoctor = :assignDoctor");
            $qb->setParameter('assignDoctor', $assignDoctor);
        }
        if(!empty($process)){
            $qb->andWhere("e.process = :process");
            $qb->setParameter('process', $process);
        }
        if(!empty($transactionMethod)){
            $qb->andWhere("e.transactionMethod = :transactionMethod");
            $qb->setParameter('transactionMethod', $transactionMethod);
        }
        if (!empty($startDate) ) {
            $datetime = new \DateTime($startDate);
            $startDate = $datetime->format('Y-m-d 00:00:00');
            $qb->andWhere("e.updated >= :startDate")->setParameter('startDate',$startDate);
        }
        if (!empty($endDate) ) {
            $datetime = new \DateTime($endDate);
            $endDate = $datetime->format('Y-m-d 23:59:59');
            $qb->andWhere("e.updated <= :endDate")->setParameter('endDate',$endDate);
        }
    }

    public function findWithList(User $user,$data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();

        $qb = $this->createQueryBuilder('e');
        $qb->join('e.hmsInvoice','hmsInvoice');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $this->handleSearchBetween($qb,$data);
        $qb->orderBy('e.updated','DESC');
        $qb->getQuery();
        return  $qb;
    }

    public function  commissionSummary(User $user,$data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.hmsInvoice','hmsInvoice');
        $qb->select('sum(e.payment) as subTotal');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('e.process = :process')->setParameter('process', 'Paid') ;
        $this->handleSearchBetween($qb,$data);
        $receivable = $qb->getQuery()->getOneOrNullResult();
        $receivableTotal = !empty($receivable['subTotal']) ? $receivable['subTotal'] :0;
        return $receivableTotal;

    }

    public function  commissionUserSummary(User $user,$data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.hmsInvoice','hmsInvoice');
        $qb->join('e.createdBy','u');
        $qb->select('sum(e.payment) as subTotal');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('e.process = :process')->setParameter('process', 'Paid') ;
        $qb->andWhere('e.createdBy = :createdBy')->setParameter('createdBy', $user->getId()) ;
        $this->handleSearchBetween($qb,$data);
        $receivable = $qb->getQuery()->getOneOrNullResult();
        $receivableTotal = !empty($receivable['subTotal']) ? $receivable['subTotal'] :0;
        return $receivableTotal;

    }

    public function  commissionDoctorSummary(User $user,$data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        $qb = $this->createQueryBuilder('dc');
        $qb->join('dc.hmsInvoice','e');
        $qb->join('dc.assignDoctor','ad');
        $qb->select('sum(dc.payment) as payment');
        $qb->addSelect('ad.name','ad.particularCode','ad.mobile');
        $qb->addSelect('sum(e.subTotal) as subTotal ,sum(e.discount) as discount ,sum(e.total) as netTotal,sum(e.payment) as netPayment , sum(e.due) as netDue , sum(e.commission) as netCommission');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('dc.process = :process')->setParameter('process', 'Paid') ;
        $mode = isset($data['mode'])? $data['mode'] :'';
        if (!empty($mode)){
            $qb->andWhere('e.invoiceMode = :mode')->setParameter('mode', $mode);
        }
        if (!empty($data['startDate'])) {
            $startDate = str_replace('T',' ',$data['startDate']);
            $qb->andWhere("dc.updated >= :startDate");
            $qb->setParameter('startDate', $startDate);
        }
        if (!empty($data['endDate'])) {
            $endDate = str_replace('T',' ',$data['endDate']);
            $qb->andWhere("dc.updated <= :endDate");
            $qb->setParameter('endDate', $endDate);
        }
        $qb->groupBy('ad.id');
        $qb->orderBy('ad.name','ASC');
        $results = $qb->getQuery()->getArrayResult();
        return $results;
    }

    public function  commissionServicesSummary(User $user,$data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        $qb = $this->createQueryBuilder('dc');
        $qb->leftJoin('dc.hmsInvoice','e');
        $qb->leftJoin('dc.hmsCommission','ad');
        $qb->select('sum(dc.payment) as payment');
        $qb->addSelect('ad.name');
        $qb->addSelect('sum(e.subTotal) as subTotal ,sum(e.discount) as discount ,sum(e.total) as netTotal,sum(e.payment) as netPayment , sum(e.due) as netDue , sum(e.commission) as netCommission');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('dc.process = :process')->setParameter('process', 'Paid') ;
        $mode = isset($data['mode'])? $data['mode'] :'';
        if (!empty($mode)){
            $qb->andWhere('e.invoiceMode = :mode')->setParameter('mode', $mode);
        }
        if (!empty($data['startDate'])) {
            $startDate = str_replace('T',' ',$data['startDate']);
            $qb->andWhere("dc.updated >= :startDate");
            $qb->setParameter('startDate', $startDate);
        }
        if (!empty($data['endDate'])) {
            $endDate = str_replace('T',' ',$data['endDate']);
            $qb->andWhere("dc.updated <= :endDate");
            $qb->setParameter('endDate', $endDate);
        }
        $qb->groupBy('ad.id');
        $qb->orderBy('ad.name','ASC');
        $results = $qb->getQuery()->getArrayResult();
        return $results;


    }

    public function  userCommissionSummary(User $user,$data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.hmsInvoice','hmsInvoice');
        $qb->join('e.createdBy','u');
        $qb->select('u.id as userId','sum(e.payment) as total');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('e.process = :process')->setParameter('process', 'Paid') ;
        $this->handleSearchBetween($qb,$data);
        $qb->groupBy('e.createdBy');
        $result = $qb->getQuery()->getArrayResult();
        $array = array();
        foreach ($result as $row){
            $array[$row['userId']] = $row;
        }
        return $array;


    }

    public function  userGroupCommissionSummary(User $user,$data)
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.hmsInvoice','hmsInvoice');
        $qb->join('e.createdBy','u');
        $qb->select('u.id as userId','sum(e.payment) as total');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('e.process = :process')->setParameter('process', 'Paid') ;
        $this->handleSearchBetween($qb,$data);
        $qb->groupBy('e.createdBy');
        $result = $qb->getQuery()->getArrayResult();
        $array = array();
        foreach ($result as $row){
            $array[$row['userId']] = $row;
        }
        return $array;


    }

    public function  findWithOverview(User $user,$data, $mode = '' )
    {
        $hospital = $user->getGlobalOption()->getHospitalConfig()->getId();
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.hmsInvoice','hmsInvoice');
        $qb->select('sum(e.payment) as subTotal');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('e.process = :process')->setParameter('process', 'In-progress') ;
        $this->handleSearchBetween($qb,$data);
        $receivable = $qb->getQuery()->getOneOrNullResult();
        $receivableTotal = !empty($receivable['subTotal']) ? $receivable['subTotal'] :0;


        $qb = $this->createQueryBuilder('e');
        $qb->join('e.hmsInvoice','hmsInvoice');
        $qb->select('sum(e.payment) as subTotal');
        $qb->where('e.hospitalConfig = :hospital')->setParameter('hospital', $hospital) ;
        $qb->andWhere('e.process = :process')->setParameter('process', 'Paid') ;
        $this->handleSearchBetween($qb,$data);
        $payment = $qb->getQuery()->getOneOrNullResult();
        $paymentTotal = !empty($payment['subTotal']) ? $payment['subTotal'] :0;
        $due = $receivableTotal- $paymentTotal;
        $data = array( 'commission'=> $receivableTotal , 'payment'=> $paymentTotal , 'due'=> $due);
        return $data;
    }

    public function updateCommissionInvoice(Invoice $invoice)
    {
        $em = $this->_em;
        $total = $this->createQueryBuilder('e')
            ->select('sum(e.payment) as subTotal')
            ->where('e.hmsInvoice = :invoice')
            ->setParameter('invoice', $invoice ->getId())
            ->getQuery()->getOneOrNullResult();
        $subTotal = !empty($total['subTotal']) ? $total['subTotal'] :0;
        return $subTotal;
    }

    public function getInvoiceBaseCommission(HospitalConfig $config , $entities)
    {
        $ids = array();
        foreach ($entities->getQuery()->getArrayResult() as $row){
            $ids[] = $row['id'];
        }
        $em = $this->_em;
        $qb = $this->createQueryBuilder('e');
        $qb->select('i.id as invoiceId , referred.name as referredName,  particular.id as commissionId');
        $qb->addSelect('SUM(e.payment) as payment');
        $qb->innerJoin('e.hmsInvoice','i');
        $qb->innerJoin('e.hmsCommission','particular');
        $qb->innerJoin('e.assignDoctor','referred');
        $qb->where('i.id IN (:invoices)');
        $qb->setParameter('invoices',$ids);
        $qb->andWhere("e.process = :process")->setParameter('process','Paid');
        $qb->groupBy('particular.id,i.id,referred.id');
        $result = $qb->getQuery()->getArrayResult();
        $resDatas = [];
        foreach ($result as $row){
            $uniqueId = $row['invoiceId'].'-'.$row['commissionId'];
            $resDatas[$uniqueId]= $row;
        }
        return $resDatas;

    }

    public function getInvoiceBaseCommissionSummary(HospitalConfig $config , $entities)
    {
        $ids = array();
        foreach ($entities->getQuery()->getArrayResult() as $row){
            $ids[] = $row['id'];
        }
        $em = $this->_em;
        $qb = $this->createQueryBuilder('e');
        $qb->select('particular.id as commissionId');
        $qb->addSelect('SUM(e.payment) as payment,particular.name as commissionName , particular.id as comId');
        $qb->innerJoin('e.hmsInvoice','i');
        $qb->innerJoin('e.hmsCommission','particular');
        $qb->where('i.id IN (:invoices)');
        $qb->setParameter('invoices',$ids);
        $qb->andWhere("e.process = :process")->setParameter('process','Paid');
        $qb->groupBy('particular.name');
        $result = $qb->getQuery()->getArrayResult();
        return $result;

    }

    public function monthlyCommission(User $user , $data =array())
    {
        $config = $user->getGlobalOption()->getHospitalConfig()->getId();
        $compare = new \DateTime();
        $month =  $compare->format('F');
        $year =  $compare->format('Y');
        $month = isset($data['month'])? $data['month'] :$month;
        $year = isset($data['year'])? $data['year'] :$year;
        $sql = "SELECT DATE_FORMAT(transaction.updated,'%d-%m-%Y') as date,SUM(transaction.payment) as payment
                FROM hms_doctor_invoice as transaction
                WHERE transaction.hospitalConfig_id = :hmsConfig AND transaction.process = :process AND MONTHNAME(transaction.updated) =:month AND YEAR(transaction.updated) =:year
                GROUP BY date";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('hmsConfig', $config);
        $stmt->bindValue('process', 'Paid');
        $stmt->bindValue('month', $month);
        $stmt->bindValue('year', $year);
        $stmt->execute();
        $results =  $stmt->fetchAll();
        $arrays = array();
        foreach ($results as $result){
            $arrays[$result['date']] = $result;
        }
        return $arrays;
    }

    public function monthlyGroupBaseCommissionSummary(User $user , $data)
    {
        $config = $user->getGlobalOption()->getHospitalConfig()->getId();
        $compare = new \DateTime();
        $month =  $compare->format('F');
        $year =  $compare->format('Y');
        $month = isset($data['month'])? $data['month'] :$month;
        $year = isset($data['year'])? $data['year'] :$year;
        $referred = isset($data['referred'])? $data['referred'] :'';
        $referredSql = "";
        if(!empty($referred)){
            $referredSql = "AND transaction.assignDoctor_id = :referred";
        }
        $sql = "SELECT SUM(transaction.payment) as payment, commission.name as commissionName, commission.id as commissionId
                FROM hms_doctor_invoice as transaction
                INNER JOIN hms_commission as commission ON transaction.hmsCommission_id = commission.id
                WHERE transaction.hospitalConfig_id = :hmsConfig AND transaction.process = :process AND MONTHNAME(transaction.updated) =:month AND YEAR(transaction.updated) =:year
                {$referredSql} GROUP BY commissionName ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('hmsConfig', $config);
        $stmt->bindValue('process', 'Paid');
        $stmt->bindValue('month', $month);
        $stmt->bindValue('year', $year);
        if(!empty($referred)) {
            $stmt->bindValue('referred',$referred);
        }
        $stmt->execute();
        $results =  $stmt->fetchAll();
        return $results;

    }

    public function monthlyCommissionGroup(User $user , $data =array())
    {
        $config = $user->getGlobalOption()->getHospitalConfig()->getId();
        $compare = new \DateTime();
        $month =  $compare->format('F');
        $year =  $compare->format('Y');
        $referred = isset($data['referred'])? $data['referred'] :'';
        $referredSql = "";
        if(!empty($referred)){
         $referredSql = "AND transaction.assignDoctor_id = :referred";
        }
        $month = isset($data['month'])? $data['month'] :$month;
        $year = isset($data['year'])? $data['year'] :$year;
        $sql = "SELECT DATE_FORMAT(transaction.updated,'%d-%m-%Y') as date,SUM(transaction.payment) as payment, commission.name as commissionName, commission.id as commissionId
                FROM hms_doctor_invoice as transaction
                INNER JOIN hms_commission as commission ON transaction.hmsCommission_id = commission.id
                WHERE transaction.hospitalConfig_id = :hmsConfig AND transaction.process = :process AND MONTHNAME(transaction.updated) = :month AND YEAR(transaction.updated) =:year
                {$referredSql} GROUP BY date , commissionName ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('hmsConfig', $config);
        $stmt->bindValue('process', 'Paid');
        $stmt->bindValue('month', $month);
        $stmt->bindValue('year', $year);
        if(!empty($referred)) {
            $stmt->bindValue('referred',$referred);
        }
        $stmt->execute();
        $results =  $stmt->fetchAll();
        $arrays = array();
        foreach ($results as $row){
            $uniqueId = $row['date'].'-'.$row['commissionId'];
            $arrays[$uniqueId]= $row;
        }
        return $arrays;
    }

    public function monthlyReferredCommissionInvoice(User $user , $data =array()){
        $this->_em;
        $config = $user->getGlobalOption()->getHospitalConfig()->getId();
        $month = isset($data['month'])? $data['month'] : '';
        $year = isset($data['year'])? $data['year'] : '';
        $referred = isset($data['referred'])? $data['referred'] :'';
        if(empty($month) and empty($year)){
            $datetime = new \DateTime();
            $startDate = $datetime->format('Y-m-01 00:00:00');
            $endDate = $datetime->format('Y-m-t 23:59:59');
        }else{
            $date = $year.'-'.$month.'-01';
            $datetime = new \DateTime($date);
            $startDate = $datetime->format('Y-m-01 00:00:00');
            $endDate = $datetime->format('Y-m-t 23:59:59');
        }
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.updated as created,i.id as invoiceId,i.invoice as invoice,i.created as invoiceDate,c.name as customerName ');
        $qb->innerJoin('e.hmsInvoice','i');
        $qb->innerJoin('i.customer','c');
        $qb->innerJoin('e.assignDoctor','referred');
        $qb->where('e.hospitalConfig = :config')->setParameter('config',$config);
        $qb->andWhere("e.updated >= :startDate")->setParameter('startDate',$startDate);
        $qb->andWhere("e.updated <= :endDate")->setParameter('endDate',$endDate);
        $qb->andWhere("e.process = :process")->setParameter('process','Paid');
        $qb->andWhere('referred.id = :assign');
        $qb->setParameter('assign',$referred);
        $qb->groupBy('i.id');
        $result = $qb->getQuery()->getArrayResult();
        return $result;
    }

    public function monthlyCommissionDetails($user,$data)
    {
        $this->_em;
        $config = $user->getGlobalOption()->getHospitalConfig()->getId();
        $month = isset($data['month'])? $data['month'] : '';
        $year = isset($data['year'])? $data['year'] : '';
        $referred = isset($data['referred'])? $data['referred'] :'';
        if(empty($month) and empty($year)){
            $datetime = new \DateTime();
            $startDate = $datetime->format('Y-m-01 00:00:00');
            $endDate = $datetime->format('Y-m-t 23:59:59');
        }else{
            $date = $year.'-'.$month.'-01';
            $datetime = new \DateTime($date);
            $startDate = $datetime->format('Y-m-01 00:00:00');
            $endDate = $datetime->format('Y-m-t 23:59:59');
        }
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.updated as created , i.id as invoiceId , referred.name as referredName,  particular.id as commissionId');
        $qb->addSelect('SUM(e.payment) as payment');
        $qb->innerJoin('e.hmsInvoice','i');
        $qb->innerJoin('e.hmsCommission','particular');
        $qb->innerJoin('e.assignDoctor','referred');
        $qb->where('e.hospitalConfig = :config')->setParameter('config',$config);
        $qb->andWhere("e.updated >= :startDate")->setParameter('startDate',$startDate);
        $qb->andWhere("e.updated <= :endDate")->setParameter('endDate',$endDate);
        $qb->andWhere("e.process = :process")->setParameter('process','Paid');
        $qb->andWhere('referred.id = :assign')->setParameter('assign',$referred);
        $qb->groupBy('particular.id,i.id');
        $result = $qb->getQuery()->getArrayResult();
        $resDatas = array();
        foreach ($result as $row){
            $uniqueId = $row['invoiceId'].'-'.$row['commissionId'];
            $resDatas[$uniqueId]= $row;
        }
        return $resDatas;

    }

    public function commissionGroup($user,$data)
    {
        $this->_em;
        $config = $user->getGlobalOption()->getHospitalConfig()->getId();
        $startDate = isset($data['startDate'])? $data['startDate'] : '';
        $endDate = isset($data['endDate'])? $data['endDate'] : '';
        if(empty($startDate) and empty($endDate)){
            $datetime = new \DateTime();
            $startDate = $datetime->format('Y-m-01 00:00:00');
            $endDate = $datetime->format('Y-m-t 23:59:59');
        }else{
            $datetime = new \DateTime($startDate);
            $startDate = $datetime->format('Y-m-d 00:00:00');
            $datetime = new \DateTime($endDate);
            $endDate = $datetime->format('Y-m-d 23:59:59');
        }
        $qb = $this->createQueryBuilder('e');
        $qb->select('particular.name as commissionName');
        $qb->addSelect('SUM(e.payment) as payment');
        $qb->innerJoin('e.hmsCommission','particular');
        $qb->where('e.hospitalConfig = :config');
        $qb->setParameter('config',$config);
        $qb->andWhere("e.updated >= :startDate")->setParameter('startDate',$startDate);
        $qb->andWhere("e.updated <= :endDate")->setParameter('endDate',$endDate);
        $qb->andWhere("e.process = :process")->setParameter('process','Paid');
        $qb->groupBy('particular.name');
        $result = $qb->getQuery()->getArrayResult();
        return $result;

    }

    public function commissionReferred($user,$data)
    {
        $this->_em;
        $config = $user->getGlobalOption()->getHospitalConfig()->getId();
        $startDate = isset($data['startDate'])? $data['startDate'] : '';
        $endDate = isset($data['endDate'])? $data['endDate'] : '';
        if(empty($startDate) and empty($endDate)){
            $datetime = new \DateTime();
            $startDate = $datetime->format('Y-m-01 00:00:00');
            $endDate = $datetime->format('Y-m-t 23:59:59');
        }else{
            $datetime = new \DateTime($startDate);
            $startDate = $datetime->format('Y-m-d 00:00:00');
            $datetime = new \DateTime($endDate);
            $endDate = $datetime->format('Y-m-d 23:59:59');
        }
        $qb = $this->createQueryBuilder('e');
        $qb->select('particular.name as name , particular.mobile as mobile');
        $qb->addSelect('SUM(e.payment) as payment');
        $qb->innerJoin('e.assignDoctor','particular');
        $qb->where('e.hospitalConfig = :config');
        $qb->setParameter('config',$config);
        $qb->andWhere("e.updated >= :startDate")->setParameter('startDate',$startDate);
        $qb->andWhere("e.updated <= :endDate")->setParameter('endDate',$endDate);
        $qb->andWhere("e.process = :process")->setParameter('process','Paid');
        $qb->groupBy('particular.name');
        $result = $qb->getQuery()->getArrayResult();
        return $result;

    }



}
