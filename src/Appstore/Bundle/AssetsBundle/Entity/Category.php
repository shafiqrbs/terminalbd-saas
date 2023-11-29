<?php

namespace Appstore\Bundle\AssetsBundle\Entity;

use Appstore\Bundle\AccountingBundle\Entity\AccountHead;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * ExpenseCategory
 *
 * @Gedmo\Tree(type="materializedPath")
 * @ORM\Table(name="assets_item_category")
 * @ORM\Entity(repositoryClass="Appstore\Bundle\AssetsBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var integer
     *
     * @Gedmo\TreePathSource
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AssetsBundle\Entity\AssetsConfig", inversedBy="categories")
     **/
    protected $config;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\CategoryMeta", mappedBy="category")
     **/
    protected $categoryMetas;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\DepreciationModel", mappedBy="category")
     **/
    protected $depreciationModel;

      /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", mappedBy="category")
     **/
    protected $products;


      /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Item", mappedBy="category")
     **/
    protected $items;

      /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\StockItem", mappedBy="category")
     **/
    protected $stockItems;

     /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\Product", mappedBy="parentCategory")
     **/
    protected $childProducts;

    /**
     * @ORM\OneToMany(targetEntity="Appstore\Bundle\AssetsBundle\Entity\ProductLedger", mappedBy="category")
     **/
    protected $ledgers;

    /**
     * @ORM\ManyToOne(targetEntity="Appstore\Bundle\AccountingBundle\Entity\AccountHead", inversedBy="assetsCategories" )
     **/
    private  $accountHead;


    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="-")
     *      })
     * }, fields={"name"})
     * @Doctrine\ORM\Mapping\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     * })
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category" , mappedBy="parent")
     * @ORM\OrderBy({"name" = "ASC"})
     **/
    private $children;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="level", type="integer", nullable=true)
     */
    private $level;



    /**
     * @Gedmo\TreePath(separator="/")
     * @ORM\Column(name="path", type="string", length=3000, nullable=true)
     */
    private $path;

    /**
     * @var int
     *
     * @ORM\Column(name="sorting", type="smallint")
     */
    private $sorting = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="categoryType", type="string", nullable=true)
     */
    private $categoryType;

    /**
     * @var int
     *
     * @ORM\Column(name="permission", type="string")
     */
    private $permission = 'public';

    /**
     * @var boolean
     *
     * @ORM\Column(name="feature", type="boolean")
     */
    private $feature = false;


    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", nullable = true)
     */
    private $code;


    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
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
     * Get name
     *
     * @return string
     */
    public function getTypeNameHead()
    {
        return $this->categoryType." - ".$this->name." ({$this->accountHead->getName()}) ";
    }



    /**
     * Set slug
     *
     * @param string $slug
     *
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set code
     *
     * @param integer $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }


    /**
     * Set status1
     *
     * @param boolean $status1
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
     * @return mixed
     */
    public function getSTRPadCode()
    {
        $code = str_pad($this->getCode(),2, '0', STR_PAD_LEFT);
        return $code;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return boolean
     */
    public function isFeature()
    {
        return $this->feature;
    }

    /**
     * @param boolean $feature
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;
    }

    /**
     * @return int
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param int $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
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
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getExpenditures()
    {
        return $this->expenditures;
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

    public function getNestedLabel()
    {
        if($this->getLevel() > 1) {
            return $this->formatLabel($this->getLevel() - 1, $this->getName());
        }else{
            return $this->getName();
        }
    }

    public function getParentIdByLevel($level = 1)
    {
        $parentsIds = explode("/", $this->getPath());

        return isset($parentsIds[$level - 1]) ? $parentsIds[$level - 1] : null;

    }

    private function formatLabel($level, $value) {
        return str_repeat("-", $level * 3) . str_repeat(">", $level) . $value;
    }

    /**
     * @return AccountHead
     */
    public function getAccountHead()
    {
        return $this->accountHead;
    }

    /**
     * @param AccountHead $accountHead
     */
    public function setAccountHead($accountHead)
    {
        $this->accountHead = $accountHead;
    }

    /**
     * @return AssetsConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param AssetsConfig $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return CategoryMeta
     */
    public function getCategoryMetas()
    {
        return $this->categoryMetas;
    }

    /**
     * @return string
     */
    public function getCategoryType()
    {
        return $this->categoryType;
    }

    /**
     * @param string $categoryType
     */
    public function setCategoryType($categoryType)
    {
        $this->categoryType = $categoryType;
    }
}

