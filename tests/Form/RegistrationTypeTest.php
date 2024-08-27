<?php

namespace App\Tests\Form;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;

class RegistrationTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new ConstraintValidatorFactory([EmailValidator::class => new EmailValidator(Email::VALIDATION_MODE_HTML5)]))
            ->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@example.com',
            'plainPassword' => 'password123',
        ];

        $model = new User();
        $form = $this->factory->create(RegistrationFormType::class, $model);

        $expected = new User();
        $expected->setEmail('test@example.com');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected->getEmail(), $model->getEmail());
        $this->assertEquals('password123', $form->get('plainPassword')->getData());
    }

    public function testInvalidData()
    {
        $formData = [
            'email' => 'email', // Invalid email
            'plainPassword' => 'short', // Invalid password
        ];

        $form = $this->factory->create(RegistrationFormType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->getErrors(true);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }
        $this->assertCount(2, $errorMessages);
        $this->assertContains('Cette valeur n\'est pas une adresse email valide.', $errorMessages);
        $this->assertContains('Votre mot de passe doit contenir au moins 6 caractères', $errorMessages);
    }
}
