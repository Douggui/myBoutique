<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Order;
use App\Services\Cart;
use App\Entity\Address;
use App\Entity\Product;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Doctrine\ORM\Mapping\Id;
use PhpParser\Node\Expr\Empty_;
use App\Repository\AddressRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/commande", name="order")
     */
    public function index(Request $request, Cart $cart, ProductRepository $repo, EntityManagerInterface $manager): Response
    {

        if (count($this->getUser()->getAddresses()) == 0) {
            return $this->redirectToRoute('account_address-add');
        }

        //récupération des produit pour le recap de la commande
        $cart = $cart->get_cart();
        $productsCart = [];

        foreach ($cart as $id => $quantity) {

            $productsCart[] = [
                'product' => $repo->findOneById($id),
                'quantity' => $quantity

            ];
            //dd($produitsCart['produit']);
        }




        // dd($address);

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request); //on recupère la requete

        if ($form->isSubmitted() && $form->isValid()) {

            //dd($form->get('carrier')->getData());

            $order = new Order;
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setCarrier($form->get('carrier')->getData());
            $order->setDelivery($form->get('addresses')->getData());
            $order->setStatut(0);
            $date = new \DateTime();
            $date = $date->format('dmY');
            $order->setReference($date . '-' . uniqid());




            foreach ($productsCart as $product) {

                $orderDetails = new OrderDetails;
                $orderDetails->setMyorder($order);
                $orderDetails->setProduct($product['product']);
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());

                $products_for_stripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $product['product']->getName(),
                            'images' => [$_SERVER['HTTP_ORIGIN'] . '/uploads/' . $product['product']->getIllustration()],

                        ],
                        'unit_amount' => $product['product']->getPrice()

                    ],
                    'quantity' => $product['quantity'],

                ];

                $manager->persist($orderDetails);
            }

            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' =>  $orderDetails->getMyorder()->getCarrier()->getName(),


                    ],
                    'unit_amount' =>  $orderDetails->getMyorder()->getCarrier()->getPrice()

                ],
                'quantity' => 1,

            ];
            //dd($products_for_stripe);

            // This is your test secret API key.
            Stripe::setApiKey($this->getParameter('stripe_key'));



            $YOUR_DOMAIN = 'http://localhost:8000';

            $checkout_session = \Stripe\Checkout\Session::create([
                'customer_email' => $this->getUser()->getEmail(),
                'line_items' => $products_for_stripe,
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/commande/success/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);
            $order->setCheckoutSessionId($checkout_session->id);
            $manager->persist($order);
            $manager->flush();
            dump($checkout_session);
            return $this->render('order/recap.html.twig', [
                'stripeUrl' => $checkout_session->url,
                'productsCart' => $productsCart,
                'order' => $order
            ]);
        }


        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'productsCart' => $productsCart
        ]);
    }
}
