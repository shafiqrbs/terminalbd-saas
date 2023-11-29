<?php

namespace Appstore\Bundle\EducationBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountOnlineOrder;
use Appstore\Bundle\AccountingBundle\Entity\AccountVendor;
use Appstore\Bundle\BusinessBundle\Entity\BusinessInvoice;
use Appstore\Bundle\DmsBundle\Entity\DmsInvoice;
use Appstore\Bundle\DoctorPrescriptionBundle\Entity\DpsInvoice;
use Appstore\Bundle\EcommerceBundle\Entity\Order;
use Appstore\Bundle\HospitalBundle\Entity\Invoice;
use Appstore\Bundle\HotelBundle\Entity\HotelInvoice;
use Appstore\Bundle\InventoryBundle\Entity\Sales;
use Appstore\Bundle\MedicineBundle\Entity\MedicineSales;
use Doctrine\ORM\Mapping as ORM;
use Setting\Bundle\LocationBundle\Entity\Location;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * StudentProfile
 *
 * @ORM\Table("education_parent_notification")
 * @ORM\Entity()
 */
class ParentNotification
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
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=100, nullable =true)
	 */
	private $name;


	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="status", type="boolean")
	 */
	private $status = true;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="isNew", type="boolean")
	 */
	private $isNew = true;

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(name="created", type="datetime")
	 */
	private $created;


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
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return StudentProfile
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


}

