<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    /**
     * @Route("/compte/mes-commandes", name="account_order")
     */
    public function index(OrderRepository $repo): Response
    {
        //dd($this->getUser()->getOrders());

        return $this->render('/account/account_order.html.twig', [
            'orders' => $repo->findPaidOrder($this->getUser())
        ]);
    }
    /**
     * @Route("/compte/mes-commandes/{reference}", name="account_order_show")
     */
    public function show(Order $order): Response
    {
        //dd($this->getUser()->getOrders());
        if($order->getUser()!=$this->getUser()){
          return  $this->redirectToRoute('account_order');
        }

        return $this->render('/account/show.html.twig', [
            'order'=>$order
        ]);
    }
}
