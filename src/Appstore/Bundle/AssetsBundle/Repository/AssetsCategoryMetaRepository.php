<?php

namespace Appstore\Bundle\AssetsBundle\Repository;

use Appstore\Bundle\AssetsBundle\Entity\AssetsCategory;
use Appstore\Bundle\AssetsBundle\Entity\AssetsCategoryMeta;
use Doctrine\ORM\EntityRepository;


/**
 * CategoryMeta
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AssetsCategoryMetaRepository extends EntityRepository
{

    public function pageMeta(AssetsCategory $category, $data)
    {

        $em = $this->_em;
        if ($category and isset($data['metaValue']) and !empty($data['metaValue'])) {

            $i = 0;
            foreach ($data['metaValue'] as $row):

                if (isset($data['metaValue'][$i]) and !empty($data['metaValue'][$i])) {

                    if (!empty($data['metaValue'][$i]) and !empty($data['metaId'][$i])) {
                        $entity = $em->getRepository('AssetsBundle:AssetsCategoryMeta')->find($data['metaId'][$i]);
                    } elseif (!empty($data['metaValue'][$i])) {
                        $entity = new AssetsCategoryMeta();
                        $entity->setCategory($category);
                    }
                    $entity->setMetaLang($data['metaLang'][$i]);
                    $entity->setMetaValue($data['metaValue'][$i]);
                    $entity->setMetaKey($data['metaKey'][$i]);
                    $em->persist($entity);
                }
                $i++;
            endforeach;
            $em->flush();

        }

    }


}
