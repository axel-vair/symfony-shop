<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    /**
     * Affiche la liste des commandes de l'utilisateur.
     *
     * Cette méthode récupère et affiche les commandes de l'utilisateur connecté de manière paginée.
     *
     * @param OrderRepository $orderRepository Le repository pour accéder aux commandes
     * @param int $page Le numéro de la page courante (par défaut 1)
     * @return Response La réponse HTTP contenant la vue des commandes
     */
    #[Route('/utilisateur/commandes', name: 'app_user_orders')]
    public function showOrders(
        OrderRepository $orderRepository,
        #[MapQueryParameter] int $page = 1
    ): Response
    {
        // Vérifie que l'utilisateur a le rôle 'ROLE_USER'
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        // Crée un QueryBuilder pour récupérer les commandes de l'utilisateur
        $queryBuilder = $orderRepository->createQueryBuilder('o')
            ->where('o.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('o.createdAt', 'DESC');

        // Configure la pagination
        $adapter = new QueryAdapter($queryBuilder);
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $page,
            3  // 3 commandes par page
        );

        // Affiche la vue des commandes
        return $this->render('pages/order/index.html.twig', [
            'orders' => $pager,
        ]);
    }

    /**
     * Affiche les détails d'une commande spécifique.
     *
     * Cette méthode affiche les détails d'une commande donnée.
     * Note : La vérification que l'utilisateur est le propriétaire de la commande n'est pas implémentée ici.
     *
     * @param Order $order La commande à afficher
     * @return Response La réponse HTTP contenant la vue des détails de la commande
     */
    #[Route('/utilisateur/commande/{reference}', name: 'app_order_details')]
    public function showOrderDetails(Order $order): Response
    {
        // Affiche la vue des détails de la commande
        return $this->render('pages/order/details.html.twig', [
            'order' => $order,
            'reference' => $order->getReference(),
        ]);
    }
}
