<?php

namespace Appstore\Bundle\DmsBundle\Entity;

use Appstore\Bundle\MedicineBundle\Entity\MedicineBrand;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DmsInvoiceParticular
 *
 * @ORM\Table( name = "dms_invoice_medicine")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\DmsBundle\Repository\DmsInvoiceMedicineRepository")
 */
class DmsInvoiceMedicine
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", inversedBy="invoiceMedicines")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $dmsInvoice;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineBrand", inversedBy="invoiceMedicine")
     **/
    private $medicine;

     /**
     * @var string
     *
     * @ORM\Column(name="medicineQuantity", type="string", length=100, nullable=true)
     */
    private $medicineQuantity;

    /**
     * @var string
     *
     * @ORM\Column(name="medicineDose", type="string", length=100, nullable=true)
     */
    private $medicineDose;

    /**
     * @var string
     *
     * @ORM\Column(name="medicineDoseTime", type="string", length=100, nullable=true)
     */
    private $medicineDoseTime;

    /**
     * @var string
     *
     * @ORM\Column(name="medicineName", type="string", length=255, nullable=true)
     */
    private $medicineName;

    /**
     * @var string
     *
     * @ORM\Column(name="medicineDuration", type="string", length=10, nullable=true)
     */
    private $medicineDuration;


    /**
     * @var string
     *
     * @ORM\Column(name="medicineDurationType", type="string", length=20, nullable=true)
     */
    private $medicineDurationType;


    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DmsInvoice
     */
    public function getDmsInvoice()
    {
        return $this->dmsInvoice;
    }

    /**
     * @param DmsInvoice $dmsInvoice
     */
    public function setDmsInvoice($dmsInvoice)
    {
        $this->dmsInvoice = $dmsInvoice;
    }



    /**
     * @return MedicineBrand
     */
    public function getMedicine()
    {
        return $this->medicine;
    }

    /**
     * @param MedicineBrand $medicine
     */
    public function setMedicine($medicine)
    {
        $this->medicine = $medicine;
    }

    /**
     * @return string
     */
    public function getMedicineQuantity()
    {
        return $this->medicineQuantity;
    }

    /**
     * @param string $medicineQuantity
     */
    public function setMedicineQuantity($medicineQuantity)
    {
        $this->medicineQuantity = $medicineQuantity;
    }

    /**
     * @return string
     */
    public function getMedicineDose()
    {
        return $this->medicineDose;
    }

    /**
     * @param string $medicineDose
     */
    public function setMedicineDose($medicineDose)
    {
        $this->medicineDose = $medicineDose;
    }

    /**
     * @return string
     */
    public function getMedicineDoseTime()
    {
        return $this->medicineDoseTime;
    }

    /**
     * @param string $medicineDoseTime
     */
    public function setMedicineDoseTime($medicineDoseTime)
    {
        $this->medicineDoseTime = $medicineDoseTime;
    }

    /**
     * @return string
     */
    public function getMedicineDuration()
    {
        return $this->medicineDuration;
    }

    /**
     * @param string $medicineDuration
     */
    public function setMedicineDuration($medicineDuration)
    {
        $this->medicineDuration = $medicineDuration;
    }

    /**
     * @return string
     */
    public function getMedicineDurationType()
    {
        return $this->medicineDurationType;
    }

    /**
     * @param string $medicineDurationType
     */
    public function setMedicineDurationType($medicineDurationType)
    {
        $this->medicineDurationType = $medicineDurationType;
    }


    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getMedicineName()
    {
        return $this->medicineName;
    }

    /**
     * @param string $medicineName
     */
    public function setMedicineName($medicineName)
    {
        $this->medicineName = $medicineName;
    }


}

