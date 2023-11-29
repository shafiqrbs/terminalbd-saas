<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * HotelInvoiceReturn
 *
 * @ORM\Table("hotel_invoice_return")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelInvoiceReturnRepository")
 */
class HotelInvoiceReturn
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelConfig", inversedBy="hotelInvoiceReturns")
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $hotelConfig;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", inversedBy="hotelInvoiceReturns", cascade={"persist"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $hotelParticular;


    /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoiceParticular", inversedBy="hotelInvoiceReturns", cascade={"persist"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $hotelInvoiceParticular;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="damage" )
     **/
    private  $createdBy;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string", length=255, nullable=true)
     */
    private $invoice;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer",  nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", nullable=true)
     */
    private $process = "created";


    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="salesPrice", type="float",nullable=true)
     */
    private $salesPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="subTotal", type="float",nullable=true)
     */
    private $subTotal;

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
     * @var string
     *
     * @ORM\Column(name="notes", type="string",  nullable=true)
     */
    private $notes;


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
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
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
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
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
    public function getSalesPrice()
    {
        return $this->salesPrice;
    }

    /**
     * @param float $salesPrice
     */
    public function setSalesPrice($salesPrice)
    {
        $this->salesPrice = $salesPrice;
    }


    /**
     * @return string
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param string $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param string $process
     */
    public function setProcess($process)
    {
        $this->process = $process;
    }



    /**
     * @return HotelConfig
     */
    public function getHotelConfig()
    {
        return $this->hotelConfig;
    }

    /**
     * @param HotelConfig $hotelConfig
     */
    public function setHotelConfig($hotelConfig)
    {
        $this->hotelConfig = $hotelConfig;
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
     * @return HotelInvoiceParticular
     */
    public function getHotelInvoiceParticular()
    {
        return $this->hotelInvoiceParticular;
    }

    /**
     * @param HotelInvoiceParticular $hotelInvoiceParticular
     */
    public function setHotelInvoiceParticular($hotelInvoiceParticular)
    {
        $this->hotelInvoiceParticular = $hotelInvoiceParticular;
    }

}

