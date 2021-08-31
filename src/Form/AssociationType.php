<?php

namespace App\Form;

use App\Entity\Associations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'constraints' => [new Length(['max' => 100])]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new Email()]
            ])
            ->add('address', null, [
                'constraints' => [new Length(['max' => 100])]
            ])
            ->add('zip', null, [
                'constraints' => [new Length(['max' => 10])],
                ])
            ->add('city', null, [
                'constraints' => [new Length(['max' => 100])]
                ])
            ->add('fixNumber', null, [
                'required' => false,
                'constraints' => [new Length(['max' => 10])]
                ])
            ->add('cellNumber', null, [
                'required' => false,
                'constraints' => [new Length(['max' => 10])]
                ])
            ->add('faxNumber', null, [
                'required' => false,
                'constraints' => [new Length(['max' => 10])]
                ])
            ->add('description', TextareaType::class, [
                'required' => false,
                ])
            ->add('picture', FileType::class, [
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
            ->add('webSite', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url()]])
            ->add('facebook', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url(), new Regex([
                    'pattern' => '/facebook\.com\/[a-zA-Z0-9_]*$/'])]])
            ->add('linkedin', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url(), new Regex([
                    'pattern' => '/linkedin\.com\/[a-zA-Z0-9_]*$/'])]])
            ->add('youtube', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url(), new Regex([
                    'pattern' => '/youtube\.com\/[a-zA-Z0-9_]*$/'])]])
            ->add('twitter', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200]), new Url(), new Regex([
                    'pattern' => '/twitter\.com\/[a-zA-Z0-9_]*$/'])]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Associations::class,
        ]);
    }
}
