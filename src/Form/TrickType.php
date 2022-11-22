<?php

namespace App\Form;

use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextareaType::class)
            ->add('groupe_figure', ChoiceType::class, [
                'choices' => [
                    'Grabs' => 1,
                    'Rotations' => 2,
                    'Flips' => 3
                ]
            ])
            ->add('photoFigures', CollectionType::class, [
                'entry_type' => PhotoFigureType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true
            ])
            ->add('videoFigures', CollectionType::class, [
                'entry_type' => VideoFigureType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true
            ])
            ->add('save', SubmitType::class)
            // ->add('date_creation')
            // ->add('date_modification')
            // ->add('id_utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
