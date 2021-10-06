<?php

namespace App\Form;

use App\Entity\Associations;
use App\Validator\isFromYoutube;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length(['max' => 100,
                    'maxMessage' => 'max_length',
                    'min' => 2,
                    'minMessage' => 'min_length'
                    ])                   
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Email()
                ]
            ])
            ->add('address', null, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length(['max' => 100,
                    'maxMessage' => 'max_length',
                    'min' => 2,
                    'minMessage' => 'min_length'
                    ])
                    
                ]
            ])
            ->add('zip', null, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length(['max' => 10,
                    'maxMessage' => 'max_length',
                    'min' => 5,
                    'minMessage' => 'min_length'])
                ]
            ])
            ->add('city', null, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length(['max' => 100,
                    'maxMessage' => 'max_length',
                    'min' => 2,
                    'minMessage' => 'min_length'
                    ])
                ]
            ])
            ->add('fixNumber', null, [
                'required' => false,
                'constraints' => [new Length(['max' => 20,
                'maxMessage' => 'max_length',
                'min' => 4,
                'minMessage' => 'min_length'
                ])]
            ])
            ->add('cellNumber', null, [
                'required' => false,
                'constraints' => [new Length(['max' => 20,
                'maxMessage' => 'max_length',
                'min' => 4,
                'minMessage' => 'min_length'
                ])]
            ])
            ->add('faxNumber', null, [
                'required' => false,
                'constraints' => [new Length(['max' => 20,
                'maxMessage' => 'max_length',
                'min' => 4,
                'minMessage' => 'min_length'
                ])]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                ])
            ->add('picture', FileType::class, [
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                    ])
                ],
                'data_class' => null])
            ->add('webSite', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url()]])
            ->add('facebook', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url(), new Regex([
                    'pattern' => '/^(https?:\/\/)?(www\.)?([A-Za-z]{2}-[A-Za-z]{2}.)?facebook\.com\/[a-zA-Z0-9_]*$/',
                    'message' => 'LinkPattern_notValid'])]])
            ->add('linkedin', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url(), new Regex([
                    'pattern' => '/^(https?:\/\/)?(www\.)?linkedin\.com\/[a-zA-Z0-9_]*$/',
                    'message' => 'LinkPattern_notValid'])]])
            ->add('youtube', UrlType::class, [
                'required' => false,
                'constraints' => [new isFromYoutube()]])
            ->add('twitter', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url(), new Regex([
                    'pattern' => '/^(https?:\/\/)?(www\.)?twitter\.com\/[a-zA-Z0-9_]*$/',
                    'message' => 'LinkPattern_notValid'])]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Associations::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => 'name',
                    'message' => 'unique'])
              ]
        ]);
    }
}
