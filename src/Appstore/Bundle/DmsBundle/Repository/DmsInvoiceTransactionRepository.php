<?php

namespace Appstore\Bundle\DmsBundle\Repository;
use Appstore\Bundle\AccountingBundle\Entity\AccountSales;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoice;
use Appstore\Bundle\DmsBundle\Entity\DmsDmsInvoiceTransaction;
use Appstore\Bundle\DmsBundle\Entity\Invoice;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoiceTransaction;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;


/**
 * DmsInvoiceTransactionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DmsInvoiceTransactionRepository extends EntityRepository
{

    public function todaySalesOverview(User $user , $data , $previous ='', $mode='')
    {

        if (empty($data)) {
            $datetime = new \DateTime("now");
            $data['startDate'] = $datetime->format('Y-m-d 00:00:00');
            $data['endDate'] = $datetime->format('Y-m-d 23:59:59');
        } elseif (!empty($data['startDate']) and !empty($data['endDate'])) {
            $data['startDate'] = date('Y-m-d', strtotime($data['startDate']));
            $data['endDate'] = date('Y-m-d', strtotime($data['endDate']));
        }

        $hospital = $user->getGlobalOption()->getDmsConfig()->getId();
        $qb = $this->createQueryBuilder('it');
        $qb->join('it.dmsInvoice', 'e');
        $qb->select('sum(it.total) as total ,sum(it.discount) as discount , sum(it.payment) as payment');
        $qb->where('e.dmsConfig = :hospital')->setParameter('hospital', $hospital);
        if ($previous == 'true'){

            if (!empty($data['startDate'])) {
                $qb->andWhere("it.updated >= :startDate");
                $qb->setParameter('startDate', $data['startDate'] . ' 00:00:00');
            }
            if (!empty($data['endDate'])) {
                $qb->andWhere("it.updated <= :endDate");
                $qb->setParameter('endDate', $data['endDate'] . ' 23:59:59');
            }
            if (!empty($data['startDate'])) {
                $qb->andWhere("e.created >= :startDate");
                $qb->setParameter('startDate', $data['startDate'] . ' 00:00:00');
            }
            if (!empty($data['endDate'])) {
                $qb->andWhere("e.created <= :endDate");
                $qb->setParameter('endDate', $data['endDate'] . ' 23:59:59');
            }

        }elseif ($previous == 'false'){

            if (!empty($data['startDate'])) {
                $qb->andWhere("e.created < :startDate");
                $qb->setParameter('startDate', $data['startDate'] . ' 00:00:00');
            }
            if (!empty($data['startDate'])) {
                $qb->andWhere("it.updated >= :startDate");
                $qb->setParameter('startDate', $data['startDate'] . ' 00:00:00');
            }
            if (!empty($data['endDate'])) {
                $qb->andWhere("it.updated <= :endDate");
                $qb->setParameter('endDate', $data['endDate'] . ' 23:59:59');
            }

        }
        if (!empty($mode)){
            $qb->andWhere('e.invoiceMode = :mode')->setParameter('mode', $mode);
        }
        $qb->andWhere('it.process = :process')->setParameter('process', 'Done');
        $result = $qb->getQuery()->getOneOrNullResult();
        $total = !empty($result['total']) ? $result['total'] :0;
        $discount = !empty($result['discount']) ? $result['discount'] :0;
        $receive = !empty($result['payment']) ? $result['payment'] :0;
        $data = array('total'=> $total ,'discount'=> $discount ,'receive'=> $receive);
        return $data;
    }

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
            $qb->andWhere("ip.updated >= :startDate");
            $qb->setParameter('startDate', $data['startDate'].' 00:00:00');
        }
        if (!empty($data['endDate'])) {
            $qb->andWhere("ip.updated <= :endDate");
            $qb->setParameter('endDate', $data['endDate'].' 23:59:59');
        }
    }


    public function findWithTransactionOverview(User $user, $data)
    {
        $hospital = $user->getGlobalOption()->getDmsConfig()->getId();
        $qb = $this->createQueryBuilder('ip');
        $qb->join('ip.dmsInvoice','e');
        $qb->leftJoin('ip.transactionMethod','p');
        $qb->select('sum(ip.payment) as paymentTotal');
        $qb->addSelect('p.name as transName');
        $qb->where('e.dmsConfig = :hospital')->setParameter('hospital', $hospital);
        if (!empty($mode)){
            $qb->andWhere('e.invoiceMode = :mode')->setParameter('mode', $mode);
        }
        $qb->andWhere("e.process IN (:process)");
        $qb->setParameter('process', array('Done','Paid','In-progress','Diagnostic','Admitted'));
        $this->handleDateRangeFind($qb,$data);
        $qb->groupBy('p.id');
        $result = $qb->getQuery()->getArrayResult();
        return $result;
    }



    public function initialInsertDmsInvoiceTransaction(DmsInvoice $invoice){

        $code = $this->getLastCode($invoice);
        $entity = New DmsInvoiceTransaction();
        $entity->setDmsInvoice($invoice);
        $entity->setCode($code + 1);
        $transactionCode = sprintf("%s", str_pad($entity->getCode(),2, '0', STR_PAD_LEFT));
        $entity->setProcess('Done');
        $entity->setTransactionCode($transactionCode);
        $entity->setDiscount($invoice->getDiscount());
        $entity->setTotal($invoice->getSubTotal());
        $entity->setPayment($invoice->getPayment());
        $this->_em->persist($entity);
        $this->_em->flush($entity);
        return $entity;

    }

    public function admissionPaymentTransaction(DmsInvoice $invoice,$data){

        $code = $this->getLastCode($invoice);
        $entity = New DmsInvoiceTransaction();
        $entity->setDmsInvoice($invoice);
        $entity->setCode($code + 1);
        $transactionCode = sprintf("%s", str_pad($entity->getCode(),2, '0', STR_PAD_LEFT));
        $entity->setTransactionCode($transactionCode);
        $entity->setDiscount($data['discount']);
        $entity->setPayment($data['payment']);
        $entity->setProcess('In-progress');
        $transactionMethod = $this->_em->getRepository('SettingToolBundle:TransactionMethod')->find(1);
        $entity->setTransactionMethod($transactionMethod);
        $this->_em->persist($entity);
        $this->_em->flush($entity);
        return $entity;

    }



    public function insertAdmissionTransaction(DmsInvoiceTransaction $entity, $data)
    {

        $process = $data['process'];
        $invoice = $entity->getDmsInvoice();
        $entity->setProcess($process);
        $entity->setPayment($data['discount']);
        $entity->setPayment($data['payment']);
        $entity->setTransactionMethod($invoice->getTransactionMethod());
        $entity->setAccountBank($invoice->getAccountBank());
        $entity->setPaymentCard($invoice->getPaymentCard());
        $entity->setCardNo($invoice->getCardNo());
        $entity->setBank($invoice->getBank());
        $entity->setAccountMobileBank($invoice->getAccountMobileBank());
        $entity->setPaymentMobile($invoice->getPaymentMobile());
        $entity->setTransactionId($invoice->getTransactionId());
        $entity->setComment($invoice->getComment());
        if ($invoice->getDmsConfig()->getVatEnable() == 1 && $invoice->getDmsConfig()->getVatPercentage() > 0) {
            $vat = $this->getCulculationVat($invoice, $entity->getPayment());
            $entity->setVat($vat);
        }
        $this->_em->persist($entity);
        $this->_em->flush($entity);

    }
    public function insertTransaction(DmsInvoice $invoice)
    {
            $entity = New DmsInvoiceTransaction();
            $code = $this->getLastCode($invoice);
            $entity->setDmsInvoice($invoice);
            $entity->setCode($code + 1);
            $transactionCode = sprintf("%s", str_pad($entity->getCode(),2, '0', STR_PAD_LEFT));
            $entity->setTransactionCode($transactionCode);
            $entity->setProcess('Done');
            $entity->setDiscount($invoice->getDiscount());
            $entity->setPayment($invoice->getPayment());
            $entity->setTotal($invoice->getSubTotal());
            $entity->setTransactionMethod($invoice->getTransactionMethod());
            $entity->setAccountBank($invoice->getAccountBank());
            $entity->setPaymentCard($invoice->getPaymentCard());
            $entity->setCardNo($invoice->getCardNo());
            $entity->setBank($invoice->getBank());
            $entity->setAccountMobileBank($invoice->getAccountMobileBank());
            $entity->setPaymentMobile($invoice->getPaymentMobile());
            $entity->setTransactionId($invoice->getTransactionId());
            $entity->setComment($invoice->getComment());
            if ($invoice->getDmsConfig()->getVatEnable() == 1 && $invoice->getDmsConfig()->getVatPercentage() > 0) {
                $vat = $this->getCulculationVat($invoice, $entity->getPayment());
                $entity->setVat($vat);
            }
            $this->_em->persist($entity);
            $this->_em->flush($entity);
            if($invoice->getPayment() > 0){
                $accountInvoice = $this->_em->getRepository('AccountingBundle:AccountSales')->insertAccountInvoice($entity);
                $this->_em->getRepository('AccountingBundle:Transaction')->hmsSalesTransaction($entity, $accountInvoice);
            }


    }
    public function insertPaymentTransaction(DmsInvoice $invoice, $data)
    {
        $entity = New DmsInvoiceTransaction();
        $code = $this->getLastCode($invoice);
        $entity->setDmsInvoice($invoice);
        $entity->setCode($code + 1);
        $transactionCode = sprintf("%s", str_pad($entity->getCode(),2, '0', STR_PAD_LEFT));
        $entity->setTransactionCode($transactionCode);
        $entity->setProcess($data['process']);
        $entity->setDiscount($data['discount']);
        $entity->setPayment($data['payment']);
        $entity->setTransactionMethod($invoice->getTransactionMethod());
        $entity->setAccountBank($invoice->getAccountBank());
        $entity->setPaymentCard($invoice->getPaymentCard());
        $entity->setCardNo($invoice->getCardNo());
        $entity->setBank($invoice->getBank());
        $entity->setAccountMobileBank($invoice->getAccountMobileBank());
        $entity->setPaymentMobile($invoice->getPaymentMobile());
        $entity->setTransactionId($invoice->getTransactionId());
        $entity->setComment($invoice->getComment());
        if ($invoice->getDmsConfig()->getVatEnable() == 1 && $invoice->getDmsConfig()->getVatPercentage() > 0) {
            $vat = $this->getCulculationVat($invoice, $entity->getPayment());
            $entity->setVat($vat);
        }
        $this->_em->persist($entity);
        $this->_em->flush($entity);

    }

    public function admissionDmsInvoiceTransactionUpdate(DmsInvoiceTransaction $entity )
    {
        $invoice = $entity->getDmsInvoice();
        if ($invoice->getDmsConfig()->getVatEnable() == 1 && $invoice->getDmsConfig()->getVatPercentage() > 0) {
            $vat = $this->getCulculationVat($invoice, $entity->getPayment());
            $entity->setVat($vat);
        }
        if(empty($entity->getTransactionMethod())){
            $entity->setTransactionMethod($this->_em->getRepository('SettingToolBundle:TransactionMethod')->find(1));
        }
        $this->_em->persist($entity);
        $this->_em->flush($entity);
        $accountInvoice = $this->_em->getRepository('AccountingBundle:AccountSales')->insertAccountInvoice($entity);
        $this->_em->getRepository('AccountingBundle:Transaction')->hmsSalesTransaction($entity, $accountInvoice);

    }

    public function hmsSalesTransactionReverse(DmsInvoice $entity)
    {


        $em = $this->_em;

        if(!empty($entity->getAccountSales())){
            /* @var AccountSales $sales*/
            foreach ($entity->getAccountSales() as $sales ){

                $globalOption = $sales->getGlobalOption()->getId();
                $accountRefNo = $sales->getAccountRefNo();
                $transaction = $em->createQuery("DELETE AccountingBundle:Transaction e WHERE e.globalOption = ".$globalOption ." AND e.accountRefNo =".$accountRefNo." AND e.processHead = 'Sales'");
                $transaction->execute();
                $accountCash = $em->createQuery("DELETE AccountingBundle:AccountCash e WHERE e.globalOption = ".$globalOption ." AND e.accountRefNo =".$accountRefNo." AND e.processHead = 'Sales'");
                $accountCash->execute();
            }
        }

        $accountCash = $em->createQuery('DELETE AccountingBundle:AccountSales e WHERE e.dmsInvoices = '.$entity->getId());
        if(!empty($accountCash)){
            $accountCash->execute();
        }

        $transaction = $em->createQuery('DELETE DmsBundle:DmsInvoiceTransaction e WHERE e.dmsInvoice = '.$entity->getId());
        if(!empty($transaction)) {
            $transaction->execute();
        }
    }

    public function hmsAdmissionSalesTransactionReverse(DmsInvoice $entity)
    {

        $em = $this->_em;

        if(!empty($entity->getAccountSales())){
            /* @var AccountSales $sales*/
            foreach ($entity->getAccountSales() as $sales ){

                $globalOption = $sales->getGlobalOption()->getId();
                $accountRefNo = $sales->getAccountRefNo();
                $transaction = $em->createQuery("DELETE AccountingBundle:Transaction e WHERE e.globalOption = ".$globalOption ." AND e.accountRefNo =".$accountRefNo." AND e.processHead = 'Sales'");
                $transaction->execute();
                $accountCash = $em->createQuery("DELETE AccountingBundle:AccountCash e WHERE e.globalOption = ".$globalOption ." AND e.accountRefNo =".$accountRefNo." AND e.processHead = 'Sales'");
                $accountCash->execute();
            }
        }

        $accountCash = $em->createQuery('DELETE AccountingBundle:AccountSales e WHERE e.dmsInvoices = '.$entity->getId());
        if(!empty($accountCash)){
            $accountCash->execute();
        }

        $qb = $this->createQueryBuilder('it');
        $q = $qb->update()
            ->set('it.process', $qb->expr()->literal('In-progress'))
            ->set('it.revised', $qb->expr()->literal(1))
            ->where('it.dmsInvoice = :invoice')
            ->setParameter('invoice', $entity->getId())
            ->getQuery();
        $q->execute();
    }

    public function updateDmsInvoiceTransactionDiscount(DmsInvoice $entity)
    {

        /** @var DmsInvoiceTransaction $transaction */
        foreach ($entity->getDmsInvoiceTransactions() as $transaction) {

            $transaction->setDiscount(0);
            $this->_em->persist($transaction);
            $this->_em->flush($transaction);
        }
        foreach ($entity->getDmsInvoiceTransactions() as $transaction) {

            if( empty($transaction->getPayment()) and empty($transaction->getDiscount())){

                $qb = $this->_em->createQueryBuilder();
                $qb->delete('DmsBundle:DmsInvoiceTransaction', 'trans')
                    ->where($qb->expr()->eq('trans.id', ':id'))
                    ->setParameter('id', $transaction->getId());
                $qb->getQuery()->execute();
            }
        }
    }

    public function removePendingTransaction(DmsInvoice $entity)
    {
        $em = $this->_em;
        $qb = $em->createQueryBuilder();
        $query = $qb->delete('DmsBundle:DmsInvoiceTransaction', 'e')
            ->where('e.dmsInvoice = :dmsInvoice')
            ->setParameter('dmsInvoice', $entity->getId())
            ->andWhere('e.process IN (:process)')
            ->setParameter('process',array('Pending','In-progress'))
            ->getQuery();
        if(!empty($query)) {
            $query->execute();
        }
    }

    public function getCulculationVat(Invoice $invoice, $totalAmount)
    {
        $vat = (($totalAmount * (int)$invoice->getDmsConfig()->getVatPercentage()) / 100);
        return round($vat);
    }

    public function getDmsInvoiceTransactionItems(DmsInvoice $invoice)
    {
        $entities = $invoice->getDmsInvoiceTransactions();
        $data = '';
        $i = 1;

        /** @var DmsInvoiceTransaction $transaction */

        foreach ($entities as $transaction) {

            $date = $transaction->getUpdated()->format('d-m-Y');
            $transactionMethod ='';
            if(!empty($transaction->getTransactionMethod())){
                $transactionMethod = $transaction->getTransactionMethod()->getName();
            }
            $data .= '<tr>';
            $data .= '<td class="numeric" >' . $i . '</td>';
            $data .= '<td class="numeric" >' . $date . '</td>';
            $data .= '<td class="numeric" >' . $transactionMethod. '</td>';
            $data .= '<td class="numeric" >' . $transaction->getDiscount() . '</td>';
            $data .= '<td class="numeric" >' . $transaction->getVat() . '</td>';
            $data .= '<td class="numeric" >' . $transaction->getPayment() . '</td>';
            $data .= '<td class="numeric" >' . $transaction->getCreatedBy() . '</td>';

            $i++;
        }
        return $data;
    }

    public function getLastCode(Invoice $invoice)
    {
        $qb = $this->_em->getRepository('DmsBundle:DmsDmsInvoiceTransaction')->createQueryBuilder('s');
        $qb
            ->select('MAX(s.code)')
            ->where('s.dmsInvoice = :invoice')
            ->setParameter('invoice', $invoice->getId());
            $lastCode = $qb->getQuery()->getSingleScalarResult();
        if (empty($lastCode)) {
            return 0;
        }
        return $lastCode;
    }
}