<?php

namespace App\Tests\Repository;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactRepositoryTest extends KernelTestCase
{
    private ContactRepository $repository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var ContactRepository $repository */
        $repository = static::getContainer()->get(ContactRepository::class);
        $this->repository = $repository;

        /** @var EntityManagerInterface $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager = $entityManager;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testAddAndFindContact(): void
    {
        $contact = new Contact();
        $contact->setEmail('email@email.com');
        $contact->setName('Nom');
        $contact->setMessage('Message');

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        $contacts = $this->repository->findBy(['name' => 'Nom']);
        $this->assertNotEmpty($contacts, "La recherche doit retourner au moins un contact.");
        $this->assertEquals('Nom', $contacts[0]->getName());
        $this->assertNotNull($contacts[0]->getId(), "Le contact doit avoir un ID attribué.");
        $this->entityManager->remove($contact);
        $this->entityManager->flush();
    }
}
