<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Mailjet\Client;

use Mailjet\Resources;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $repo): Response
    {
        $product = $repo->findByIsBest(1);
        //dd($product);




        //dd($session->get('card', []));


        return $this->render('home/index.html.twig', [
            'products' => $repo->findByIsBest(1),
        ]);
    }
}
