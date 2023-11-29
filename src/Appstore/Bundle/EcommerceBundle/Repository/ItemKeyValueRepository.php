<?php

namespace Appstore\Bundle\EcommerceBundle\Repository;
use Appstore\Bundle\EcommerceBundle\Entity\Item;
use Appstore\Bundle\EcommerceBundle\Entity\ItemKeyValue;
use Appstore\Bundle\MedicineBundle\Entity\MedicineStock;
use Doctrine\ORM\EntityRepository;

/**
 * ItemKeyValueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemKeyValueRepository extends EntityRepository
{
    public function insertItemKeyValue(Item $reEntity,$data)
    {

        $em = $this->_em;
        $i=0;
        if(isset($data['metaKey']) OR isset($data['metaValue']) ){
            foreach ($data['metaKey'] as $value) {
                $metaId = isset($data['metaId'][$i]) ? $data['metaId'][$i] : 0 ;
                $itemKeyValue = $this->_em->getRepository('EcommerceBundle:ItemKeyValue')->findOneBy(array('item'=>$reEntity,'id' => $metaId));
                if(!empty($metaId) and !empty($itemKeyValue)){
                    $this->updateMetaAttribute($itemKeyValue,$data['metaKey'][$i],$data['metaValue'][$i]);
                }else{
                    if(!empty($data['metaKey'][$i]) OR !empty($data['metaValue'][$i]))
                    {
                        $entity = new ItemKeyValue();
                        $entity->setMetaKey($data['metaKey'][$i]);
                        $entity->setMetaValue($data['metaValue'][$i]);
                        $entity->setItem($reEntity);
                        $em->persist($entity);
                        $em->flush($entity);
                    }

                }
                $i++;
            }

        }


    }

    public function updateMetaAttribute(ItemKeyValue $itemKeyValue,$key,$value ='')
    {
            $em = $this->_em;
            $itemKeyValue->setMetaKey($key);
            $itemKeyValue->setMetaValue($value);
            $em->flush();
    }

    public function insertCopyItemKeyValue(Item $itemNew , Item $item)
    {
        $em = $this->_em;
        $i=0;

        if(!empty($item->getItemKeyValues())){

            /* @var ItemKeyValue $attribute */
            foreach ($item->getItemKeyValues() as $attribute) {
                $entity = new ItemKeyValue();
                $entity->setMetaKey($attribute->getMetaKey());
                $entity->setMetaValue($attribute->getMetaValue());
                $entity->setSorting($attribute->getSorting());
                $entity->setItem($itemNew);
                $em->persist($entity);
                $em->flush($entity);

            }

        }
    }

    public function setDivOrdering($data)
    {
        $i = 1;
        $em = $this->_em;
        $qb = $em->createQueryBuilder();
        foreach ($data as $key => $value){
            $qb->update('InventoryBundle:ItemKeyValue', 'mg')
                ->set('mg.sorting', $i)
                ->where('mg.id = :id')
                ->setParameter('id', $key)
                ->getQuery()
                ->execute();
            $i++;

        }

    }

    public function insertMedicineAttribute(Item $item,MedicineStock $copyEntity)
    {
        $em = $this->_em;
        $mode = $copyEntity->getMode();
        $entity = new ItemKeyValue();
        $entity->setItem($item);
        $entity->setSorting(1);
        $entity->setMetaKey("Mode");
        $entity->setMetaValue(ucfirst($mode));
        $em->persist($entity);
        $em->flush($entity);
        if($mode == 'medicine'){
            if($copyEntity->getMedicineBrand()->getMedicineGeneric()){
                $gen = $copyEntity->getMedicineBrand()->getMedicineGeneric()->getName();
                $generic = new ItemKeyValue();
                $generic->setItem($item);
                $generic->setSorting(2);
                $generic->setMetaKey("Generic");
                $generic->setMetaValue(ucfirst($gen));
                $em->persist($generic);
                $em->flush($generic);
            }

            if($copyEntity->getMedicineBrand()->getMedicineCompany()){
                $com = $copyEntity->getMedicineBrand()->getMedicineCompany()->getName();
                $brand = new ItemKeyValue();
                $brand->setItem($item);
                $brand->setSorting(3);
                $brand->setMetaKey("Brand");
                $brand->setMetaValue(ucfirst($com));
                $em->persist($brand);
                $em->flush($brand);
            }

            $form = $copyEntity->getMedicineBrand()->getMedicineForm();
            $formEn = new ItemKeyValue();
            $formEn->setItem($item);
            $formEn->setSorting(3);
            $formEn->setMetaKey("Medicine Form");
            $formEn->setMetaValue(ucfirst($form));
            $em->persist($formEn);
            $em->flush($formEn);

            $strength = $copyEntity->getMedicineBrand()->getStrength();
            $strengthEn = new ItemKeyValue();
            $strengthEn->setItem($item);
            $strengthEn->setSorting(3);
            $strengthEn->setMetaKey("Strength");
            $strengthEn->setMetaValue(ucfirst($strength));
            $em->persist($strengthEn);
            $em->flush($strengthEn);

            $packSize = $copyEntity->getMedicineBrand()->getPackSize();
            $packSizeEn = new ItemKeyValue();
            $packSizeEn->setItem($item);
            $packSizeEn->setSorting(3);
            $packSizeEn->setMetaKey("Pack Size");
            $packSizeEn->setMetaValue(ucfirst($packSize));
            $em->persist($packSizeEn);
            $em->flush($packSizeEn);

            $dar = $copyEntity->getMedicineBrand()->getDar();
            $darEn = new ItemKeyValue();
            $darEn->setItem($item);
            $darEn->setSorting(3);
            $darEn->setMetaKey("DAR");
            $darEn->setMetaValue(ucfirst($dar));
            $em->persist($darEn);
            $em->flush($darEn);

            $useFor = $copyEntity->getMedicineBrand()->getUseFor();
            $useForEn = new ItemKeyValue();
            $useForEn->setItem($item);
            $useForEn->setSorting(3);
            $useForEn->setMetaKey("Use For");
            $useForEn->setMetaValue(ucfirst($useFor));
            $em->persist($useForEn);
            $em->flush($useForEn);

        }
    }

}
