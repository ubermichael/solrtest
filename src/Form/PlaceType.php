<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Place form.
 */
class PlaceType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('sortableName', TextType::class, [
            'label' => 'Sortable Name',
            'required' => true,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('geoNamesId', TextType::class, [
            'label' => 'Geonames Id',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('regionName', TextType::class, [
            'label' => 'Region Name',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('countryName', TextType::class, [
            'label' => 'Country Name',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('latitude', null, [
            'label' => 'Latitude',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('longitude', null, [
            'label' => 'Longitude',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('notes', TextareaType::class, [
            'label' => 'Notes',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
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
            'data_class' => Place::class,
        ]);
    }
}
