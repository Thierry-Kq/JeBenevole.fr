<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 3000,
                        'maxMessage' => 'max_length',
                        'min' => 15,
                        'minMessage' => 'min_length',
                    ])
                ]
            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                    ])
                ],
                'data_class' => null])
            ->add('fixNumber', null, [
                'required' => false,
                'constraints' => [new Length([
                    'max' => 15,
                    'maxMessage' => 'max_length',
                    'min' => 4,
                    'minMessage' => 'min_length'
                ])]
            ])
            ->add('cellNumber', null, [
                'required' => false,
                'constraints' => [new Length([
                    'max' => 15,
                    'maxMessage' => 'max_length',
                    'min' => 4,
                    'minMessage' => 'min_length'
                ])]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}