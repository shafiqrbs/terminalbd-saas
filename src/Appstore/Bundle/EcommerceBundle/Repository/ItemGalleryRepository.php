<?php

namespace Appstore\Bundle\EcommerceBundle\Repository;

use Appstore\Bundle\EcommerceBundle\Entity\Item;
use Appstore\Bundle\EcommerceBundle\Entity\ItemGallery;
use Doctrine\ORM\EntityRepository;

/**
 * ProductGalleryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemGalleryRepository extends EntityRepository
{
    public function insertProductGallery(Item $reEntity,$data)
    {

        $em = $this->_em;
        $i=0;

        foreach ($data as $key => $value) {
            $entity = new ItemGallery();
            if(strpos($key,'tmpname')){
                $imageName = nl2br(htmlentities(stripslashes($value)));
                $entity->setPath($imageName);
                $entity->setItem($reEntity);
                $em->persist($entity);

            }
            $i++;

        }
        $em->flush();

        if(!empty($data['imageId'])){
            $this->removeImage($data['imageId']);
        }
    }

    public function removeImage($posts)
    {
        $em = $this->_em;
        foreach ($posts as $post ){
            $entity = $em->getRepository('EcommerceBundle:ItemGallery')->find($post);
            $entity->removeUpload($entity->getItem()->getEcommerceConfig()->getGlobalOption()->getId(),$entity->getItem()->getId());
            $em->remove($entity);
        }
        $em->flush();


    }

}
