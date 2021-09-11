<?php

namespace App\Form;

use App\Entity\Offers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('address')
            ->add('zip')
            ->add('city')
//            ->add('longitude')
//            ->add('latitude')
//            ->add('isPublished')
//            ->add('isActived')
//            ->add('isUrgent')
            ->add('description')
//            ->add('status')
//            ->add('dateStart')
//            ->add('dateEnd')
//            ->add('file')
//            ->add('recommended')
//            ->add('contactExternalName')
//            ->add('contactExternalEmail')
//            ->add('contactExternalTel')
//            ->add('isDeleted')
//            ->add('createdAt')
//            ->add('updatedAt')
//            ->add('slug')
//            ->add('users')
//            ->add('associations')
//            ->add('categories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offers::class,
        ]);
    }
}
