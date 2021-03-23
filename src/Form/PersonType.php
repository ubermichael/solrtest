<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\DateYear;
use App\Entity\Person;
use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Person form.
 */
class PersonType extends AbstractType
{
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('fullName', TextType::class, [
            'label' => 'Full Name',
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
        $builder->add('gender', TextType::class, [
            'label' => 'Gender',
            'required' => false,
            'attr' => [
                'help_block' => '',
            ],
        ]);
        $builder->add('canadian', ChoiceType::class, [
            'label' => 'Canadian',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
                'Unknown' => null, ],
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
        $builder->add('urlLinks', CollectionType::class, [
            'label' => 'Url Links',
            'required' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => TextType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => false,
            'attr' => [
                'class' => 'collection collection-simple',
                'help_block' => '',
            ],
        ]);

        $builder->add('birthDate', Select2EntityType::class, [
            'label' => 'DateYear',
            'class' => DateYear::class,
            'remote_route' => 'date_year_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'date_year_new_popup',
                'add_label' => 'Add DateYear',
            ],
        ]);

        $builder->add('birthPlace', Select2EntityType::class, [
            'label' => 'Place',
            'class' => Place::class,
            'remote_route' => 'place_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'place_new_popup',
                'add_label' => 'Add Place',
            ],
        ]);

        $builder->add('deathDate', Select2EntityType::class, [
            'label' => 'DateYear',
            'class' => DateYear::class,
            'remote_route' => 'date_year_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'date_year_new_popup',
                'add_label' => 'Add DateYear',
            ],
        ]);

        $builder->add('deathPlace', Select2EntityType::class, [
            'label' => 'Place',
            'class' => Place::class,
            'remote_route' => 'place_typeahead',
            'allow_clear' => true,
            'attr' => [
                'help_block' => '',
                'add_path' => 'place_new_popup',
                'add_label' => 'Add Place',
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
            'data_class' => Person::class,
        ]);
    }
}
