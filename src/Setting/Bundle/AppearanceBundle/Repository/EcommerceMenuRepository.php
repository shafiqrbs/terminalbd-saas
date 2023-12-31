<?php

namespace Setting\Bundle\AppearanceBundle\Repository;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityRepository;
use Product\Bundle\ProductBundle\Entity\Category;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * EcommerceMenuRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EcommerceMenuRepository extends EntityRepository
{
    public function setOrdering($data)
    {

        $i = 1;
        $em = $this->_em;
        $qb = $em->createQueryBuilder();

        foreach ($data as $key => $value){

            $qb->update('SettingAppearanceBundle:EcommerceMenu', 'mg')
                ->set('mg.sorting', $i)
                ->where('mg.id = :id')
                ->setParameter('id', $key)
                ->getQuery()
                ->execute();
            $i++;

        }
    }

    public function getActiveMenus($subdomain)
    {
        $option = $this->_em->getRepository('SettingToolBundle:GlobalOption')->findOneBy(array('subDomain' => $subdomain));
        return $this->findBy(
            array('status' => true,'globalOption' => $option),
            array('sorting'=>'asc')
        );
    }

    public function getMegaMenuCategory(GlobalOption $globalOption , $categories,$column){

        $menues = array();

        foreach ($categories as $category) {
            $parent = $category->getParent();
            if(!isset($menues[$parent->getId()])) {
                $menues[$parent->getId()]  = array(
                    'id' => $parent->getId(),
                    'name' => $parent->getName(),
                    'children' => array()
                );
            }
            $menues[$parent->getId()]['children'][] = $category;
        }
        $str = "";
        foreach ($menues as $item) {
            $str .= '<ul class="'.$column.'" data-match-height="menu-category" >';
            $str .= '<li class="dropdown-header">' . $item['name'] .'</li>';
            foreach ($item['children'] as $child) {
                $str .= '<li><a href="/product/category/'. $child->getSlug() .'">' . $child->getName() .'</a></li>';
            }
            $str .= '</ul>';
        }
        return $str;

    }

    public function getSimpleCategoryMenu($categories){

        $menues = array();
        foreach ($categories as $category) {
            $parent = $category->getParent();
            if(!isset($menues[$parent->getId()])) {
                $menues[$parent->getId()]  = array(
                    'id' => $parent->getId(),
                    'name' => $parent->getName(),
                    'slug' => $parent->getSlug(),
                    'children' => array()
                );
            }
            $menues[$parent->getId()]['children'][] = $category;
        }
        $str = "";
        foreach ($menues as $item) {

            $str .= "<li><a href='/product/category/{$item['slug'] }' class='menuLinkx' data-action='/product/category/{$item['slug'] }'>{$item['name']}</a>";
            $str .= "<ul class=''>";
            foreach ($item['children'] as $child) {
                $str .= "<li class='' ><a href='/product/category/{$child->getSlug() }'>{$child->getName() }</a>";
                $row = $this->_em->getRepository('ProductProductBundle:Category')->find($child->getId());
                $str .= $this->menuCategoryDropdownChild($row->getChildren());
                $str .= "</li>";
            }
            $str .= "</ul></li>";
        }
        return $str;

    }

    public function menuCategoryDropdownChild($row){

        $result = "";
      //  $row = $this->_em->getRepository('ProductProductBundle:Category')->find($id);
        if (!empty($row)) {
            $result .= "<ul class=''>";
            foreach ($row as $child):
                if(!empty($child->getChildren())){
                    $result.= "<li class=''><a class='' href='/product/category/{$child->getSlug()}'>{$child->getName()}</a>";
                   // $result.= $this->menuCategoryDropdownChild($child->getChildren());
                    $result.= "</li>";
                }
            endforeach;
            $result.= "</ul>";
        }else {
            $result.= "<li><a class='' href='/product/category/{$row->getSlug()}'>{$row->getName()}</a></li>";
        }
        return $result;

    }
}
