<?php

namespace App\Form;

use App\Entity\Users;
use App\Validator\CustomEmail;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Email(),
                    new CustomEmail()
                ]
            ])
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'max_length',
                        'min' => 2,
                        'minMessage' => 'min_length'
                    ])
                ]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'max_length',
                        'min' => 2,
                        'minMessage' => 'min_length'
                    ])
                ]
            ])
            ->add('nickname', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 50,
                        'maxMessage' => 'max_length',
                        'min' => 5,
                        'minMessage' => 'min_length'
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'password_empty',
                    ]),
                    new Length([
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    // 12 char, at least 1 uppercase and 1 lowercase, 1 number and 1 special char
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/',
                        'message' => 'password_unsecure',
                    ]),
                ],
                'type' => PasswordType::class,
                'required' => true,
                'first_options'  => ['label' => 'plain_password'],
                'second_options' => ['label' => 'repeated_plain_password'],
            ])
            ->add('rgpd', CheckboxType::class, [
                'constraints' => [
                    new IsTrue([
                        'message' => 'L\'acceptation des RGPD est obligatoire !',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => 'nickname',
                    'message' => 'unique'],
                )
            ]
        ]);
    }
}
