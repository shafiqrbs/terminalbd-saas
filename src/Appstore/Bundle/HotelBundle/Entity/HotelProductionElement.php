<?php

namespace Appstore\Bundle\HotelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * HotelProductionElement
 *
 * @ORM\Table(name ="hotel_production_element")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelProductionElementRepository")
 */
class HotelProductionElement
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", inversedBy="productionElements" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $hotelParticular;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", inversedBy="production" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $particular;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="purchasePrice", type="float", nullable = true)
     */
    private $purchasePrice;


    /**
     * @var float
     *
     * @ORM\Column(name="salesPrice", type="float", nullable = true)
     */
    private $salesPrice;


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
     * @param integer $quantity
     */

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
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
     * @param float $purchasePrice
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
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
     * Set salesPrice
     * @param float $salesPrice
     */
    public function setSalesPrice($salesPrice)
    {
        $this->salesPrice = $salesPrice;
    }

    /**
     * Get salesPrice
     *
     * @return float
     */
    public function getSalesPrice()
    {
        return $this->salesPrice;
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
     * @return HotelParticular
     */
    public function getParticular()
    {
        return $this->particular;
    }

    /**
     * @param HotelParticular $particular
     */
    public function setParticular($particular)
    {
        $this->particular = $particular;
    }


}

