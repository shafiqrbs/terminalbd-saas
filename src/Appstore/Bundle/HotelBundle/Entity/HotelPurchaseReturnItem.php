<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * HotelPurchaseReturnItem
 *
 * @ORM\Table(name ="hotel_purchase_return_item")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelPurchaseReturnItemRepository")
 */
class HotelPurchaseReturnItem
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchaseReturn", inversedBy="hotelPurchaseReturnItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $hotelPurchaseReturn;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", inversedBy="hotelPurchaseReturnItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $hotelParticular;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchaseItem", inversedBy="hotelPurchaseReturnItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $hotelPurchaseItem;


    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer",nullable=true)
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="purchasePrice", type="float",nullable=true)
     */
    private $purchasePrice;

    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float",nullable=true)
     */
    private $subTotal;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return HotelPurchaseReturnItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set purchasePrice
     *
     * @param float $purchasePrice
     *
     * @return HotelPurchaseReturnItem
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    /**
     * Get purchasePrice
     *
     * @return float
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
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
     * @return HotelPurchaseReturn
     */
    public function getHotelPurchaseReturn()
    {
        return $this->hotelPurchaseReturn;
    }

    /**
     * @param HotelPurchaseReturn $hotelPurchaseReturn
     */
    public function setHotelPurchaseReturn($hotelPurchaseReturn)
    {
        $this->hotelPurchaseReturn = $hotelPurchaseReturn;
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


}

