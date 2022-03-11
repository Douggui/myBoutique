<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    /**
     * @Route("/compte/adresses", name="account_address")
     */
    public function index(): Response
    {


        return $this->render('account/address.html.twig', []);
    }


    /**
     * @Route("/compte/ajouter-une-adresse", name="account_address-add")
     */
    public function add(Request $request, EntityManagerInterface $manager, Cart $cart): Response
    {

        $address = new Address;
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request); //on recupère la requete

        if ($form->isSubmitted() && $form->isValid()) {

            //dd($address);
            $user = $this->getUser();
            $address->setUser($user);
            $manager->persist($address);  //on persiste les données dans le temps
            $manager->flush();  //on ecrit en base de données

            $this->addFlash(
                'success',
                'l\'adresse  ' . $address->getName() . '   a été crée!'
            );
            //on regarde si il y a qlq chose ds $cart avec $cart=$cart->get_cart 
            if (count($cart = $cart->get_cart()) > 0) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_address');
            }
        }

        return $this->render('account/address_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_address_edit")
     */
    public function edit(Request $request, EntityManagerInterface $manager, Address $address): Response
    {




        if ($address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);




        $form->handleRequest($request); //on recupère la requete

        if ($form->isSubmitted() && $form->isValid()) {

            //dd($address);



            $manager->flush();  //on ecrit en base de données

            $this->addFlash(
                'success',
                'l\'adresse  a bien été modifier   '
            );
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_address_delete")
     */
    public function delete(EntityManagerInterface $manager, Address $address): Response
    {

        if ($address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $manager->remove($address);
        $manager->flush();

        $this->addFlash(
            'success',
            'l\'adresse a bien été supprimée'
        );
        return $this->redirectToRoute('account_address');

        return $this->render('account/address.html.twig', []);
    }
}
