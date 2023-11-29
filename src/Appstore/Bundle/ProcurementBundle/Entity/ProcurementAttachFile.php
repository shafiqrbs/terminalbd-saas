<?php

namespace Appstore\Bundle\ProcurementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Mapping\Fixture\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProcurementAttachFile
 *
 * @ORM\Table(name="pro_attach_file")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\ProcurementBundle\Repository\ProcurementAttachFileRepository")
 */
class ProcurementAttachFile
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\ProcurementBundle\Entity\PurchaseOrder", inversedBy="attachFiles" , cascade={"remove"})
    **/
     private  $purchaseOrder;


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
	 * @var string
	 *
	 * @ORM\Column(name="vendor", type="string", nullable=true)
	 */
	private $vendor;

    /**
	 * @var string
	 *
	 * @ORM\Column(name="remark", type="string", nullable=true)
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return ProcurementAttachFile
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }


    /**
     * Sets file.
     *
     * @param ProcurementAttachFile $file
     */

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return ProcurementAttachFile
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
        return 'uploads/domain/purchase-order/';
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
	public function getRemark() {
		return $this->remark;
	}

	/**
	 * @param string $remark
	 */
	public function setRemark( $remark ) {
		$this->remark = $remark;
	}

	/**
	 * @return string
	 */
	public function getVendor() {
		return $this->vendor;
	}

	/**
	 * @param string $vendor
	 */
	public function setVendor( $vendor ) {
		$this->vendor = $vendor;
	}


	/**
	 * @return PurchaseOrder
	 */
	public function getPurchaseOrder() {
		return $this->purchaseOrder;
	}

	/**
	 * @param PurchaseOrder $purchaseOrder
	 */
	public function setPurchaseOrder( $purchaseOrder ) {
		$this->purchaseOrder = $purchaseOrder;
	}


}

