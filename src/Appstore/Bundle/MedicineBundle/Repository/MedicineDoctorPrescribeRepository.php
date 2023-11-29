<?php

namespace Appstore\Bundle\MedicineBundle\Repository;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoice;
use Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsInvoice;
use Appstore\Bundle\MedicineBundle\Entity\MedicineDoctorPrescribe;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;


/**
 * DpsInvoiceParticularRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MedicineDoctorPrescribeRepository extends EntityRepository
{


    public function defaultDmsBeforeMedicine(DmsInvoice $invoice,DmsInvoice $lastObject)
    {
         $em = $this->_em;
         if(!empty($lastObject->getMedicineDoctorPrescribes())){

            /** @var MedicineDoctorPrescribe $item */
            foreach($lastObject->getMedicineDoctorPrescribes() as $item ){
                $entity = new MedicineDoctorPrescribe();
                $entity->setMedicineQuantity($item->getMedicineQuantity());
                $entity->setMedicineDose($item->getMedicineDose());
                $entity->setMedicineDoseTime($item->getMedicineDoseTime());
                $entity->setMedicineDuration($item->getMedicineDuration());
                $entity->setMedicineDurationType($item->getMedicineDurationType());
                $entity->setDmsInvoice($invoice);
                $entity->setMedicine($item->getMedicine());
                $em->persist($entity);
                $em->flush();
            }
        }

    }

    public function insertDmsInvoiceMedicine(DmsInvoice $invoice, $data)
    {

        $em = $this->_em;
        $entity = new MedicineDoctorPrescribe();

        if(!empty($data['medicineQuantity']) and $data['medicineQuantity'] != 'NaN'){
            $entity->setMedicineQuantity($em->getRepository('MedicineBundle:PrescriptionAttribute')->find($data['medicineQuantity'])->getNameBn());
        }
        if(!empty($data['medicineDose']) and $data['medicineDose'] != 'NaN'){
            $entity->setMedicineDose($em->getRepository('MedicineBundle:PrescriptionAttribute')->find($data['medicineDose'])->getNameBn());
        }
        if(!empty($data['medicineDoseTime']) and $data['medicineDoseTime'] != 'NaN'){
            $entity->setMedicineDoseTime($em->getRepository('MedicineBundle:PrescriptionAttribute')->find($data['medicineDoseTime'])->getNameBn());
        }
        if(!empty($data['medicineDurationType']) and $data['medicineDurationType'] != 'NaN'){
            $entity->setMedicineDurationType($em->getRepository('MedicineBundle:PrescriptionAttribute')->find($data['medicineDurationType'])->getNameBn());
        }
        if(!empty($data['medicineDuration'])){
            $entity->setMedicineDuration($data['medicineDuration']);
        }
        if(!empty($data['unit'])){
            $entity->setUnit($em->getRepository('MedicineBundle:PrescriptionAttribute')->find($data['unit'])->getNameBn());
        }
        if(!empty($data['totalQuantity'])){
            $entity->setTotalQuantity($data['totalQuantity']);
        }
        $entity->setDmsInvoice($invoice);
        if(!empty($data['medicineId'])) {
            $medicine = $this->_em->getRepository('MedicineBundle:MedicineBrand')->find($data['medicineId']);
            $entity->setMedicine($medicine);
        }elseif($data['medicine']){
            $entity->setMedicineName($data['medicine']);
        }
        $em->persist($entity);
        $em->flush();

    }

    public function getDmsInvoiceMedicines(DmsInvoice $sales)
    {
        $entities = $sales->getMedicineDoctorPrescribes();
        $data = '';
        $date = '';
        $i = 1;
        /** @var $entity MedicineDoctorPrescribe */
        foreach ($entities as $entity) {
            if($entity->getMedicine()){
                $medicine = $entity->getMedicine()->getMedicineForm().'. '.$entity->getMedicine()->getName() .' '.$entity->getMedicine()->getStrength();
            }else{
                $medicine = $entity->getMedicineName();
            }
            if($entity->getUpdated()->format('d-m-Y') != $date ){
                $date = $entity->getUpdated()->format('d-m-Y');
                $data .= '<tr><th colspan="7">'.$entity->getUpdated()->format('d-m-Y').'</th></tr>';
            }
            $data .= '<tr id="medicine-'.$entity->getId().'">';
            $data .= '<td class="numeric" >' . $i . '</td>';
            $data .= '<td class="numeric" >' . $medicine . '</td>';
            $data .= '<td class="numeric" >' . $entity->getMedicineQuantity(). '</td>';
            $data .= '<td class="numeric" >' . $entity->getMedicineDose() .'-'. $entity->getMedicineDoseTime() . '</td>';
            $data .= '<td class="numeric" >' . $entity->getMedicineDuration().' '. $entity->getMedicineDurationType() . '</td>';
            $data .= '<td class="numeric" >' . $entity->getTotalQuantity().' '. $entity->getUnit() . '</td>';
            $data .= '<td class="numeric" >
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'" title="Are you sure went to delete ?" data-url="/dms/invoice/'. $entity->getId(). '/medicine-delete" href="javascript:" class="btn red mini deleteMedicine" ><i class="icon-trash"></i></a>
            </td>';
            $data .= '</tr>';
            $i++;
        }
        return $data;
    }

    public function defaultDpsBeforeMedicine(DpsInvoice $invoice,DpsInvoice $lastObject)
    {
        $em = $this->_em;
        if(!empty($lastObject->getMedicineDoctorPrescribes())){

            /** @var MedicineDoctorPrescribe $item */
            foreach($lastObject->getMedicineDoctorPrescribes() as $item ){
                $entity = new MedicineDoctorPrescribe();
                $entity->setMedicineQuantity($item->getMedicineQuantity());
                $entity->setMedicineDose($item->getMedicineDose());
                $entity->setMedicineDoseTime($item->getMedicineDoseTime());
                $entity->setMedicineDuration($item->getMedicineDuration());
                $entity->setMedicineDurationType($item->getMedicineDurationType());
                $entity->setDpsInvoice($invoice);
                $entity->setMedicine($item->getMedicine());
                $em->persist($entity);
                $em->flush();
            }
        }

    }

    public function insertDpsInvoiceMedicine(DpsInvoice $invoice, $data)
    {

        $em = $this->_em;
        $entity = new MedicineDoctorPrescribe();
        if(!empty($data['medicineQuantity'])){
            $entity->setMedicineQuantity($data['medicineQuantity']);
        }
        if(!empty($data['medicineDose']) and $data['medicineDose'] != 'NaN'){
            $entity->setMedicineDose($em->getRepository('MedicineBundle:PrescriptionAttribute')->find($data['medicineDose'])->getNameBn());
        }
        if(!empty($data['medicineDoseTime']) and $data['medicineDoseTime'] != 'NaN'){
            $entity->setMedicineDoseTime($em->getRepository('MedicineBundle:PrescriptionAttribute')->find($data['medicineDoseTime'])->getNameBn());
        }
        if(!empty($data['medicineDurationType']) and $data['medicineDurationType'] != 'NaN'){
            $entity->setMedicineDurationType($em->getRepository('MedicineBundle:PrescriptionAttribute')->find($data['medicineDurationType'])->getNameBn());
        }
        if(!empty($data['medicineDuration'])){
            $entity->setMedicineDuration($data['medicineDuration']);
        }

        $entity->setDpsInvoice($invoice);
        if(!empty($data['medicineId'])) {
            $medicine = $this->_em->getRepository('MedicineBundle:MedicineBrand')->find($data['medicineId']);
            $entity->setMedicine($medicine);
        }elseif($data['medicine']){
            $entity->setMedicineName($data['medicine']);
        }
        $em->persist($entity);
        $em->flush();

    }

    public function getDpsInvoiceMedicines(DpsInvoice $sales)
    {
        $entities = $sales->getMedicineDoctorPrescribes();
        $data = '';
        $date = '';
        $i = 1;
        /** @var $entity MedicineDoctorPrescribe */
        foreach ($entities as $entity) {
            if($entity->getMedicine()){
                $medicine = $entity->getMedicine()->getMedicineForm().'. '.$entity->getMedicine()->getName() .' '.$entity->getMedicine()->getStrength();
            }else{
                $medicine = $entity->getMedicineName();
            }
            if($entity->getUpdated()->format('d-m-Y') != $date ){
                $date = $entity->getUpdated()->format('d-m-Y');
                $data .= '<tr><th colspan="6">'.$entity->getUpdated()->format('d-m-Y').'</th></tr>';
            }
            $data .= '<tr id="medicine-'.$entity->getId().'">';
            $data .= '<td class="numeric" >' . $i . '</td>';
            $data .= '<td class="numeric" >' . $medicine . '</td>';
            $data .= '<td class="numeric" >' . $entity->getMedicineQuantity(). '</td>';
            $data .= '<td class="numeric" >' . $entity->getMedicineDose() .'-'. $entity->getMedicineDoseTime() . '</td>';
            $data .= '<td class="numeric" >' . $entity->getMedicineDuration(). $entity->getMedicineDurationType() . '</td>';
            $data .= '<td class="numeric" >
            <a id="'.$entity->getId().'" data-id="'.$entity->getId().'" title="Are you sure went to delete ?" data-url="/dms/invoice/'. $entity->getId(). '/medicine-delete" href="javascript:" class="btn red mini deleteMedicine" ><i class="icon-trash"></i></a>
            </td>';
            $data .= '</tr>';
            $i++;
        }
        return $data;
    }


}
