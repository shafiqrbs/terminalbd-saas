<?php

namespace Frontend\FrontentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EducationFrontend
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Frontend\FrontentBundle\Repository\EducationFrontendRepository")
 */
class EducationFrontend
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

