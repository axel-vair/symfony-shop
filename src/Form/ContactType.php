<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    private const FIELD_CLASS = 'mb-3 form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none';
    private const LABEL_CLASS = 'form-label inline-block mb-2 text-gray-700';
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => self::FIELD_CLASS,
                    'placeholder' => 'Adresse email'
                ],
                'label' => 'Email',
                'label_attr' => ['class' => self::LABEL_CLASS],
                'constraints' => [
                    new NotBlank(['message' => "L'email est requis."]),
                    new Email(['message' => 'Veuillez saisir une adresse email valide.'])
                ],
                'required' => false,
            ])

            ->add('name', TextareaType::class, [
                'attr' => [
                    'class' => self::FIELD_CLASS,
                    'placeholder' => 'Nom'
                ],
                'label' => 'Nom',
                'label_attr' => ['class' => self::LABEL_CLASS],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                ],
                'required' => false,
            ])

            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => self::FIELD_CLASS,
                    'placeholder' => 'Contenu du message'
                ],
                'label' => 'Message',
                'label_attr' => ['class' => self::LABEL_CLASS],
                'constraints' => [
                    new NotBlank(['message' => 'Le message est requis.']),
                ],
                'required' => false,
            ])

            ->add('submit', SubmitType::class, [
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
