<?php

namespace App\Form;

use App\Entity\Associations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'constraints' => [new Length(['max' => 100])]
            ])
            ->add('email', EmailType::class, [

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
                'required' => false])
            ->add('picture', FileType::class, [
                'label' => 'Picture',
                'required' => false,
                'data_class' => null])
            ->add('webSite', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200])]])
            ->add('facebook', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200])]
                ])
            ->add('linkedin', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200])]])
            ->add('youtube', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200])]
                ])
            ->add('twitter', UrlType::class, [
                'required' => false,
                'constraints' => [new Length(['max' => 200])]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Associations::class,
        ]);
    }
}
