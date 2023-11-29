<?php

namespace Appstore\Bundle\EducationBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\LocationBundle\Entity\Location;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * StudentProfile
 *
 * @ORM\Table("education_student")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\EducationBundle\Repository\StudentRepository")
 */
class Student
{
    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\EducationBundle\Entity\EducationConfig", inversedBy="students" , cascade={"detach","merge"} )
     **/
    private  $educationConfig;

      /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\LocationBundle\Entity\Location", inversedBy="students")
     **/
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\LocationBundle\Entity\Country", inversedBy="students")
     **/
    private $country;


    /**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=100, nullable =true)
	 */
	private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="nameBn", type="string", length=150, nullable =true)
     */
    private $nameBn;

    /**
     * @var string
     *
     * @ORM\Column(name="fatherName", type="string", length=100, nullable =true)
     */
    private $fatherName;

    /**
     * @var string
     *
     * @ORM\Column(name="motherName", type="string", length=100, nullable =true)
     */
    private $motherName;

    /**
     * @var string
     *
     * @ORM\Column(name="nid", type="string", length=100, nullable =true)
     */
    private $nid;

     /**
     * @var string
     *
     * @ORM\Column(name="studentId", type="string", length=100, nullable =true)
     */
    private $studentId;

    /**
     * @var string
     *
     * @ORM\Column(name="religion", type="string", length=100, nullable =true)
     */
    private $religion ="Islam" ;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string", length=100, nullable =true)
     */
    private $nationality = 'Bangladeshi';

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255, nullable =true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable =true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=100 , nullable = true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="bloodGroup", type="string", length=20, nullable =true)
     */
    private $bloodGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="dob", type="string", nullable =true)
     */
    private $dob;


    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="smallint",length=3, nullable = true)
     */
    private $age;

    /**
     * @var integer
     *
     * @ORM\Column(name="familyMember", type="smallint",length=3, nullable = true)
     */
    private $familyMember;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10 , nullable = true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="identificationMark", type="string", length=100 , nullable = true)
     */
    private $identificationMark;

    /**
     * @var string
     * @ORM\Column(name="fatherEmail", type="string", length=100 , nullable = true)
     */
    private $fatherEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="motherEmail", type="string", length=100 , nullable = true)
     */
    private $motherEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="fatherProfession", type="string", length=100 , nullable = true)
     */
    private $fatherProfession;


    /**
     * @var string
     *
     * @ORM\Column(name="motherProfession", type="string", length=100 , nullable = true)
     */
    private $motherProfession;

     /**
     * @var string
     *
     * @ORM\Column(name="fatherFacebook", type="string", length=100 , nullable = true)
     */
    private $fatherFacebook;

     /**
     * @var string
     *
     * @ORM\Column(name="motherFacebook", type="string", length=100 , nullable = true)
     */
    private $motherFacebook;


     /**
     * @var string
     *
     * @ORM\Column(name="fatherQualification", type="string", length=100 , nullable = true)
     */
    private $fatherQualification;

    /**
     * @var string
     *
     * @ORM\Column(name="motherQualification", type="string", length=100 , nullable = true)
     */
    private $motherQualification;

     /**
     * @var string
     *
     * @ORM\Column(name="fatherMobile", type="string", length=100 , nullable = true)
     */
    private $fatherMobile;

     /**
     * @var string
     *
     * @ORM\Column(name="motherMobile", type="string", length=100 , nullable = true)
     */
    private $motherMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="fatherLandPhone", type="string", length=100 , nullable = true)
     */
    private $fatherLandPhone;

       /**
     * @var string
     *
     * @ORM\Column(name="motherLandPhone", type="string", length=100 , nullable = true)
     */
    private $motherLandPhone;

     /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string", length=10 , nullable = true)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="presentAddress", type="text", nullable =true)
     */
    private $presentAddress;

	 /**
     * @var string
     *
     * @ORM\Column(name="permanentAddress", type="text", nullable =true)
     */
    private $permanentAddress;

     /**
     * @var string
     *
     * @ORM\Column(name="remark", type="text", nullable =true)
     */
    private $remark;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;


    /**
     * @Assert\File(maxSize="8388608")
     */
    protected $file;


    /**
	 * @var boolean
	 *
	 * @ORM\Column(name="status", type="boolean")
	 */
	private $status = true;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="removeImage", type="boolean")
	 */
	private $removeImage = false;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="isNew", type="boolean")
	 */
	private $isNew = true;

	/**
	 * @var \DateTime
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(name="created", type="datetime")
	 */
	private $created;


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
	 * @return StudentProfile
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
     * @return string
     */
    public function getNameBn()
    {
        return $this->nameBn;
    }

    /**
     * @param string $nameBn
     */
    public function setNameBn(string $nameBn)
    {
        $this->nameBn = $nameBn;
    }

    /**
     * @return string
     */
    public function getFatherName()
    {
        return $this->fatherName;
    }

    /**
     * @param string $fatherName
     */
    public function setFatherName(string $fatherName)
    {
        $this->fatherName = $fatherName;
    }

    /**
     * @return string
     */
    public function getMotherName()
    {
        return $this->motherName;
    }

    /**
     * @param string $motherName
     */
    public function setMotherName(string $motherName)
    {
        $this->motherName = $motherName;
    }

    /**
     * @return string
     */
    public function getNid()
    {
        return $this->nid;
    }

    /**
     * @param string $nid
     */
    public function setNid(string $nid)
    {
        $this->nid = $nid;
    }

    /**
     * @return string
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * @param string $religion
     */
    public function setReligion(string $religion)
    {
        $this->religion = $religion;
    }

    /**
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param string $nationality
     */
    public function setNationality(string $nationality)
    {
        $this->nationality = $nationality;
    }

    /**
     * @return string
     */
    public function getAdditionalMobileNo()
    {
        return $this->additionalMobileNo;
    }

    /**
     * @param string $additionalMobileNo
     */
    public function setAdditionalMobileNo(string $additionalMobileNo)
    {
        $this->additionalMobileNo = $additionalMobileNo;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getBloodGroup()
    {
        return $this->bloodGroup;
    }

    /**
     * @param string $bloodGroup
     */
    public function setBloodGroup(string $bloodGroup)
    {
        $this->bloodGroup = $bloodGroup;
    }

    /**
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param string $dob
     */
    public function setDob(string $dob)
    {
        $this->dob = $dob;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    /**
     * Sets file.
     *
     * @param Student $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return Student
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/domain/'.$this->getBusinessConfig()->getGlobalOption()->getId().'/business/';
    }

    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->path = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @return int
     */
    public function getFamilyMember()
    {
        return $this->familyMember;
    }

    /**
     * @param int $familyMember
     */
    public function setFamilyMember(int $familyMember)
    {
        $this->familyMember = $familyMember;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * @return EducationConfig
     */
    public function getEducationConfig()
    {
        return $this->educationConfig;
    }

    /**
     * @param EducationConfig $educationConfig
     */
    public function setEducationConfig($educationConfig)
    {
        $this->educationConfig = $educationConfig;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getPresentAddress()
    {
        return $this->presentAddress;
    }

    /**
     * @param string $presentAddress
     */
    public function setPresentAddress(string $presentAddress)
    {
        $this->presentAddress = $presentAddress;
    }

    /**
     * @return string
     */
    public function getPermanentAddress()
    {
        return $this->permanentAddress;
    }

    /**
     * @param string $permanentAddress
     */
    public function setPermanentAddress(string $permanentAddress)
    {
        $this->permanentAddress = $permanentAddress;
    }

    /**
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * @param string $profession
     */
    public function setProfession(string $profession)
    {
        $this->profession = $profession;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age)
    {
        $this->age = $age;
    }

    /**
     * @return bool
     */
    public function isRemoveImage()
    {
        return $this->removeImage;
    }

    /**
     * @param bool $removeImage
     */
    public function setRemoveImage(bool $removeImage)
    {
        $this->removeImage = $removeImage;
    }

    /**
     * @return string
     */
    public function getMotherProfession()
    {
        return $this->motherProfession;
    }

    /**
     * @param string $motherProfession
     */
    public function setMotherProfession(string $motherProfession)
    {
        $this->motherProfession = $motherProfession;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $facebook
     */
    public function setFacebook(string $facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getIdentificationMark()
    {
        return $this->identificationMark;
    }

    /**
     * @param mixed $identificationMark
     */
    public function setIdentificationMark($identificationMark)
    {
        $this->identificationMark = $identificationMark;
    }

    /**
     * @return string
     */
    public function getFatherEmail(): string
    {
        return $this->fatherEmail;
    }

    /**
     * @param string $fatherEmail
     */
    public function setFatherEmail(string $fatherEmail)
    {
        $this->fatherEmail = $fatherEmail;
    }

    /**
     * @return string
     */
    public function getMotherEmail(): string
    {
        return $this->motherEmail;
    }

    /**
     * @param string $motherEmail
     */
    public function setMotherEmail(string $motherEmail)
    {
        $this->motherEmail = $motherEmail;
    }

    /**
     * @return mixed
     */
    public function getFatherProfession()
    {
        return $this->fatherProfession;
    }

    /**
     * @param mixed $fatherProfession
     */
    public function setFatherProfession($fatherProfession)
    {
        $this->fatherProfession = $fatherProfession;
    }

    /**
     * @return string
     */
    public function getFatherFacebook(): string
    {
        return $this->fatherFacebook;
    }

    /**
     * @param string $fatherFacebook
     */
    public function setFatherFacebook(string $fatherFacebook)
    {
        $this->fatherFacebook = $fatherFacebook;
    }

    /**
     * @return string
     */
    public function getMotherFacebook(): string
    {
        return $this->motherFacebook;
    }

    /**
     * @param string $motherFacebook
     */
    public function setMotherFacebook(string $motherFacebook)
    {
        $this->motherFacebook = $motherFacebook;
    }

    /**
     * @return string
     */
    public function getFatherQualification(): string
    {
        return $this->fatherQualification;
    }

    /**
     * @param string $fatherQualification
     */
    public function setFatherQualification(string $fatherQualification)
    {
        $this->fatherQualification = $fatherQualification;
    }

    /**
     * @return string
     */
    public function getMotherQualification(): string
    {
        return $this->motherQualification;
    }

    /**
     * @param string $motherQualification
     */
    public function setMotherQualification(string $motherQualification)
    {
        $this->motherQualification = $motherQualification;
    }

    /**
     * @return string
     */
    public function getFatherMobile(): string
    {
        return $this->fatherMobile;
    }

    /**
     * @param string $fatherMobile
     */
    public function setFatherMobile(string $fatherMobile)
    {
        $this->fatherMobile = $fatherMobile;
    }

    /**
     * @return string
     */
    public function getMotherMobile(): string
    {
        return $this->motherMobile;
    }

    /**
     * @param string $motherMobile
     */
    public function setMotherMobile(string $motherMobile)
    {
        $this->motherMobile = $motherMobile;
    }

    /**
     * @return string
     */
    public function getFatherLandPhone(): string
    {
        return $this->fatherLandPhone;
    }

    /**
     * @param string $fatherLandPhone
     */
    public function setFatherLandPhone(string $fatherLandPhone)
    {
        $this->fatherLandPhone = $fatherLandPhone;
    }

    /**
     * @return string
     */
    public function getMotherLandPhone(): string
    {
        return $this->motherLandPhone;
    }

    /**
     * @param string $motherLandPhone
     */
    public function setMotherLandPhone(string $motherLandPhone)
    {
        $this->motherLandPhone = $motherLandPhone;
    }


}

