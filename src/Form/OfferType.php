<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Offers;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['max' => 255])
                ]
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['max' => 255])
                ]
            ])
            ->add('zip', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['max' => 10])
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['max' => 255])
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('c')
                        ->where('c.isActived = 1');
                },
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une catégorie (optionnel)',
                'required' => false
            ])
            ->add('isPublished', CheckboxType::class, [
                'label'    => 'Voulez vous publier cette offre?',
                'required' => false
            ])
            ->add('isUrgent', CheckboxType::class, [
                'label'    => 'Cette offre est urgente?',
                'required' => false
            ])
            ->add('description', TextareaType::class, [])
            ->add('dateStart', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('dateEnd', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            "image/png",
                            "image/jpeg",
                            "image/jpg",
                            "image/gif"
                        ],
                        'mimeTypesMessage' => 'Image non valide. Problème de format ou de taille.',
                    ])
                ],
                'data_class' => null])
            ->add('contactExternalName', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length(['max' => 255]),
                ]
            ])
            ->add('contactExternalEmail', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length(['max' => 255])
                ]
            ])
            ->add('contactExternalTel', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length(['max' => 255])
                ]
            ])
//            ->add('longitude')
//            ->add('status')
//            ->add('latitude')
//            ->add('recommended')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offers::class,
        ]);
    }
}
