<?php

namespace App\Form;

use App\Entity\Associations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('address')
            ->add('zip')
            ->add('city')
            ->add('fixNumber')
            ->add('cellNumber')
            ->add('faxNumber')
            ->add('description')
            ->add('picture')
            ->add('webSite')
            ->add('facebook')
            ->add('linkedin')
            ->add('youtube')
            ->add('twitter')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Associations::class,
        ]);
    }
}
