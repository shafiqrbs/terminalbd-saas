<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Core\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * HotelReverse
 *
 * @ORM\Table(name="hotel_reverse")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\HotelBundle\Repository\HotelReverseRepository")
 */
class HotelReverse
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelConfig", inversedBy="hotelReverses")
     */
    protected $hotelConfig;

    /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelInvoice", inversedBy="hotelReverse")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $hotelInvoice;


    /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelPurchase", inversedBy="hotelReverse")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $hotelPurchase;


    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     **/
    private  $createdBy;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=50, nullable=true)
     */
    private $process;



    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

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
     * Set name
     *
     * @param string $name
     *
     * @return HotelReverse
     */
    public function setName($name)
    {
        return $this->name = $name;

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

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
	 * @return HotelPurchase
	 */
	public function getHotelPurchase() {
		return $this->hotelPurchase;
	}

	/**
	 * @param HotelPurchase $hotelPurchase
	 */
	public function setHotelPurchase( $hotelPurchase ) {
		$this->hotelPurchase = $hotelPurchase;
	}

	/**
	 * @return HotelConfig
	 */
	public function getHotelConfig() {
		return $this->hotelConfig;
	}

	/**
	 * @param HotelConfig $hotelConfig
	 */
	public function setHotelConfig( $hotelConfig ) {
		$this->hotelConfig = $hotelConfig;
	}

	/**
	 * @return HotelInvoice
	 */
	public function getHotelInvoice() {
		return $this->hotelInvoice;
	}

	/**
	 * @param HotelInvoice $hotelInvoice
	 */
	public function setHotelInvoice( $hotelInvoice ) {
		$this->hotelInvoice = $hotelInvoice;
	}


}

