<?php

namespace App\Controller;

use App\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/compte/modification-mot-de-passe", name="account_password")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(UpdatePasswordType::class, $user);




        $form->handleRequest($request); //on recupère la requete

        if ($form->isSubmitted() && $form->isValid()) {
            dump($user);
            if (!$passwordHasher->isPasswordValid($user, $user->getOldPassword())) {
                $this->addFlash(
                    'danger',
                    "l'ancien mot de passe est incorrecte"
                );
            } else {
                $user->setPassword($passwordHasher->hashPassword($user, $user->getNewPassword()));
                $manager->persist($user);  //on persiste les données dans le temps
                $manager->flush();  //on ecrit en base de données

                $this->addFlash(
                    'success',
                    'le mot de passe a été mis a jour'
                );
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
