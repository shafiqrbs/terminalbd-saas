<?php

namespace Appstore\Bundle\DmsBundle\Entity;

use Appstore\Bundle\DmsBundle\Entity\DmsInvoice;
use Appstore\Bundle\DmsBundle\Entity\DmsParticular;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * DmsInvoiceAccessories
 *
 * @ORM\Table(name ="dms_invoice_accessories")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\DmsBundle\Repository\DmsInvoiceAccessoriesRepository")
 */
class DmsInvoiceAccessories
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsConfig", inversedBy="dmsInvoiceAccessories")
     **/
    private $dmsConfig;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsParticular", inversedBy="dmsInvoiceAccessories" )
     **/
    private  $dmsParticular;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", inversedBy="dmsInvoiceAccessories" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $dmsInvoice;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;


    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="smallint", nullable=true)
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="subTotal", type="float", nullable=true)
     */
    private $subTotal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean" )
     */
    private $status = false;


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
     * @return DmsParticular
     */
    public function getDmsParticular()
    {
        return $this->dmsParticular;
    }

    /**
     * @param DmsParticular $dmsParticular
     */
    public function setDmsParticular($dmsParticular)
    {
        $this->dmsParticular = $dmsParticular;
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
     * @return bool
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return DmsConfig
     */
    public function getDmsConfig()
    {
        return $this->dmsConfig;
    }

    /**
     * @param DmsConfig $dmsConfig
     */
    public function setDmsConfig($dmsConfig)
    {
        $this->dmsConfig = $dmsConfig;
    }


}

