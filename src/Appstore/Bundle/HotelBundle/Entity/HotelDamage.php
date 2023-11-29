<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * HotelDamage
 *
 * @ORM\Table("hotel_damage")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelDamageRepository")
 */
class HotelDamage
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelConfig", inversedBy="hotelDamages")
     **/
    private $hotelConfig;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", inversedBy="hotelDamages", cascade={"persist"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $hotelParticular;
    

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchaseItem", inversedBy="hotelDamages", cascade={"persist"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $hotelPurchaseItem;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="hotelDamage" )
     **/
    private  $createdBy;



    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="purchasePrice", type="float",nullable=true)
     */
    private $purchasePrice;

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
	 * @var string
	 *
	 * @ORM\Column(name="process", type="string", nullable=true)
	 */
	private $process = "Created";


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
     * @return HotelPurchaseItem
     */
    public function getHotelPurchaseItem()
    {
        return $this->hotelPurchaseItem;
    }

    /**
     * @param HotelPurchaseItem $hotelPurchaseItem
     */
    public function setHotelPurchaseItem($hotelPurchaseItem)
    {
        $this->hotelPurchaseItem = $hotelPurchaseItem;
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
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
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
	 * @return string
	 */
	public function getProcess(){
		return $this->process;
	}

	/**
	 * @param string $process
	 */
	public function setProcess( string $process ) {
		$this->process = $process;
	}


}

