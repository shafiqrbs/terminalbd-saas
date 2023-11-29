<?php

namespace Appstore\Bundle\EducationBundle\Entity;

use Appstore\Bundle\EducationBundle\Form\EducationParticularType;
use Appstore\Bundle\EducationBundle\Entity\EducationParticular;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ElectionEducationParticular
 *
 * @ORM\Table( name ="education_particular_pattern")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\EducationBundle\Repository\EducationParticularRepository")
 */
class EducationParticularPattern
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationConfig", inversedBy="particularPatterns" , cascade={"detach","merge"} )
     **/
    private  $educationConfig;

     /**
     * @ORM\OneToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationFees", mappedBy="pattern" , cascade={"detach","merge"} )
     **/
    private  $fees;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="classPattern" , cascade={"detach","merge"} )
     **/
    private  $studentClass;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="groupPattern" , cascade={"detach","merge"} )
     **/
    private  $studentGroup;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="shiftPattern" , cascade={"detach","merge"} )
     **/
    private  $studentShift;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="departmentPattern" , cascade={"detach","merge"} )
     **/
    private  $studentDepartment;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="sectionPattern" , cascade={"detach","merge"} )
     **/
    private  $studentSection;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="divisionPattern" , cascade={"detach","merge"} )
     **/
    private  $studentDivision;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="branchPattern" , cascade={"detach","merge"} )
     **/
    private  $studentBranch;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="versionPattern" , cascade={"detach","merge"} )
     **/
    private  $studentVersion;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="semesterPattern" , cascade={"detach","merge"} )
     **/
    private  $studentSemester;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="subjectPattern" , cascade={"detach","merge"} )
     **/
    private  $studentSubject;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticular", inversedBy="sessionPattern" , cascade={"detach","merge"} )
     **/
    private  $studentSession;


	/**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=256, nullable=true)
     */
    private $name;

	/**
     * @var string
     *
     * @ORM\Column(name="assignType", type="string", length=50, nullable=true)
     */
    private $assignType ='student';

	/**
	 * @Gedmo\Slug(fields={"name"})
	 * @Doctrine\ORM\Mapping\Column(length=100)
	 */
	private $slug;


	/**
     * @var int
     *
     * @ORM\Column(name="sorting", type="smallint",  length=2, nullable=true)
     */
    private $sorting = 0;


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
     * @return int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param int $sorting
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    }


    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }



	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getAssignType(){
		return $this->assignType;
	}

	/**
	 * @param string $assignType
	 * student
	 * fees
	 * others
	 */
	public function setAssignType( string $assignType ) {
		$this->assignType = $assignType;
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
	 * @return string
	 */
	public function getEducationParticularCode(){
		return $this->EducationParticularCode;
	}

	/**
	 * @param string $EducationParticularCode
	 */
	public function setEducationParticularCode( string $EducationParticularCode ) {
		$this->EducationParticularCode = $EducationParticularCode;
	}

    /**
     * @return EducationParticular
     */
    public function getStudentSession()
    {
        return $this->studentSession;
    }

    /**
     * @param EducationParticular $studentSession
     */
    public function setStudentSession($studentSession)
    {
        $this->studentSession = $studentSession;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentSubject()
    {
        return $this->studentSubject;
    }

    /**
     * @param EducationParticular $studentSubject
     */
    public function setStudentSubject($studentSubject)
    {
        $this->studentSubject = $studentSubject;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentSemester()
    {
        return $this->studentSemester;
    }

    /**
     * @param EducationParticular $studentSemester
     */
    public function setStudentSemester($studentSemester)
    {
        $this->studentSemester = $studentSemester;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentVersion()
    {
        return $this->studentVersion;
    }

    /**
     * @param EducationParticular $studentVersion
     */
    public function setStudentVersion($studentVersion)
    {
        $this->studentVersion = $studentVersion;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentBranch()
    {
        return $this->studentBranch;
    }

    /**
     * @param EducationParticular $studentBranch
     */
    public function setStudentBranch($studentBranch)
    {
        $this->studentBranch = $studentBranch;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentSection()
    {
        return $this->studentSection;
    }

    /**
     * @param EducationParticular $studentSection
     */
    public function setStudentSection($studentSection)
    {
        $this->studentSection = $studentSection;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentDepartment()
    {
        return $this->studentDepartment;
    }

    /**
     * @param EducationParticular $studentDepartment
     */
    public function setStudentDepartment($studentDepartment)
    {
        $this->studentDepartment = $studentDepartment;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentClass()
    {
        return $this->studentClass;
    }

    /**
     * @param EducationParticular $studentClass
     */
    public function setStudentClass($studentClass)
    {
        $this->studentClass = $studentClass;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentGroup()
    {
        return $this->studentGroup;
    }

    /**
     * @param EducationParticular $studentGroup
     */
    public function setStudentGroup($studentGroup)
    {
        $this->studentGroup = $studentGroup;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentShift()
    {
        return $this->studentShift;
    }

    /**
     * @param EducationParticular $studentShift
     */
    public function setStudentShift($studentShift)
    {
        $this->studentShift = $studentShift;
    }

    /**
     * @return EducationParticular
     */
    public function getStudentDivision()
    {
        return $this->studentDivision;
    }

    /**
     * @param EducationParticular $studentDivision
     */
    public function setStudentDivision($studentDivision)
    {
        $this->studentDivision = $studentDivision;
    }

    public function getPatternName()
    {
        $name = "";
        $class          = !empty($this->getStudentClass()) ? $this->getStudentClass()->getName():'';
        $branch         = !empty($this->getStudentBranch()) ? " => ".$this->getStudentBranch()->getName():'';
        $shift          = !empty($this->getStudentShift()) ? " => ".$this->getStudentShift()->getName():'';
        $section        = !empty($this->getStudentSection()) ? " => ".$this->getStudentSection()->getName():'';
        $semester       = !empty($this->getStudentSemester()) ? " => ".$this->getStudentSemester()->getName():'';
        $group          = !empty($this->getStudentGroup()) ? " => ".$this->getStudentGroup()->getName():'';
        $subject        = !empty($this->getStudentSubject()) ? " => ".$this->getStudentSubject()->getName():'';
        $division       = !empty($this->getStudentDivision()) ? " => ".$this->getStudentDivision()->getName():'';
        $department     = !empty($this->getStudentDepartment()) ? " => ".$this->getStudentDepartment()->getName():'';
        $session        = !empty($this->getStudentSession()) ? " => ".$this->getStudentSection()->getName():'';
        $version        = !empty($this->getStudentVersion()) ? " => ".$this->getStudentVersion()->getName():'';
        $name           = $class.$branch.$shift.$section.$group.$semester.$subject.$division.$department.$session.$version;
        return $name;

    }


}

