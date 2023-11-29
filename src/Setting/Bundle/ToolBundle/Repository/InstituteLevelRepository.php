<?php

namespace Setting\Bundle\ToolBundle\Repository;

use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;
use Setting\Bundle\ToolBundle\Entity\InstituteLevel;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InstituteLevelRepository extends MaterializedPathRepository{


    public function getFlatTree()
    {

        $instituteLevels = $this->childrenHierarchy();

        $this->buildFlatTree($instituteLevels, $array);

        return $array;
    }

    public function getFlatInstituteLevelTree()
    {

        $instituteLevels = $this->childrenHierarchy();

        $this->buildFlatInstituteLevelTree($instituteLevels, $array);

        return $array;
    }

    private function buildFlatTree($instituteLevels, &$array = array())
    {
        usort($instituteLevels, function($a, $b){
            return strcmp($a["name"], $b["name"]);
        });

        foreach($instituteLevels as $instituteLevel) {
            $array[$instituteLevel['id']] = $this->formatLabel($instituteLevel['level'], $instituteLevel['name']);
            if(isset($instituteLevel['__children'])) {
                $this->buildFlatTree($instituteLevel['__children'], $array);
            }
        }
    }

    private function buildFlatInstituteLevelTree($instituteLevels, &$array = array())
    {
        usort($instituteLevels, function($a, $b){
            return strcmp($a["name"], $b["name"]);
        });

        foreach($instituteLevels as $instituteLevel) {
            $array[] = $this->find($instituteLevel['id']);
            if(isset($instituteLevel['__children'])) {
                $this->buildFlatInstituteLevelTree($instituteLevel['__children'], $array);
            }
        }
    }

    private function formatLabel($level, $value) {
        $level = $level - 1;
        return str_repeat("-", $level * 3) . str_repeat(">", $level) . "$value";
    }


    public function getInstituteLevelOptions(){

        $ret = array();
        $em = $this->_em;
        $instituteLevels = $em->getRepository('SettingToolBundle:InstituteLevel')->findBy(array(),array('name'=>'asc'));

        foreach( $instituteLevels as $cat ){
            if( !$cat->getParent() ){
                continue;
            }
            if(!array_key_exists($cat->getParent()->getName(), $ret) ){
                $ret[ $cat->getParent()->getName() ] = array();
            }
            $ret[ $cat->getParent()->getName() ][ $cat->getId() ] = $cat;
        }
        return $ret;
    }

    /**
     * @param $instituteLevels InstituteLevel[]
     * @return array
     */
    public function buildInstituteLevelGroup($instituteLevels)
    {
        $result = array();

        foreach($instituteLevels as $instituteLevel) {
            $parentInstituteLevel = $this->getParentInstituteLevelByLevel($instituteLevel, 2);


            if(empty($parentInstituteLevel)) {
                continue;
            }

            $parentId = $parentInstituteLevel->getId();

            if(!isset($result[$parentId])) {
                $result[$parentId] = array(
                    'name' =>  $parentInstituteLevel->getName(),
                    'slug' =>  $parentInstituteLevel->getSlug(),
                    '__children' =>  array(),
                );
            }

            $result[$parentId]['__children'][] = array(
                'name' => $instituteLevel->getName(),
                'slug' => $instituteLevel->getSlug()
            );
        }

        return $result;
    }

    public function getInstituteLevelOptionGroup()
    {
        $results = $this->createQueryBuilder('node')
            ->orderBy('node.level, node.name', 'ASC')
            ->where('node.level > 2')
            ->getQuery()
            ->getResult()
        ;

        $instituteLevels = $this->getinstituteLevelsIndexedById($results);

        $grouped = array();

        foreach ($instituteLevels as $instituteLevel) {
            switch($instituteLevel->getLevel()) {
                case 3: break;
                default:
                    $grouped[$instituteLevels[$instituteLevel->getParentIdByLevel(3)]->getName()][$instituteLevel->getId()] = $instituteLevel;
            }
        }

        return $grouped;
    }

    /**
     * @param InstituteLevel $instituteLevel
     * @param int $level
     * @return InstituteLevel
     */
    public function getParentInstituteLevelByLevel(InstituteLevel $instituteLevel, $level = 1)
    {
        return $this->find($instituteLevel->getParentIdByLevel($level));
    }

    /**
     * @param $results
     * @return InstituteLevel[]
     */
    protected function getinstituteLevelsIndexedById($results)
    {
        $instituteLevels = array();

        foreach ($results as $instituteLevel) {
            $instituteLevels[$instituteLevel->getId()] = $instituteLevel;
        }
        return $instituteLevels;
    }

    public function getInstituteLevelBaseVendor()
    {
        return false;
    }

    public function getSelectedInstitutes($categories,$entity)
    {

        $array =array();
        if(!empty($entity->getInstituteLevels())){

            $selectInstituteLevel = $entity->getInstituteLevels();
            foreach($selectInstituteLevel as $row ){
                $array[] = $row->getId();
            }

        }

        $value ='';
        $value .='<ul>';
        foreach ($categories as $val) {

            $checkd = in_array($val->getId(), $array)? 'checked':'';

            if (!empty($val->getName())) {

                    $subIcon = (count($val->getChildren()) > 0 ) ? 1 : 2 ;
                    if($subIcon == 1){
                        $value .= '<li class="dd-item1" ><input type="checkbox" '.$checkd.' name="subinstituteLevels[]" value="'.$val->getId().'" >' . $val->getName();
                        $value .= $this->getSelectedInstitutes($val->getChildren(),$entity);
                    }else{
                        $value .= '<li class="dd-item1" ><input type="checkbox" '.$checkd.' name="subinstituteLevels[]" value="'.$val->getId().'" >' . $val->getName();
                    }
                    $value .= '</li>';

            }

        }
        $value .='</ul>';

        return $value;



    }
    public function getInstituteLevelUnderScholars($categories,$entity)
    {

        $array =array();
        if(!empty($entity->getinstituteLevels())){

            $selectInstituteLevel = $entity->getinstituteLevels();
            foreach($selectInstituteLevel as $row ){
                $array[] = $row->getId();
            }
        }

        $value ='';
        $value .='<ul>';
        foreach ($categories as $val) {

            $checkd = in_array($val->getId(), $array)? 'checked':'';

            if (!empty($val->getName())) {

                    $subIcon = (count($val->getChildren()) > 0 ) ? 1 : 2 ;
                    if($subIcon == 1){
                        $value .= '<li class="dd-item1" ><input type="checkbox" '.$checkd.' name="subinstituteLevels[]" value="'.$val->getId().'" >' . $val->getName();
                        $value .= $this->getInstituteLevelUnderScholars($val->getChildren(),$entity);
                    }else{
                        $value .= '<li class="dd-item1" ><input type="checkbox" '.$checkd.' name="subinstituteLevels[]" value="'.$val->getId().'" >' . $val->getName();
                    }
                    $value .= '</li>';

            }

        }
        $value .='</ul>';

        return $value;

    }

    public function getInstituteLevelList($categories=NULL)
    {

        $em = $this->_em;
        $categories     = $em -> getRepository('SettingToolBundle:InstituteLevel')->findBy(array('status'=>1,'parent'=>1),array('name'=>'asc'));

        $array =array();
        $value ='';
        $value .='<ul>';
        foreach ($categories as $val) {

            $checkd = in_array($val->getId(), $array)? 'checked':'';

            if (!empty($val->getName())) {

                $subIcon = (count($val->getChildren()) > 0 ) ? 1 : 2 ;
                if($subIcon == 1){
                    $value .= '<li><input type="checkbox" '.$checkd.' name="subinstituteLevels[]" value="'.$val->getId().'" >' . $val->getName();
                    $value .= $this->getInstituteLevelList($val->getChildren());
                }else{
                    $value .= '<li><input type="checkbox" '.$checkd.' name="subinstituteLevels[]" value="'.$val->getId().'" >' . $val->getName();
                }
                $value .= '</li>';

            }

        }
        $value .='</ul>';

        return $value;



    }





}
