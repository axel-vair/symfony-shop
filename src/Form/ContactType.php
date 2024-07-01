<?php

namespace App\Form;

use App\Entity\Contact;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
  /*  public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [])
            ->add('name')
            ->add('message', TextareaType::class,[])
            ->add('envoyer',SubmitType::class,)
        ;
    }*/

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                    'placeholder' => 'Adresse email'
                ],
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label inline-block mb-2 text-gray-700'],
            ])

            ->add('name', TextareaType::class, [
                'attr' => [
                    'class' => 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                    'placeholder' => 'Nom'
                ],
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label inline-block mb-2 text-gray-700'],
            ])

            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none',
                    'placeholder' => 'Contenu du message'
                ],
                'label' => 'Message',
                'label_attr' => ['class' => 'form-label inline-block mb-2 text-gray-700'],
            ])

            ->add('envoyer', SubmitType::class, [
                'label' => 'Envoyer le message',
                'attr' => ['class' => 'd-grid btn btn-dark mt-4 w-100'],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
