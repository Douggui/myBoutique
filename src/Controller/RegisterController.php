<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\ServiceMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController

{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {


        $this->passwordHasher = $passwordHasher;
    }
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, EntityManagerInterface $manager, ServiceMail $email): Response
    {

        $user = new User();

        $form = $this->createForm(UserType::class, $user);




        $form->handleRequest($request); //on recupère la requete

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setActive(0);
            $manager->persist($user);  //on persiste les données dans le temps
            $manager->flush();  //on ecrit en base de données

            $token = sha1($user->getEmail());

            $contentMail = 'Merci de votre inscription sur le site myBoutique,pour activer votre compte  veuillez cliquez sur le lien suivant<br><a href="http://' . $_SERVER['HTTP_HOST'] . '/inscription/' . $user->getEmail() . '/' . $token . '">  href="http://"' . $_SERVER['HTTP_HOST'] . '/inscription/' . $user->getEmail() . '/' . $token . '"  </a>';

            $email->sendMail($contentMail, $user->getEmail(), $user->getFullName(), 'Inscription sur le site MyBoutique');
    

            mail($user->getFullName(), 'Inscription sur le site MyBoutique', $contentMail);


            $this->addFlash(
                'success',
                'le compte  ' . $user->getFirstName() . '  ' . $user->getLastName() . '   a été crée et un email d\'activation a été envoyé!'
            );

            return $this->redirectToRoute('home');
            dump($user);
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()

        ]);
    }
    /**
     * @Route("/inscription/{email}/{token}", name="activation_compte")
     */
    public function activation_compte(EntityManagerInterface $manager, User $user, $token): Response
    {
        //dd($user);
        $token_verif = sha1($user->getEmail());
        if ($token == $token_verif) {
            $user->setActive(1);
            $manager->flush();
            $this->addFlash(
                'succes',
                'le compte est activé avec succée'
            );
            return $this->RedirectToRoute('app_login');
        } else {
            $this->addFlash(
                'danger',
                'le lien d\'activation est incorrecte'
            );
        }
        return $this->RedirectToRoute('home');
    }
}
