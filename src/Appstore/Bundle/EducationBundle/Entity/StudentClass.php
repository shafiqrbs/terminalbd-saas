<?php

namespace Appstore\Bundle\EducationBundle\Entity;

use Appstore\Bundle\EducationBundle\Form\ParticularType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * StudentClass
 *
 * @ORM\Table( name ="education_student_class")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\EducationBundle\Repository\EducationParticularRepository")
 */
class StudentClass
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationConfig", inversedBy="particulars" , cascade={"detach","merge"} )
     **/
    private  $educationConfig;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationFees", inversedBy="students" , cascade={"detach","merge"} )
     **/
    private  $fees;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationParticularPattern", inversedBy="students" , cascade={"detach","merge"} )
     **/
    private  $pattern;

    /**
     * @var string
     *
     * @ORM\Column(name="className", type="string", length=100, nullable=true)
     */
    private $className;

    /**
     * @var string
     *
     * @ORM\Column(name="studentGroup", type="string", length=100, nullable=true)
     */
    private $studentGroup;


    /**
     * @var string
     *
     * @ORM\Column(name="studentShift", type="string", length=100, nullable=true)
     */
    private  $studentShift;

    /**
     * @var string
     *
     * @ORM\Column(name="studentDepartment", type="string", length=100, nullable=true)
     */
    private  $studentDepartment;

    /**
     * @var string
     *
     * @ORM\Column(name="studentSection", type="string", length=100, nullable=true)
     */
    private  $studentSection;

    /**
     * @var string
     *
     * @ORM\Column(name="studentDivision", type="string", length=100, nullable=true)
     */
    private  $studentDivision;


    /**
     * @var string
     *
     * @ORM\Column(name="studentBranch", type="string", length=100, nullable=true)
     */
    private  $studentBranch;

    /**
     * @var string
     *
     * @ORM\Column(name="studentVersion", type="string", length=100, nullable=true)
     */
    private  $studentVersion;

    /**
     * @var string
     *
     * @ORM\Column(name="studentSemester", type="string", length=100, nullable=true)
     */
    private  $studentSemester;

    /**
     * @var string
     *
     * @ORM\Column(name="studentSubject", type="string", length=100, nullable=true)
     */
    private  $studentSubject;

    /**
     * @var string
     *
     * @ORM\Column(name="studentSession", type="string", length=100, nullable=true)
     */
    private  $studentSession;

    /**
     * @var string
     *
     * @ORM\Column(name="rollNo", type="string", length=10, nullable=true)
     */
    private $rollNo;

    /**
     * @var string
     *
     * @ORM\Column(name="registrationNo", type="string", length=30, nullable=true)
     */
    private $registrationNo;

    /**
     * @var string
     *
     * @ORM\Column(name="classYear", type="string", length=20, nullable=true)
     */
    private $classYear;

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
    private $status = true;

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
	 * @param EducationConfig $educationConfig
	 */
	public function setEducationConfig( $educationConfig ) {
		$this->educationConfig = $educationConfig;
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
     * @return string
     */
    public function getRollNo()
    {
        return $this->rollNo;
    }

    /**
     * @param string $rollNo
     */
    public function setRollNo(string $rollNo)
    {
        $this->rollNo = $rollNo;
    }

    /**
     * @return string
     */
    public function getRegistrationNo()
    {
        return $this->registrationNo;
    }

    /**
     * @param string $registrationNo
     */
    public function setRegistrationNo(string $registrationNo)
    {
        $this->registrationNo = $registrationNo;
    }

    /**
     * @return string
     */
    public function getClassYear()
    {
        return $this->classYear;
    }

    /**
     * @param string $classYear
     */
    public function setClassYear(string $classYear)
    {
        $this->classYear = $classYear;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getStudentGroup()
    {
        return $this->studentGroup;
    }

    /**
     * @param string $studentGroup
     */
    public function setStudentGroup(string $studentGroup)
    {
        $this->studentGroup = $studentGroup;
    }

    /**
     * @return string
     */
    public function getStudentShift()
    {
        return $this->studentShift;
    }

    /**
     * @param string $studentShift
     */
    public function setStudentShift($studentShift)
    {
        $this->studentShift = $studentShift;
    }

    /**
     * @return string
     */
    public function getStudentDepartment()
    {
        return $this->studentDepartment;
    }

    /**
     * @param string $studentDepartment
     */
    public function setStudentDepartment(string $studentDepartment)
    {
        $this->studentDepartment = $studentDepartment;
    }

    /**
     * @return string
     */
    public function getStudentSection()
    {
        return $this->studentSection;
    }

    /**
     * @param string $studentSection
     */
    public function setStudentSection($studentSection)
    {
        $this->studentSection = $studentSection;
    }

    /**
     * @return string
     */
    public function getStudentDivision()
    {
        return $this->studentDivision;
    }

    /**
     * @param string $studentDivision
     */
    public function setStudentDivision(string $studentDivision)
    {
        $this->studentDivision = $studentDivision;
    }

    /**
     * @return string
     */
    public function getStudentBranch()
    {
        return $this->studentBranch;
    }

    /**
     * @param string $studentBranch
     */
    public function setStudentBranch(string $studentBranch)
    {
        $this->studentBranch = $studentBranch;
    }

    /**
     * @return string
     */
    public function getStudentVersion()
    {
        return $this->studentVersion;
    }

    /**
     * @param string $studentVersion
     */
    public function setStudentVersion(string $studentVersion)
    {
        $this->studentVersion = $studentVersion;
    }

    /**
     * @return string
     */
    public function getStudentSemester()
    {
        return $this->studentSemester;
    }

    /**
     * @param string $studentSemester
     */
    public function setStudentSemester(string $studentSemester)
    {
        $this->studentSemester = $studentSemester;
    }

    /**
     * @return string
     */
    public function getStudentSubject()
    {
        return $this->studentSubject;
    }

    /**
     * @param string $studentSubject
     */
    public function setStudentSubject(string $studentSubject)
    {
        $this->studentSubject = $studentSubject;
    }

    /**
     * @return string
     */
    public function getStudentSession()
    {
        return $this->studentSession;
    }

    /**
     * @param string $studentSession
     */
    public function setStudentSession(string $studentSession)
    {
        $this->studentSession = $studentSession;
    }

}

