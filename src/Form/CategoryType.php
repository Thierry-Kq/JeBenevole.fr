<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['max' => 100])                   
                ]
            ])
            ->add('isActived', CheckboxType::class, [
                'required' => false,
            ])
            ->add('description', TextareaType::class)
            ->add('picture', FileType::class, [
                'required' => false,
                'constraints' => [
                    new Image()
                ],
                'data_class' => null
            ])
            ->add('color', ColorType::class, [
                'html5' => true,
            ])
            ->add('isDeleted', CheckboxType::class, [
                'required' => false,
            ])
            ->add('parent', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
