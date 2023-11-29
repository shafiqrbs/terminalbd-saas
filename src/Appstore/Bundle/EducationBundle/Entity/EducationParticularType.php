<?php

namespace Appstore\Bundle\EducationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * MedicineVendor
 *
 * @ORM\Table(name="education_particular_type")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\EducationBundle\Repository\EducationParticularTypeRepository")
 */
class EducationParticularType
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
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", mappedBy="particularType")
     */
    protected $particulars;

  
    /**
     * @ORM\ManyToMany(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationConfig", mappedBy="type")
     */
    protected $educationConfig;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="ordering", type="smallint", length=2, nullable=true)
     */
    private $ordering;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @Doctrine\ORM\Mapping\Column(length=255)
     */
    private $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean" )
     */
    private $status=true;


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
     * @return MedicineVendor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
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
     * @return EducationParticular
     */
    public function getParticulars()
    {
        return $this->particulars;
    }


    /**
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @param int $ordering
     */
    public function setOrdering(int $ordering)
    {
        $this->ordering = $ordering;
    }

}

