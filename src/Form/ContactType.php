<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if ($this->token->getToken() == null) {
            $builder
                ->add('firstName', TextType::class, [
                    'label' => 'contact_form_first_name',
                    'constraints' => [
                        new NotBlank(['message' => 'required_field'])
                    ]
                ])
                ->add('lastName', TextType::class, [
                    'label' => 'contact_form_last_name',
                    'constraints' => [
                        new NotBlank(['message' => 'required_field'])
                    ]
                ])
                ->add('email', EmailType::class, [
                    'label' => 'contact_form_email',
                    'constraints' => [
                        new NotBlank(['message' => 'required_field']),
                        new Email()
                    ],
                    'attr' => [
                        'type' => 'email'
                    ]
                ])
                ->add('phone', TelType::class, [
                    'label' => 'contact_form_phone',
                    'attr' => [
                        'type' => 'tel'
                    ],
                    'required' => false
                ]);
        }
        $builder
            ->add('subject', TextType::class, [
                'label' => 'contact_form_subject',
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'max_length',
                        'min' => 2,
                        'minMessage' => 'min_length'
                    ])
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'contact_form_message',
                'constraints' => [
                    new NotBlank(['message' => 'required_field']),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'max_length',
                        'min' => 2,
                        'minMessage' => 'min_length'
                    ])
                ]
            ]);
    }
}
