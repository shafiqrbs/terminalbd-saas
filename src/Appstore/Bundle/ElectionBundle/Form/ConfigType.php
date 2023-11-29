<?php

namespace Appstore\Bundle\ElectionBundle\Form;
use Doctrine\ORM\EntityRepository;
use Setting\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class ConfigType extends AbstractType
{


	/** @var  LocationRepository */

	private $location;

	function __construct(LocationRepository $location)
	{
		$this->location       = $location;
	}

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
	        ->add('cardText','textarea', array('attr'=>array('class'=>'m-wrap span12 resize inputs','rows'=> 4,'autocomplete'=>'off','placeholder'=>'Enter ID card text')
	        ))
	        ->add('candidateName','text', array('attr'=>array('class'=>'m-wrap span12 resize inputs','autocomplete'=>'off','placeholder'=>'Enter candidate name')
	        ))
	        ->add('setup', 'entity', array(
		        'required'    => true,
		        'class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionSetup',
		        'empty_value' => '--- Choose a election setup ---',
		        'property' => 'electionName',
		        'attr'=>array('class'=>'m-wrap span12 inputs'),
		        'query_builder' => function(EntityRepository $er){
			        return $er->createQueryBuilder('e')
			                  ->where("e.status = 1");
		        },
	        ))

	        ->add('district', 'entity', array(
		        'required'    => true,
		        'empty_value' => '---Select District---',
		        'attr'=>array('class'=>'category m-wrap span12 select2'),
		        'class' => 'Setting\Bundle\LocationBundle\Entity\Location',
		        'property' => 'nestedLabel',
		        'choices'=> $this->locationChoiceList()
	        ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appstore\Bundle\ElectionBundle\Entity\ElectionConfig'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'config';
    }

	/**
	 * @return mixed
	 */
	protected function locationChoiceList()
	{
		return $categoryTree = $this->location->getDistrictOptionGroup();

	}





}
