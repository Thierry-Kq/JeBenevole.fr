<?php

namespace App\Form;

use App\Entity\Associations;
use App\Entity\Categories;
use App\Entity\Offers;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class OfferType extends AbstractType
{
    private $requestStack;
    private $security;

    public function __construct(RequestStack $requestStack, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'max_length',
                        'min' => 30,
                        'minMessage' => 'min_length'
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'max_length',
                        'min' => 15,
                        'minMessage' => 'min_length'
                    ])
                ]
            ])
            ->add('zip', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 10,
                        'maxMessage' => 'max_length'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'max_length'
                    ])
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
            ->add('description', TextareaType::class)
            ->add('dateStart', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('dateEnd', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024',
                    ])
                ],
                'data_class' => null
            ])
            ->add('contactExternalName', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'max_length'
                    ])
                ]
            ])
            ->add('contactExternalEmail', EmailType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'max_length',
                        'min' => 6,
                        'minMessage' => 'min_length'
                    ]),
                    new Email()
                ]
            ])
            ->add('contactExternalTel', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'max_length',
                        'min' => 5,
                        'minMessage' => 'min_length'
                    ])
                ]
            ]);
        if (in_array($this->requestStack->getCurrentRequest()->get('_route'), ['new_offer', 'edit_offer']) ) {
            $builder
                ->add('associations', EntityType::class, [
                    'class' => Associations::class,
                    'query_builder' => function (EntityRepository $entityRepository){
                        return $entityRepository->findAllByUserId($this->security->getUser()->getId());
                    },
                    'choice_label' => 'name',
                    'placeholder' => 'Choisissez une association'
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offers::class,
            'constraints' => [
              new UniqueEntity([
                  'fields' => 'title',
                  'message' => 'unique'])
            ]
        ]);
    }
}
