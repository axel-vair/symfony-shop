<?php


namespace App\Tests\Repository;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactRepositoryTest extends KernelTestCase
{
    private ContactRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get(ContactRepository::class);
    }

    public function testAddAndFindContact(): void
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        // Création d'un contact à tester
        $contact = new Contact();
        $contact->setEmail('email@email.com');
        $contact->setName('Nom');
        $contact->setMessage('Message');

        $entityManager->persist($contact);
        $entityManager->flush();

        // Tester la méthode findBy...
        $contacts = $this->repository->findBy(['name' => 'Nom']);
        $this->assertNotEmpty($contacts, "La recherche doit retourner au moins un contact.");
        $this->assertEquals('Nom', $contacts[0]->getName());
        $this->assertNotNull($contacts[0]->getId(), "Le contact doit avoir un ID attribué.");

        $entityManager->remove($contact);
        $entityManager->flush();

    }

}
