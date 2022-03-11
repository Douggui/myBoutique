<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Services\Cart;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
    /**
     * @Route("/commande/erreur/{checkoutSessionId}", name="order_cancel")
     */
    public function index(Order $order, $checkoutSessionId): Response
    {

        if (!$order || $order->getUser() != $this->getUser()) return $this->redirectToRoute('home');
        Stripe::setApiKey($this->getParameter('stripe_key'));

        $session = Session::retrieve($checkoutSessionId);
        return $this->render('order/cancel.html.twig', [
            'order' => $order
        ]);
    }
}
