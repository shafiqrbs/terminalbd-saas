<?php

namespace Appstore\Bundle\HospitalBundle\Repository;

use Doctrine\Common\Util\Debug;
use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;

/**
 * HmsCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HmsCategoryRepository extends MaterializedPathRepository
{

    public function findWithSearch($data){

        $name = isset($data['name'])? $data['name'] :'';
        $parent = isset($data['parent'])? $data['parent'] :'';
        $qb = $this->createQueryBuilder('category');
        $qb->where('category.level != :null')->setParameter('null', 'N;') ;
        if (!empty($name)) {
            $qb->andWhere($qb->expr()->like("category.name", "'%$name%'"  ));
        }
        if(!empty($parent)){
            $qb->andWhere("category.parent = :parent");
            $qb->setParameter('parent', $parent);
        }
        $qb->orderBy('category.name','ASC');
        $qb->getQuery();
        return  $qb;
    }

    public function getFlatCategoryTree($parent = 'null')
    {

        $categories = $this->createQueryBuilder("node")
            ->where('node.level = :level')
            ->setParameter('level', 1)
            ->orderBy('node.level','ASC')
            ->getQuery()->getResult();

        $arr =array();
        $array =array();
        if(!empty($categories)){

            foreach($categories as $category){
                $arr[] = array(
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'level' => $category->getLevel(),
                    '__children' => $this->childrenHierarchy($category)
                );
            }
            $this->buildFlatCategoryTree($arr , $array);
        }
        return $array == null ? array() : $array;
    }


    public function getParentCategoryTree($parent = 'null')
    {

        $categories = $this->createQueryBuilder("node")
            ->where('node.level = :level')
            ->setParameter('level', 2)
            ->andWhere('node.parent = :parent')
            ->setParameter('parent', $parent)
            ->orderBy('node.level','ASC')
            ->getQuery()->getResult();

        $arr =array();
        $array =array();
        if(!empty($categories)){

            foreach($categories as $category){
                $arr[] = array(
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'level' => $category->getLevel(),
                    '__children' => $this->childrenHierarchy($category)
                );
            }
            $this->buildFlatCategoryTree($arr , $array);
        }
        return $array == null ? array() : $array;
    }

    private function buildFlatCategoryTree($categories, &$array = array())
    {
        usort($categories, function($a, $b){
            return strcmp($a["name"], $b["name"]);
        });

        foreach($categories as $category) {
            $array[] = $this->find($category['id']);
            if(isset($category['__children'])) {
                $this->buildFlatCategoryTree($category['__children'], $array);
            }
        }
    }

    public function setFeatureOrdering($data)
    {
        $i = 1;
        $em = $this->_em;
        $qb = $em->createQueryBuilder();

        foreach ($data as $key => $value){
            $val = ($value) ? $value: 0 ;
            $q = $qb->update('HospitalBundle:Category', 'mg')
                ->set('mg.sorting', $i)
                ->where('mg.id = :id')
                ->setParameter('id', $key)
                ->getQuery()
                ->execute();
            $i++;

        }
    }

    public function setCategoryFeature($data)
    {

        $i = 1;
        $em = $this->_em;
        $qb = $em->createQueryBuilder();
        $isFeatures = $data['feature'];
        $catIDs = $data['catId'];
        foreach ($catIDs as $value){

            $val = in_array($value , $isFeatures ) ? 1 : 0 ;
            $q = $qb->update('HospitalBundle:Category', 'mg')
                ->set('mg.feature', $val)
                ->where('mg.id = :id')
                ->setParameter('id', $value)
                ->getQuery()
                ->execute();
            $i++;

        }
    }

    /**
     * @param $results
     * @return Category[]
     */
    protected function getCategoriesIndexedById($results)
    {
        $categories = array();

        foreach ($results as $category) {
            $categories[$category->getId()] = $category;
        }
        return $categories;
    }


}
