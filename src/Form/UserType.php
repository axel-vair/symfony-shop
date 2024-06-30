<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'label_attr' => ['class' => 'form-label inline-block mb-2 text-gray-700'],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe actuel',
                'required' => true,
                'attr' => [
                    'class' => 'form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                    'placeholder' => 'Votre mot de passe actuel'
                ],
                'label_attr' => ['class' => 'form-label inline-block mb-2 text-gray-700'],
            ])
            ->add('Modifier', SubmitType::class, [
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
