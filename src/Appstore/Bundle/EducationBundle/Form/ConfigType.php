<?php

namespace Appstore\Bundle\EducationBundle\Form;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class ConfigType extends AbstractType
{

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('file')
            ->add('smsNotification')
            ->add('removeImage')
	        ->add('address','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs address','rows'=> 4,'autocomplete'=>'off','placeholder'=>'Enter address')
	        ))
	        ->add('type', 'entity', array(
		        'required'      => false,
		        'expanded'      =>true,
		        'multiple'      =>true,
		        'class' => 'Appstore\Bundle\EducationBundle\Entity\EducationParticularType',
		        'property' => 'name',
		        'attr'=>array('class'=>'span12 m-wrap'),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1")
			                  ->orderBy("e.name","ASC");
		        }
	        ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\EducationBundle\Entity\EducationConfig'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'config';
    }



}
