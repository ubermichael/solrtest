<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\DateYear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * DateYear form.
 */
class DateYearType extends AbstractType
{
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('value', TextType::class, [
            'label' => 'Value',
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('start', null, [
            'label' => 'Start',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('startCirca', ChoiceType::class, [
            'label' => 'Start Circa',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('end', null, [
            'label' => 'End',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('endCirca', ChoiceType::class, [
            'label' => 'End Circa',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => DateYear::class,
        ]);
    }
}
