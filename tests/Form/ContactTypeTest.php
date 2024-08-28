<?php
// tests/Form/ContactTypeTest.php
namespace App\Tests\Form;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;



class ContactTypeTest extends TypeTestCase
{

    private ValidatorInterface $validator;
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
        // On prépare les données à soumettre
        $formData = [
            'email' => 'test@example.com',
            'name' => 'John Doe',
            'message' => 'This is a test message.',
        ];

        // On crée une nouvelle instance de Contact
        $contact = new Contact();

        // On crée le formulaire à partir de ContactType
        $form = $this->factory->create(ContactType::class, $contact);

        // On soumet les données au formulaire
        $form->submit($formData);

        // On vérifie que le formulaire est valide
        $this->assertTrue($form->isSynchronized());

        // On vérifie que les données du formulaire sont bien mappées à l'entité
        $this->assertEquals('test@example.com', $contact->getEmail());
        $this->assertEquals('John Doe', $contact->getName());
        $this->assertEquals('This is a test message.', $contact->getMessage());

        // Vérification des données non modifiées
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }



    public function testSubmitInvalidData()
    {
        $formData = [
            'email' => 'coucou',
            'name' => '',
            'message' => '',
        ];

        // On crée une nouvelle instance de Contact
        $contact = new Contact();

        // On crée le formulaire à partir de ContactType
        $form = $this->factory->create(ContactType::class, $contact);

        // On soumet les données au formulaire
        $form->submit($formData);

        // On vérifie que le formulaire est valide
        $this->assertTrue($form->isSynchronized());
        $errors = $form->getErrors(true);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();

        }

    $this->assertContains('Veuillez saisir une adresse email valide.', $errorMessages);
    $this->assertContains('Le nom ne peut pas être vide.', $errorMessages);
    $this->assertContains('Le message ne peut pas être vide.', $errorMessages);

    }
    
}
