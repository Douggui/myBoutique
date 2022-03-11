<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Repository\ProductRepository;
use App\Services\Cart;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    /**
     * @Route("/commande/success/{checkoutSessionId}", name="order_success")
     */
    public function index(Order $order, Request $request, ProductRepository $repo, Cart $cart, EntityManagerInterface $manager, $checkoutSessionId): Response
    {
        //on récupére la session pour vérifier le paiment 
        Stripe::setApiKey($this->getParameter('stripe_key'));

        $session = Session::retrieve($checkoutSessionId);

        if ($session->payment_status !== "paid") return $this->redirectToRoute('order_cancel', ['checkoutSessionId' => $checkoutSessionId]);

        //dump($session->payment_status);
        //si il y a de commande ou la commande n'est pas de l'utilisateur en cours on redirige vers la page d'accueil 
        if (!$order || $order->getUser() != $this->getUser()) return $this->redirectToRoute('home');
        //on vide le panier

        //on met le statut de paiment à 1 dans la bdd 
        $order->setStatut(1);
        //on enregistre la bdd
        $manager->flush();

        $cart = $cart->get_cart();
        $productsCart = [];

        foreach ($cart as $id => $quantity) {

            $productsCart[] = [
                'product' => $repo->findOneById($id),
                'quantity' => $quantity

            ];
            //dd($produitsCart['produit']);
        }

        return $this->render('order/success.html.twig', [
            'order' => $order,
            'productsCart' => $productsCart
        ]);

        $cart->remove_cart();
    }
}
