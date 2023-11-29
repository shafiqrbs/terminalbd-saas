<?php

namespace Appstore\Bundle\MedicineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * MedicineVendor
 *
 * @ORM\Table(name="medicine_particular_type")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\MedicineBundle\Repository\MedicineParticularTypeRepository")
 */
class MedicineParticularType
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
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\MedicineBundle\Entity\MedicineParticular", mappedBy="particularType")
     */
    protected $particulars;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="modeFor", type="string", length=50, nullable=true)
     */
    private $modeFor;

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
     * @return MedicineParticular
     */
    public function getParticulars()
    {
        return $this->particulars;
    }

    /**
     * @return string
     */
    public function getModeFor()
    {
        return $this->modeFor;
    }

    /**
     * @param string $modeFor
     */
    public function setModeFor($modeFor)
    {
        $this->modeFor = $modeFor;
    }

}

