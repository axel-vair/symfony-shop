<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                    'placeholder' => 'Votre adresse email'
                ],
                'label_attr' => [
                    'class' => 'form-label inline-block mb-2 text-gray-700'
                ],
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                    'placeholder' => 'Prénom'
                ],
                'label' => 'Prénom',
                'label_attr' => ['class' => 'form-label inline-block mb-2 text-gray-700'],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 30,
                        'maxMessage' => 'Votre mot de passe doit contenir moins de {{ limit }} caractères',

                    ])
                ],
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                    'placeholder' => 'Nom'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label inline-block mb-2 text-gray-700'
                ],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 30,
                        'maxMessage' => 'Votre mot de passe doit contenir moins de {{ limit }} caractères',

                    ])
                ],
                'required' => false,
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'class' => 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                        'placeholder' => 'Mot de passe',
                    ],
                    'label_attr' => ['class' => 'form-label inline-block mb-2 text-gray-700'],
                ],
                'second_options' => [
                    'label' => 'Vérification du mot de passe',
                    'attr' => [
                        'class' => 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                        'placeholder' => 'Tapez à nouveau le mot de passe',
                    ],
                    'label_attr' => ['class' => 'form-label inline-block mb-2 text-gray-700'],
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'required' => true,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Modifier les informations',
                'attr' => ['class' => 'd-grid btn btn-dark mt-4 w-100'],
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
