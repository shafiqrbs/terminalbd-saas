<?php

namespace Appstore\Bundle\AssetsBundle\Importer;
use Appstore\Bundle\AssetsBundle\Entity\Brand;
use Appstore\Bundle\AssetsBundle\Entity\Category;
use Appstore\Bundle\AssetsBundle\Entity\Item;
use Appstore\Bundle\AssetsBundle\Entity\ItemImport;
use Appstore\Bundle\AssetsBundle\Entity\Particular;
use Appstore\Bundle\AssetsBundle\Repository\BrandRepository;
use Appstore\Bundle\AssetsBundle\Repository\CategoryRepository;
use Appstore\Bundle\AssetsBundle\Repository\ParticularRepository;
use Setting\Bundle\LocationBundle\Entity\Country;
use Setting\Bundle\LocationBundle\Repository\CountryRepository;
use Setting\Bundle\ToolBundle\Entity\ProductUnit;
use Setting\Bundle\ToolBundle\Repository\ProductUnitRepository;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;


class StockImport
{
    use ContainerAwareTrait;

    private $data = array();
    private $cache = array();

    /* @var $import  ItemImport */
    protected $import;

    public function isValid($data) {

		return true;

	}
    public function setImport($import)
    {
        $this->import = $import;
    }

    public function import($data)
    {
        $this->data = $data;
        foreach($this->data as $key => $item) {
            $name = $item['Name'];
            $Caliber  = $item['Caliber'];
            $ProductGroup = isset($item['ProductGroup']) ? $item['ProductGroup']:'';
            $Category = isset($item['Category']) ? $item['Category']:'';
            $Model = isset($item['Model']) ? $item['Model']:'';
            $Brand = isset($item['Brand']) ? $item['Brand']:'';
            $Description = isset($item['Description']) ? $item['Description']:'';
            $Price = isset($item['Price']) ? $item['Price']:'';
            $Stock = isset($item['Stock']) ? $item['Stock']:'';
            $country = isset($item['Country']) ? $item['Country']:'';
            $Unit = isset($item['Unit']) ? $item['Unit']:'';
            $config = $this->import->getAssetsConfig();
            $exist = $this->getDoctrain()->getRepository('AssetsBundle:Item')->findOneBy(array('config' => $config,'name' => $name));
            if(empty($exist)) {
                $medicine = new Item();
                $medicine->setConfig($config);
                $medicine->setName(ucfirst(strtolower($name)));
                $medicine->setCaliber(ucfirst(strtolower($Caliber)));
                $medicine->setDescription($Description);
                $medicine->setModel($Model);
                $medicine->setUnitPrice($Price);
                $medicine->setSalesPrice($Price);
                if ($ProductGroup) {
                    $ProductGroup = $this->getProductGroup(ucfirst(strtolower($ProductGroup)));
                    $medicine->setProductGroup($ProductGroup);
                }
                 if ($Brand) {
                    $Brand = $this->getBrand(ucfirst(strtolower($Category)));
                    $medicine->setBrand($Brand);
                }
                if ($Category) {
                    $cat = $this->getcategory(ucfirst(strtolower($Category)));
                    $medicine->setCategory($cat);
                }
                if ($Unit) {
                    $cat = $this->getProductUnit(ucfirst(strtolower($Unit)));
                    $medicine->setProductUnit($cat);
                }
                if ($country) {
                    $country = $this->getCountry(ucfirst(strtolower($country)));
                    $medicine->setCountry($country);
                }
                $this->persist( $medicine );
                $this->flush();
            }

        }
    }

    private function getcategory($item)
    {
        $config = $this->import->getAssetsConfig();
        $categoryRepository = $this->getCategoryRepository();

        $size = $categoryRepository->findOneBy(array(
            'config'   => $config,
            'name'              => $item
        ));
        if($size){
            return $size;
        }else{
            $size = new Category();
            $size->setConfig($config);
            $size->setName($item);
            $size = $this->save($size);
            return $size;
        }

    }

    private function getBrand($item)
    {
        $config = $this->import->getAssetsConfig();
        $barndRepository = $this->getBrandRepository();

        $size = $barndRepository->findOneBy(array(
            'config'   => $config,
            'name'              => $item
        ));
        if($size){
            return $size;
        }else{
            $size = new Brand();
            $size->setConfig($config);
            $size->setName($item);
            $size = $this->save($size);
            return $size;
        }

    }

    private function getProductGroup($item)
    {
        $config = $this->import->getAssetsConfig();
        $barndRepository = $this->getProductGroupRepository();

        $size = $barndRepository->findOneBy(array(
            'config'   => $config,
            'name'              => $item
        ));
        if($size){
            return $size;
        }else{
            $size = new Particular();
            $size->setConfig($config);
            $size->setName($item);
            $size = $this->save($size);
            return $size;
        }

    }

    private function getCountry($item)
    {
        $countryRepository = $this->getCountryRepository();
        $size = $countryRepository->findOneBy(array(
            'name'              => $item
        ));
        if($size){
            return $size;
        }
        return  false;

    }

    private function getProductUnit($item)
    {

        $unit = $this->getProductUnitRepository();
        $size = $unit->findOneBy(array(
            'name'              => $item
        ));
        if($size){
            return $size;
        }else{
            $size = new ProductUnit();
            $size->setName($item);
            $size = $this->save($size);
            return $size;
        }

    }

    /**
     * @return CategoryRepository
     */
    private function getCategoryRepository()
    {
        return $this->getDoctrain()->getRepository(Category::class);
    }

    /**
     * @return BrandRepository
     */
    private function getBrandRepository()
    {
        return $this->getDoctrain()->getRepository(Brand::class);
    }


     /**
     * @return ParticularRepository
     */
    private function getProductGroupRepository()
    {
        return $this->getDoctrain()->getRepository(Particular::class);
    }


    /**
     * @return CountryRepository
     */
    private function getCountryRepository()
    {
        return $this->getDoctrain()->getRepository(Country::class);
    }



    /**
     * @return ProductUnitRepository
     */
    private function getProductUnitRepository()
    {
        return $this->getDoctrain()->getRepository(ProductUnit::class);
    }



    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    private function getEntityManager()
    {
        return $this->getDoctrain()->getManager();
    }



    private function save($entity){
        $this->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    private function persist($entity){
        $this->getEntityManager()->persist($entity);
    }

    private function getCachedData($type, $key)
    {
        if(isset($this->cache[$type][$key])){
            return $this->cache[$type][$key];
        }

        return NULL;
    }

    private function setCachedData($type, $key, $value)
    {
        $this->cache[$type][$key] = $value;
    }


    private function flush()
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private function getDoctrain()
    {
        return $this->container->get('doctrine');
    }

    function sentence_case($string) {
        $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $new_string = '';
        foreach ($sentences as $key => $sentence) {
            $new_string .= ($key & 1) == 0?
                ucfirst(strtolower(trim($sentence))) :
                $sentence.' ';
        }
        return trim($new_string);
    }


}