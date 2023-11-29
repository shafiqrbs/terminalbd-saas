<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\VoucherItem;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * ItemMetaAttribute
 *
 * @ORM\Table(name="assets_item_meta_attribute")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\ItemMeatAttributeRepository")
 */
class ItemMetaAttribute
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
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item", inversedBy="itemMetaAttributes" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $item;



    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\CategoryMeta", inversedBy="itemMetaAttributes" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     **/
    private  $categoryMeta;


    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }



    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }



    /**
     * @return mixed
     */
    public function getCategoryMeta()
    {
        return $this->categoryMeta;
    }

    /**
     * @param mixed $categoryMeta
     */
    public function setCategoryMeta($categoryMeta)
    {
        $this->categoryMeta = $categoryMeta;
    }


}

