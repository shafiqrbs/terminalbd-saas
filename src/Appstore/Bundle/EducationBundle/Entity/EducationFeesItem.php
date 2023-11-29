<?php

namespace Appstore\Bundle\EducationBundle\Entity;

use Appstore\Bundle\EducationBundle\Form\ParticularType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ElectionParticular
 *
 * @ORM\Table( name ="education_fee_item")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\EducationBundle\Repository\EducationParticularRepository")
 */
class EducationFeesItem
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationFees", inversedBy="feesItems" , cascade={"detach","merge"} )
     **/
    private  $fees;

	/**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationStock", inversedBy="feesItems" , cascade={"detach","merge"} )
     **/
    private  $stock;


    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=true)
     */
    private $amount = 0;


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
     * @return EducationFees
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * @param EducationFees $fees
     */
    public function setFees($fees)
    {
        $this->fees = $fees;
    }

    /**
     * @return EducationStock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param EducationStock $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }


}

