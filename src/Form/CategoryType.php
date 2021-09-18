<?php

namespace App\Form;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'max_length',
                        'min' => 15,
                        'minMessage' => 'min_length',
                    ])
                ],
                'label' =>  'Categorie'
            ])
            ->add('isActived', CheckboxType::class, [
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'max_length',
                        'min' => 15,
                        'minMessage' => 'min_length',
                    ])
                ],
            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024',
                    ])
                ],
                'data_class' => null,
                'label' => 'Image_categorie'
            ])
            ->add('color', ColorType::class, [
                'html5' => true,
            ])
            ->add('isDeleted', CheckboxType::class, [
                'required' => false,
            ])
            ->add('parent', EntityType::class, [
                'class' => Categories::class,
                'query_builder' => function (CategoriesRepository $repo) {
                    return $repo->createQueryBuilder('c')
                    ->where('c.isDeleted = 0');
                },
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une catégorie parent (optionnel)',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => 'name',
                    'message' => 'unique'])
              ]
        ]);
    }
}
