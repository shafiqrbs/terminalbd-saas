<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Cassandra\Date;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BusinessParticular
 *
 * @ORM\Table( name = "assets_office_notes")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\OfficeNoteRepository")
 */
class OfficeNote
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig")
     **/
    private  $config;


     /**
     * @ORM\ManyToMany(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseRequisition", inversedBy="officeNote" )
     **/
    private  $purchaseRequisition;


     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrder", mappedBy="officeNote" )
     **/
    private  $purchaseOrders;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;


     /**
     * @var string
     *
     * @ORM\Column(name="refNo", type="string", length=255, nullable=true)
     */
    private $refNo;


     /**
     * @var boolean
     *
     * @ORM\Column(name="isDelete", type="boolean")
     */
    private $isDelete = false;


     /**
     * @var string
     *
     * @ORM\Column(name="meetingNoteNo", type="string", length=255, nullable=true)
     */
    private $meetingNoteNo;


     /**
     * @var \DateTime
     *
     * @ORM\Column(name="meetingDate", type="date", length=255, nullable=true)
     */
    private $meetingDate;


    /**
     * @var string
     *
     * @ORM\Column(name="requestedBy", type="string", length=255, nullable=true)
     */
    private $requestedBy;



    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=50, nullable=true)
     */
    private $process = "Draft";


    /**
     * @var string
     *
     * @ORM\Column(name="noteMode", type="string", length=50, nullable=true)
     */
    private $noteMode;


     /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", length=10, nullable=true)
     */
    private $code;


     /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", length=10, nullable=true)
     */
    private $amount;


    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;


    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     **/
    private $createdBy;


    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     **/
    private $checkedBy;


    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     **/
    private $approvedBy;


    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="receiveDate", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Assert\File(maxSize="8388608")
     */
    protected $file;


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
     * Sets file.
     *
     * @param Item $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return Item
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

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/domain/'.$this->getConfig()->getGlobalOption()->getId().'/assets/';
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
        $filename = date('YmdHmi') . "_" . $this->getFile()->getClientOriginalName();
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $filename
        );

        // set the path property to the filename where you've saved the file
        $this->path = $filename;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getRefNo()
    {
        return $this->refNo;
    }

    /**
     * @param string $refNo
     */
    public function setRefNo($refNo)
    {
        $this->refNo = $refNo;
    }

    /**
     * @return string
     */
    public function getRequestedBy()
    {
        return $this->requestedBy;
    }

    /**
     * @param string $requestedBy
     */
    public function setRequestedBy($requestedBy)
    {
        $this->requestedBy = $requestedBy;
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
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getCheckedBy()
    {
        return $this->checkedBy;
    }

    /**
     * @param mixed $checkedBy
     */
    public function setCheckedBy($checkedBy)
    {
        $this->checkedBy = $checkedBy;
    }

    /**
     * @return mixed
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * @param mixed $approvedBy
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;
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
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }



    /**
     * @return \DateTime
     */
    public function getMeetingDate()
    {
        return $this->meetingDate;
    }

    /**
     * @param \DateTime $meetingDate
     */
    public function setMeetingDate($meetingDate)
    {
        $this->meetingDate = $meetingDate;
    }

    /**
     * @return string
     */
    public function getMeetingNoteNo()
    {
        return $this->meetingNoteNo;
    }

    /**
     * @param string $meetingNoteNo
     */
    public function setMeetingNoteNo($meetingNoteNo)
    {
        $this->meetingNoteNo = $meetingNoteNo;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return bool
     */
    public function isDelete()
    {
        return $this->isDelete;
    }

    /**
     * @param bool $isDelete
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;
    }

    /**
     * @return string
     */
    public function getNoteMode()
    {
        return $this->noteMode;
    }

    /**
     * @param string $noteMode
     */
    public function setNoteMode($noteMode)
    {
        $this->noteMode = $noteMode;
    }

    /**
     * @return mixed
     */
    public function getPurchaseRequisition()
    {
        return $this->purchaseRequisition;
    }

    /**
     * @param mixed $purchaseRequisition
     */
    public function setPurchaseRequisition($purchaseRequisition)
    {
        $this->purchaseRequisition = $purchaseRequisition;
    }

    /**
     * @return mixed
     */
    public function getPurchaseOrders()
    {
        return $this->purchaseOrders;
    }


}

