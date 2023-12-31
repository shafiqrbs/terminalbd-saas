<?php

namespace Appstore\Bundle\EcommerceBundle\Repository;
use Appstore\Bundle\EcommerceBundle\Entity\ItemMetaAttribute;
use Appstore\Bundle\EcommerceBundle\Entity\Item;
use Doctrine\ORM\EntityRepository;

/**
 * ItemMeatAttributeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemMeatAttributeRepository extends EntityRepository
{
    public function insertProductAttribute(Item $reEntity,$data)
    {

        $em = $this->_em;
        $i=0;

        if(isset($data['attributeId'])){
            foreach ($data['attributeId'] as $value) {

                $metaAttribute = $this->_em->getRepository('EcommerceBundle:ItemMetaAttribute')->findOneBy(array('item'=>$reEntity,'itemAttribute'=>$value));
                if(!empty($metaAttribute)){
                    $this->updateMetaAttribute($metaAttribute,$data['value'][$i]);
                }else{
                    $itemAttribute= $this->_em->getRepository('EcommerceBundle:ItemAttribute')->find($value);
                    $entity = new ItemMetaAttribute();
                    $entity->setValue($data['value'][$i]);
                    $entity->setItemAttribute($itemAttribute);
                    $entity->setItem($reEntity);
                    $em->persist($entity);
                }
                $i++;
            }
            $em->flush();
        }


    }

    public function updateMetaAttribute($metaAttribute,$value)
    {
            $em = $this->_em;
            $metaAttribute->setValue($value);
            $em->flush();
    }

    public function insertCopyProductAttribute(Item $purchaseVendorItem , Item $itemOld)
    {
        $em = $this->_em;
        $i=0;

        if(!empty($itemOld->getItemMetaAttributes())){
            /* @var ItemMetaAttribute $attribute */
            foreach ($itemOld->getItemMetaAttributes() as $attribute) {
                $entity = new ItemMetaAttribute();
                $entity->setValue($attribute->getValue());
                $entity->setItemAttribute($attribute);
                $entity->setItem($purchaseVendorItem);
                $em->persist($entity);
                $em->flush($entity);

            }

        }
    }

}
