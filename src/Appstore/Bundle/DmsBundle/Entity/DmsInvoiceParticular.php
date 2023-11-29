<?php

namespace Appstore\Bundle\DmsBundle\Entity;

use Appstore\Bundle\HospitalBundle\Entity\InvoiceParticular;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DmsInvoiceParticular
 *
 * @ORM\Table( name = "dms_invoice_particular")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\DmsBundle\Repository\DmsInvoiceParticularRepository")
 */
class DmsInvoiceParticular
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsInvoice", inversedBy="invoiceParticulars")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\OrderBy({"id" = "ASC"})
     **/
    private $dmsInvoice;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsParticular", inversedBy="invoiceParticular", cascade={"persist"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $dmsParticular;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\DmsBundle\Entity\DmsService", inversedBy="invoiceParticular", cascade={"persist"} )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private $dmsService;

    /**
     * @var string
     *
     * @ORM\Column(name="metaKey", type="string", length=225, nullable=true)
     */
    private $metaKey;

    /**
     * @var string
     *
     * @ORM\Column(name="metaValue", type="text", nullable=true)
     */
    private $metaValue;

    /**
     * @var string
     *
     * @ORM\Column(name="diseases", type="text", nullable=true)
     */
    private $diseases;

    /**
     * @var string
     *
     * @ORM\Column(name="teethPosition", type="string", length=25, nullable=true)
     */
    private $teethPosition;

    /**
     * @var array
     *
     * @ORM\Column(name="teethNo", type="array", nullable=true)
     */
    private $teethNo;


    /**
     * @var integer
     *
     * @ORM\Column(name="metaCheck", type="integer", nullable=true)
     */
    private $metaCheck;


    /**
     * @var boolean
     *
     * @ORM\Column(name="metaStatus", type="boolean", nullable=true)
     */
    private $metaStatus;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;


    /**
     * @Assert\File(maxSize="8388608")
     */
    protected $file;



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
     * @return DmsInvoice
     */
    public function getDmsInvoice()
    {
        return $this->dmsInvoice;
    }

    /**
     * @param DmsInvoice $dmsInvoice
     */
    public function setDmsInvoice($dmsInvoice)
    {
        $this->dmsInvoice = $dmsInvoice;
    }

    /**
     * @return string
     */
    public function getMetaKey()
    {
        return $this->metaKey;
    }

    /**
     * @param string $metaKey
     */
    public function setMetaKey($metaKey)
    {
        $this->metaKey = $metaKey;
    }

    /**
     * @return string
     */
    public function getMetaValue()
    {
        return $this->metaValue;
    }

    /**
     * @param string $metaValue
     */
    public function setMetaValue($metaValue)
    {
        $this->metaValue = $metaValue;
    }

    /**
     * @return bool
     */
    public function getMetaStatus()
    {
        return $this->metaStatus;
    }

    /**
     * @param bool $metaStatus
     */
    public function setMetaStatus($metaStatus)
    {
        $this->metaStatus = $metaStatus;
    }

    /**
     * @return DmsParticular
     */
    public function getDmsParticular()
    {
        return $this->dmsParticular;
    }

    /**
     * @param DmsParticular $dmsParticular
     */
    public function setDmsParticular($dmsParticular)
    {
        $this->dmsParticular = $dmsParticular;
    }

    /**
     * @return string
     */
    public function getTeethPosition()
    {
        return $this->teethPosition;
    }

    /**
     * @param string $teethPosition
     */
    public function setTeethPosition($teethPosition)
    {
        $this->teethPosition = $teethPosition;
    }

    /**
     * @return array
     */
    public function getTeethNo()
    {
        return $this->teethNo;
    }

    /**
     * @param array $teethNo
     */
    public function setTeethNo($teethNo)
    {
        $this->teethNo = $teethNo;
    }

    /**
     * @return DmsService
     */
    public function getDmsService()
    {
        return $this->dmsService;
    }

    /**
     * @param DmsService $dmsService
     */
    public function setDmsService($dmsService)
    {
        $this->dmsService = $dmsService;
    }

    /**
     * @return int
     */
    public function getMetaCheck()
    {
        return $this->metaCheck;
    }

    /**
     * @param int $metaCheck
     */
    public function setMetaCheck($metaCheck)
    {
        $this->metaCheck = $metaCheck;
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
     * Sets file.
     *
     * @param InvoiceParticular $file
     */
    public function setFile($file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return InvoiceParticular
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


    public function removeFileImage()
    {
        $path = null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;

        if ($file = $path) {
            unlink($file);
        }
    }


    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/domain/dms/'.$this->getDmsInvoice()->getDmsConfig()->getId().'/';
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
        $filename = date('YmdHmi') . "-" . $this->getFile()->getClientOriginalName();
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
     * @return string
     */
    public function getDiseases()
    {
        return $this->diseases;
    }

    /**
     * @param string $diseases
     */
    public function setDiseases($diseases)
    {
        $this->diseases = $diseases;
    }


}

