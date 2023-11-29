<?php

namespace Setting\Bundle\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Product\Bundle\ProductBundle\Entity\Category;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MallConnect
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Setting\Bundle\ContentBundle\Repository\MallConnectRepository")
 */

class MallConnect
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
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\GlobalOption", inversedBy="shops")
     */
    protected $mall;


    /**
     * @ORM\ManyToOne(targetEntity="Setting\Bundle\ToolBundle\Entity\GlobalOption", inversedBy="mallConnects")
     */
    protected $globalOption;

    /**
     * @ORM\ManyToMany(targetEntity="Product\Bundle\ProductBundle\Entity\Category", inversedBy="mallConnects" )
     **/
    protected $categories;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable = true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shopNo", type="string", length=255)
     */
    private $shopNo;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255, nullable = true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="block", type="string", length=255, nullable = true)
     */
    private $block;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255, nullable = true)
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255, nullable = true)
     */
    private $mobile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status = true;

     /**
     * @var boolean
     *
     * @ORM\Column(name="isBrand", type="boolean")
     */
    private $isBrand = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mallStatus", type="boolean")
     */
    private $mallStatus = true;

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
     * Set shopNo
     *
     * @param string $shopNo
     *
     * @return MallConnect
     */
    public function setShopNo($shopNo)
    {
        $this->shopNo = $shopNo;

        return $this;
    }

    /**
     * Get shopNo
     *
     * @return string
     */
    public function getShopNo()
    {
        return $this->shopNo;
    }

    /**
     * Set block
     *
     * @param string $block
     *
     * @return MallConnect
     */
    public function setBlock($block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * Get block
     *
     * @return string
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return MallConnect
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return MallConnect
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return MallConnect
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return GlobalOption
     */
    public function getMall()
    {
        return $this->mall;
    }

    /**
     * @param GlobalOption $mall
     */
    public function setMall($mall)
    {
        $this->mall = $mall;
    }

    /**
     * @return GlobalOption
     */
    public function getGlobalOption()
    {
        return $this->globalOption;
    }

    /**
     * @param GlobalOption $globalOption
     */
    public function setGlobalOption($globalOption)
    {
        $this->globalOption = $globalOption;
    }

    /**
     * @return Category
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Category $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function getMallStatus()
    {
        return $this->mallStatus;
    }

    /**
     * @param boolean $mallStatus
     */
    public function setMallStatus($mallStatus)
    {
        $this->mallStatus = $mallStatus;
    }

    /**
     * Sets file.
     *
     * @param Page $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return Page
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
            : $this->getUploadDir().'/' . $this->path;
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
        return 'uploads/domain/' . $this->getGlobalOption()->getId().'/content';
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
        $this->path = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @return boolean
     */
    public function getIsBrand()
    {
        return $this->isBrand;
    }

    /**
     * @param boolean $isBrand
     */
    public function setIsBrand($isBrand)
    {
        $this->isBrand = $isBrand;
    }

}

