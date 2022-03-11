<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use App\Entity\SearchForm;
use App\Form\SearchFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/produits/{page}", name="product")
     */
    public function index(ProductRepository $repo, Request $request, PaginatorInterface $paginator, $page = 1): Response
    {

        //$repo = $this->getDoctrine()->getRepository(Product::class);
        // $product = $repo->find(5);
        //findByX(x est le nom d'un champ)
        //$product = $repo->findByName('fugit voluptatem sed');
        //$product = $repo->findBy(['category' => 78], ['price' => 'desc'], 2, 0);
        //$product = $repo->findAll();
        //dump($product);


        $search = new SearchForm();

        $form = $this->createForm(SearchFormType::class, $search);

        $form->handleRequest($request); //on recupère la requete

        if ($form->isSubmitted() && $form->isValid()) {
            //dump(($search->getCategories()));



            //$products = $repo->findBy(['category' => $idCategory]);
            $donnees = $repo->myFindCategory($search);
            $products = $paginator->paginate(
                $donnees, //données qu'on lui donne pour paginer
                $page, //page sur laquel on se trouve
                2 //nombre d'elements dans la page
            );
            if (count($donnees) == 0) {
                $error = "aucun produits ne correspond à votre recherche";
            } else {
                $error = null;
            }
        } else {
            $donnees = $repo->myFindAll();


            $products = $paginator->paginate(
                $donnees, //données qu'on lui donne pour paginer
                $page, //page sur laquel on se trouve
                2 //nombre d'elements dans la page
            );
            $error = null;
        }
        //dd($repo->myFindPrice(20, 190));
        //dd(($products));

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'error' => $error,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/produits-show/{slug}", name="show_product")
     */
    public function show(Product $product): Response
    {
        //$product = $repo->findOneBySlug('velit-odio-accusantium');
        //dd($product);
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/compte/mes-commandes/{slug}/commentaire", name="comment_product")
     */
    public function comment(Product $product, Request $request, EntityManagerInterface $manager): Response
    {



        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request); //on recupère la requete

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setProduct($product);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTime());
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'succes',
                'le commentaire pour le produit ' . $product->getName() . ' a bien été poster'
            );

            return $this->redirectToRoute('show_product', ['slug' => $product->getSlug()]);
        }
        return $this->render('product/comment.html.twig', [
            'form' => $form->createView(),
            'user' => $this->getUser(),
            'product' => $product

        ]);
    }
}
