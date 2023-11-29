<?php

namespace Appstore\Bundle\EducationBundle\Repository;
use Appstore\Bundle\DomainUserBundle\Entity\NotificationConfig;
use Appstore\Bundle\ElectionBundle\Entity\ElectionConfig;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\ToolBundle\Entity\GlobalOption;

/**
 * NotificationConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EducationConfigRepository extends EntityRepository
{
	public function fileUploader(ElectionConfig $entity, $file = '')
	{
		$em = $this->_em;
		if(isset($file['logoFile'])){

			$img = $file['logoFile'];
			$fileName = $img->getClientOriginalName();
			$imgName =  uniqid(). '.' .$fileName;
			$img->move($entity->getUploadDir(), $imgName);
			$entity->setLogo($imgName);
		}
		$em->persist($entity);
		$em->flush();
	}
}
