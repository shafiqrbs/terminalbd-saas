<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Appstore\Bundle\HospitalBundle\Entity\InvoiceParticular;
use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * HotelInvoiceParticular
 *
 * @ORM\Table( name = "hotel_invoice_particular")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelInvoiceParticularRepository")
 */
class HotelInvoiceParticular
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoice", inversedBy="hotelInvoiceParticulars")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\OrderBy({"id" = "ASC"})
     **/
    private $hotelInvoice;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", inversedBy="hotelInvoiceParticulars", cascade={"persist"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $hotelParticular;

    /**
     * @var string
     *
     * @ORM\Column(name="guestName", type="string", length=225, nullable=true)
     */
    private $guestName;

    /**
     * @var string
     *
     * @ORM\Column(name="guestMobile", type="string", length=225, nullable=true)
     */
    private $guestMobile;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="adult", type="smallint", length = 2, nullable=true)
	 */
	private $adult = 0;

    /**
	 * @var integer
	 *
	 * @ORM\Column(name="child", type="smallint", length = 2, nullable=true)
	 */
	private $child = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="particular", type="text", nullable=true)
     */
    private $particular;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="process", type="text", nullable=true)
	 */
	private $process = 'booked';

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="smallint", length = 5, nullable=true)
     */
    private $quantity = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;


    /**
     * @var float
     *
     * @ORM\Column(name="purchasePrice", type="float", nullable=true)
     */
    private $purchasePrice;

    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float", nullable=true)
     */
    private $subTotal;

	/**
     * @var array
     *
     * @ORM\Column(name="bookingDate", type="simple_array", nullable=true)
     */
    private $bookingDate;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="startDate", type="datetime", nullable=true)
	 */
	private $startDate;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="endDate", type="datetime", nullable=true)
	 */
	private $endDate;


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
     * @return HotelParticular
     */
    public function getHotelParticular()
    {
        return $this->hotelParticular;
    }

    /**
     * @param HotelParticular $hotelParticular
     */
    public function setHotelParticular($hotelParticular)
    {
        $this->hotelParticular = $hotelParticular;
    }

    /**
     * @return HotelInvoice
     */
    public function getHotelInvoice()
    {
        return $this->hotelInvoice;
    }

    /**
     * @param HotelInvoice $hotelInvoice
     */
    public function setHotelInvoice($hotelInvoice)
    {
        $this->hotelInvoice = $hotelInvoice;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function getParticular()
    {
        return $this->particular;
    }

    /**
     * @param string $particular
     */
    public function setParticular($particular)
    {
        $this->particular = $particular;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * @param float $subTotal
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;
    }

    /**
     * @return float
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * @param float $purchasePrice
     */
    public function setPurchasePrice(float $purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
    }

    /**
     * @return HotelProductionExpense
     */
    public function getHotelProductionExpense()
    {
        return $this->hotelProductionExpense;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight(float $height)
    {
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth(float $width)
    {
        $this->width = $width;
    }

    /**
     * @return float
     */
    public function getSubQuantity()
    {
        return $this->subQuantity;
    }

    /**
     * @param float $subQuantity
     */
    public function setSubQuantity(float $subQuantity)
    {
        $this->subQuantity = $subQuantity;
    }

    /**
     * @return float
     */
    public function getTotalQuantity()
    {
        return $this->totalQuantity;
    }

    /**
     * @param float $totalQuantity
     */
    public function setTotalQuantity(float $totalQuantity)
    {
        $this->totalQuantity = $totalQuantity;
    }

	/**
	 * @return string
	 */
	public function getGuestName(){
		return $this->guestName;
	}

	/**
	 * @param string $guestName
	 */
	public function setGuestName($guestName ) {
		$this->guestName = $guestName;
	}

	/**
	 * @return string
	 */
	public function getGuestMobile(){
		return $this->guestMobile;
	}

	/**
	 * @param string $guestMobile
	 */
	public function setGuestMobile($guestMobile ) {
		$this->guestMobile = $guestMobile;
	}

	/**
	 * @return int
	 */
	public function getAdult(){
		return $this->adult;
	}

	/**
	 * @param int $adult
	 */
	public function setAdult($adult ) {
		$this->adult = $adult;
	}

	/**
	 * @return int
	 */
	public function getChild() {
		return $this->child;
	}

	/**
	 * @param int $child
	 */
	public function setChild($child ) {
		$this->child = $child;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDate(){
		return $this->startDate;
	}

	/**
	 * @param \DateTime $startDate
	 */
	public function setStartDate( $startDate ) {
		$this->startDate = $startDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate(): \DateTime {
		return $this->endDate;
	}

	/**
	 * @param \DateTime $endDate
	 */
	public function setEndDate($endDate ) {
		$this->endDate = $endDate;
	}

	/**
	 * @return string
	 */
	public function getProcess(): string {
		return $this->process;
	}

	/**
	 * @param string $process
	 */
	public function setProcess( string $process ) {
		$this->process = $process;
	}

	/**
	 * @return array
	 */
	public function getBookingDate(): array {
		return $this->bookingDate;
	}

	/**
	 * @param array $bookingDate
	 */
	public function setBookingDate( array $bookingDate ) {
		$this->bookingDate = $bookingDate;
	}


}

