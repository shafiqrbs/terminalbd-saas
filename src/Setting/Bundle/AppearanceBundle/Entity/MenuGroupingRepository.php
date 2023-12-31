<?php

namespace Setting\Bundle\AppearanceBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MenuGroupingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MenuGroupingRepository extends EntityRepository
{

    public function insertMenuGrouping($posts,$globalOption,$menuGroup)
    {

        $i = 1;
        $em = $this->_em;
        $menuGroup = $em->getRepository('SettingAppearanceBundle:MenuGroup')->find($menuGroup);
        if(!empty($posts['menuid'])){
            foreach ($posts['menuid'] as $post ){

                $menu = $em->getRepository('SettingAppearanceBundle:Menu')->find($post);
                $menuGroups = $em->getRepository('SettingAppearanceBundle:MenuGrouping')->findBy(array('globalOption'=>$globalOption,'menuGroup'=>$menuGroup,'menu'=>$post));
                if(empty($menuGroups)){
                    $entity = new MenuGrouping();
                    $entity ->setMenu($menu);
                    $entity ->setGlobalOption($globalOption);
                    $entity ->setMenuGroup($menuGroup);
                    $entity ->setSorting($i);
                    $em->persist($entity);
                }
                $i++;
            }
            $em->flush();
        }
        $this->removeMenuGrouping($posts,$globalOption,$menuGroup);

    }


    public function removeMenuGrouping($posts,$globalOption,$menuGroup)
    {
        $em = $this->_em;

        if(!empty($posts['delete'])){
            foreach ($posts['delete'] as $post ){
                $menu = $em->getRepository('SettingAppearanceBundle:MenuGrouping')->findOneBy(array('globalOption' => $globalOption,'menuGroup' => $menuGroup->getId() ,'menu' => $post));
                if(!empty($menu)) {
                    if(!empty($menu->getChildren())){
                        $this->childrenMenuRemove($menu);
                    }
                    $remove = $em->createQuery('DELETE SettingAppearanceBundle:MenuGrouping e WHERE e.id = '.$menu->getId() );
                    $remove->execute();
                }
            }
        }
    }

    private function childrenMenuRemove($menu)
    {
        $em = $this->_em;
        foreach ($menu->getChildren() as $val) {
            $child = $val->getChildren();
            if(!empty($child)){
                 $this->childrenMenuRemove($val);
                 $childRemove = $em->createQuery('DELETE SettingAppearanceBundle:MenuGrouping e WHERE e.id = '.$val->getId() );
                 $childRemove->execute();
            } else {
               $childRemove = $em->createQuery('DELETE SettingAppearanceBundle:MenuGrouping e WHERE e.id = '.$val->getId() );
               $childRemove->execute();
            }
        }
    }

    public function setMenuOrdering($data)
    {

        $i = 1;
        $em = $this->_em;
        $qb = $em->createQueryBuilder();
        foreach ($data as $key => $value){
            $val = ($value) ? $value: 0 ;
            $qb->update('SettingAppearanceBundle:MenuGrouping', 'mg')
                ->set('mg.parent', $val)
                ->set('mg.sorting', $i)
                ->where('mg.id = :id')
                ->setParameter('id', $key)
                ->getQuery()
                ->execute();
            $i++;

        }
    }

    public function getMenuTree($arr)
    {
        $value ='';
        $value .='<ol class="dd-list sortable">';
        foreach ($arr as $val) {

            $menu = $val->getMenu()->getMenu();
            if (!empty($menu)) {
                $subIcon = (count($val->getChildren()) > 0 ) ? 1 : 2 ;
                if($subIcon == 1){
                    $value .= '<li style="display:list-item" class="dd-item dd3-item " id="menuItem_'.$val->getId().'">
                     <div class="menuDiv"><span><div data-id="'.$val->getId().'" class="itemTitle dd-handle dd3-handle"></div>
                     <span class="dd3-content">'. $val->getMenu()->getMenu().'</span></span></div>';
                    $value .= $this->getMenuTree($val->getChildren());
                }else{
                    $value .= '<li style="display:list-item" class="dd-item dd3-item " id="menuItem_'.$val->getId().'">
                     <div class="menuDiv"><span><div data-id="'.$val->getId().'" class="itemTitle dd-handle dd3-handle"></div>
                     <span class="dd3-content">' . $val->getMenu()->getMenu().'</span></span></div>';
                }

                $value .= '</li>';
            } else {
                $value .= '<li style="display:list-item" class="dd-item dd3-item " id="menuItem_'.$val->getId().'">
                     <div class="menuDiv"><span><div data-id="'.$val->getId().'" class="itemTitle dd-handle dd3-handle"></div>
                     <span class="dd3-content">'.$val->getMenu()->getMenu() . '</span></span></div>';
            }
        }
        $value .='</ol>';

        return $value;

    }

    public function getFooterMenu(){

        $em = $this->_em;
        $entity = $em->getRepository('SettingAppearanceBundle:MenuGrouping')->findBy(array('menuGroup'=>3));
        if(!empty($entity)){
            return $entity;
        }
        return false;

    }



}
