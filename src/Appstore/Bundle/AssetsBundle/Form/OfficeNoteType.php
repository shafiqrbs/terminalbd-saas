<?php

namespace Appstore\Bundle\AssetsBundle\Form;

use Appstore\Bundle\InventoryBundle\Entity\InventoryConfig;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OfficeNoteType extends AbstractType
{



	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder

		    ->add('meetingNoteNo',TextType::class, array('attr'=>array('class'=>'m-wrap span12','placeholder'=>'Add  serial no')))
		    ->add('name',TextType::class, array('attr'=>array('class'=>'m-wrap span12','rows' => 5,'placeholder'=>'Enter disposal narration')))
		    ->add('content',TextareaType::class, array('attr'=>array('class'=>'m-wrap span12 editor','rows' => 8,'placeholder'=>'Enter disposal narration')))
		    ->add('requestedBy',TextType::class, array('attr'=>array('class'=>'m-wrap span12','rows' => 5,'placeholder'=>'Enter disposal narration')))
		    ->add('amount',TextType::class, array('attr'=>array('class'=>'m-wrap span12','rows' => 5,'placeholder'=>'Enter disposal narration')))
		    ->add('meetingDate', DateType::class, array(
			    'widget' => 'single_text',
			    'attr' => array('class'=>'m-wrap span12'),
			    'view_timezone' => 'Asia/Dhaka'))

		    ->add('process', 'choice', array(
			    'required'    => true,
			    'attr'=>array('class'=>'span12 m-wrap'),
			    'choices' => array(
				    'Draft' => 'Draft',
				    'In-progress' => 'In-progress',
				    'Hold' => 'Hold',
			    ),
		    ))
            ->add('noteMode', 'choice', array(
                'required'    => true,
                'attr'=>array('class'=>'span12 m-wrap'),
                'choices' => array(
                    'InHouse-Issue' => 'InHouse-Issue',
                    'Club-Issue' => 'Club-Issue',
                    'Local-Purchase' => 'Local-Purchase',
                    'International-Purchase' => 'International-Purchase'
                ),
            ))
            ->add('file','file')
	    ;

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\AssetsBundle\Entity\OfficeNote'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'officeNote';
    }


}
