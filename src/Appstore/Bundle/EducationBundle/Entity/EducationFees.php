<?php

namespace Appstore\Bundle\EducationBundle\Entity;

use Appstore\Bundle\EducationBundle\Form\ParticularType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ElectionParticular
 *
 * @ORM\Table( name ="education_fee")
 * @UniqueEntity(fields="pattern",message="This pattern already used,Please try another.")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\EducationBundle\Repository\EducationFeesRepository")
 */
class EducationFees
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationConfig", inversedBy="fees" , cascade={"detach","merge"} )
     **/
    private  $educationConfig;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationFeesItem", mappedBy="fees" , cascade={"detach","merge"} )
     **/
    private  $feesItems;

    /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticularPattern", inversedBy="fees" , cascade={"detach","merge"} )
     * @ORM\JoinColumn(name="pattern_id", referencedColumnName="id", nullable=true, onDelete="cascade" , unique=true)
     **/
    private  $pattern;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float" )
     */
    private $amount = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean" )
     */
    private $status= true;

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
     * @return bool
     */
    public function getStatus()
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
	 * @return EducationConfig
	 */
	public function getEducationConfig() {
		return $this->educationConfig;
	}

	/**
	 * @param EducationConfig $educationConfig
	 */
	public function setEducationConfig( $educationConfig ) {
		$this->educationConfig = $educationConfig;
	}

    /**
     * @return EducationParticularPattern
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param EducationParticularPattern $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return mixed
     */
    public function getFeesItems()
    {
        return $this->feesItems;
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

