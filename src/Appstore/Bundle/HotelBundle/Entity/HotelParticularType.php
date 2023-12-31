<?php

namespace Appstore\Bundle\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * HotelParticular
 *
 * @ORM\Table( name = "hotel_particular_type")
 * @ORM\Entity(repositoryClass="")
 */
class HotelParticularType
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
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelParticular", mappedBy="hotelParticularType" )
     **/
    private $hotelParticulars;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\HotelBundle\Entity\HotelOption", mappedBy="hotelParticularType" )
     **/
    private $hotelOptions;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="parent", type="string", length=255, nullable=true)
     */
    private $parent;

    /**
     * @Gedmo\Translatable
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

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
     * Set name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return HotelParticular
     */
    public function getHotelParticulars()
    {
        return $this->hotelParticulars;
    }

	/**
	 * @return string
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param string $parent
	 */
	public function setParent( $parent ) {
		$this->parent = $parent;
	}

	/**
	 * @return HotelOption
	 */
	public function getHotelOptions() {
		return $this->hotelOptions;
	}


}

