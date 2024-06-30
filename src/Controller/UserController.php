<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'app_profil', methods: ['GET', 'POST'])]
    public function edit(
        User $user,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce profil.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();

            if (!$hasher->isPasswordValid($user, $plainPassword)) {
                $this->addFlash('error', 'Mot de passe incorrect. Votre profil n\'a pas été modifié.');
                return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
            }

            $manager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié.');
            return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
        }

        return $this->render('pages/user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/commandes', name: 'app_user_orders')]
    public function showOrders(
        OrderRepository $orderRepository,
        #[MapQueryParameter] int $page = 1
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $queryBuilder = $orderRepository->createQueryBuilder('o')
            ->where('o.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('o.createdAt', 'DESC');

        $adapter = new QueryAdapter($queryBuilder);
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $page,
            3
        );

        return $this->render('pages/user/orders.html.twig', [
            'orders' => $pager,
        ]);
    }

    #[Route('/utilisateur/commande/{id}', name: 'app_order_details')]
    public function showOrderDetails(Order $order): Response
    {
        // Vérifiez que l'utilisateur actuel est bien le propriétaire de la commande

        return $this->render('pages/user/order_details.html.twig', [
            'order' => $order,
        ]);
    }
}
