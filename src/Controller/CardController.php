<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Services\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardController extends AbstractController
{
    /**
     * @Route("/panier", name="card")
     */
    public function index(Cart $cart, ProductRepository $repo): Response
    {
        //$cart->remove_cart();

        $cart = $cart->get_cart();
        $productsCart = [];

        foreach ($cart as $id => $quantity) {

            $productsCart[] = [
                'product' => $repo->findOneById($id),
                'quantity' => $quantity

            ];
            //dd($produitsCart['produit']);
        }


        return $this->render('card/index.html.twig', [

            'productsCart' => $productsCart
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add($id, Cart $cart): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('card');
    }
    /**
     * @Route("/cart/removeProduct/{id}", name="decrease_product")
     */
    public function decrease($id, Cart $cart): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('card');
    }
    /**
     * @Route("/cart/delete/{id}", name="delete_product")
     */
    public function removeProduct($id, Cart $cart): Response
    {
        $cart->remove_product($id);
        return $this->redirectToRoute('card');
    }
    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function remove(Cart $cart): Response
    {
        $cart->remove_cart();
        return $this->redirectToRoute('card');
    }
}
