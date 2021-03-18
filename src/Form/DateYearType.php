<?php

namespace App\Form;

use App\Entity\DateYear;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * DateYear form.
 */
class DateYearType extends AbstractType {

    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
                                    $builder->add('value', TextType::class, array(
                    'label' => 'Value',
                    'required' => true,
                    'attr' => array(
                        'help_block' => '',
                    ),
                ));                                        $builder->add('start', null, [
                    'label' => 'Start',
                    'required' => false,
                    'attr' => array(
                        'help_block' => '',
                    ),
                ]);                                        $builder->add('startCirca', ChoiceType::class, array(
                    'label' => 'Start Circa',
                    'expanded' => true,
                    'multiple' => false,
                    'choices' => array(
                        'Yes' => true,
                        'No' => false,
                        ),
                    'required' => true,
                    'attr' => array(
                        'help_block' => '',
                    ),
                ));
                                                        $builder->add('end', null, [
                    'label' => 'End',
                    'required' => false,
                    'attr' => array(
                        'help_block' => '',
                    ),
                ]);                                        $builder->add('endCirca', ChoiceType::class, array(
                    'label' => 'End Circa',
                    'expanded' => true,
                    'multiple' => false,
                    'choices' => array(
                        'Yes' => true,
                        'No' => false,
                        ),
                    'required' => true,
                    'attr' => array(
                        'help_block' => '',
                    ),
                ));
                            
            
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DateYear::class
        ));
    }

}
