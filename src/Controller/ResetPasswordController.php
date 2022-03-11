<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResetPasswordRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reinitialisation-mot-passe", name="reset_password")
     */
    public function index(Request $request, UserRepository $repo, EntityManagerInterface $manager): Response
    {

        dump($request->get('email'));


        if ($request->get('email')) {
            $user = $repo->findOneByEmail($request->get('email'));
            //dd($user);

            if ($user) {
                $resetPassword = new ResetPassword();

                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setDate(new DateTime());
                $manager->persist($resetPassword);
                $manager->flush();

                $token = $resetPassword->getToken();
                $contentMail = 'Pour réinitialiser votre mot de passe cliquez sur le lien suivant<br><a href="http://' . $_SERVER['HTTP_HOST'] . '/modifier-mot-passe/' . $token . '">  href="http://"' . $_SERVER['HTTP_HOST'] . '/modifier-mot-passe/'  . $token . '"  </a>';

                //$email->sendMail($contentMail, $user->getEmail(), $user->getFullName(), 'Inscription sur le site MyBoutique');

                mail($user->getEmail(), 'Rénitialisation mdp', $contentMail);

                $this->addFlash(
                    'success',
                    'un email vous a été envoyé pour rénitialiser le mot de passe'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'le email saisie est inconnu'
                );
            }
        }

        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'RersetPasswordController',
        ]);
    }

    /**
     * @Route("/modifier-mot-passe/{token}", name="update_password")
     */
    public function update(Request $request,  EntityManagerInterface $manager, $token, ResetPasswordRepository $repo, ResetPassword $reset, UserPasswordHasherInterface $passwordHasher): Response
    {

        //dd($token);
        $resetPasword = $repo->findOneByToken($token);
        if (!$resetPasword) {
            $this->addFlash(
                'danger',
                'lien d\'initialisation est incorrecte'
            );
            return $this->redirectToRoute('app_login');
        }

        $date_create = $resetPasword->getDate();

        $now = new DateTime();

        if ($now > $date_create->modify('+3 hour')) {

            $this->addFlash(
                'danger',
                'La demande de modification de mdp a éxpiré'
            );

            return $this->redirectToRoute('reset_password');
        }

        //dd($reset->getUser()->getEmail());
        $user = $resetPasword->getUser();
        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($passwordHasher->hashPassword($user, $user->getNewPassword()));
            $manager->flush();
            $this->addFlash(
                'succes',
                'le mot de passe  a bien été reinitialisé'
            );
            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/updateMDP.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
