<?php

namespace Appstore\Bundle\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceSalesItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appstore\Bundle\InventoryBundle\Repository\ServiceSalesItemRepository")
 */
class ServiceSalesItem
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\InventoryBundle\Entity\PurchaseVendorItem", inversedBy="serviceSalesItems" )
     **/
    private  $purchaseVendorItem;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\InventoryBundle\Entity\ServiceSales", inversedBy="serviceSalesItems" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $serviceSales;


    /**
     * @var string
     *
     * @ORM\Column(name="salesPrice", type="decimal")
     */
    private $salesPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="estimatePrice", type="decimal")
     */
    private $estimatePrice;


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
     * @return string
     */
    public function getSalesPrice()
    {
        return $this->salesPrice;
    }

    /**
     * @param string $salesPrice
     */
    public function setSalesPrice($salesPrice)
    {
        $this->salesPrice = $salesPrice;
    }



    /**
     * @return string
     */
    public function getEstimatePrice()
    {
        return $this->estimatePrice;
    }

    /**
     * @param string $estimatePrice
     */
    public function setEstimatePrice($estimatePrice)
    {
        $this->estimatePrice = $estimatePrice;
    }

    /**
     * @return PurchaseVendorItem
     */
    public function getPurchaseVendorItem()
    {
        return $this->purchaseVendorItem;
    }

    /**
     * @param PurchaseVendorItem $purchaseVendorItem
     */
    public function setPurchaseVendorItem($purchaseVendorItem)
    {
        $this->purchaseVendorItem = $purchaseVendorItem;
    }

    /**
     * @return ServiceSales
     */
    public function getServiceSales()
    {
        return $this->serviceSales;
    }

    /**
     * @param ServiceSales $serviceSales
     */
    public function setServiceSales($serviceSales)
    {
        $this->serviceSales = $serviceSales;
    }


}

