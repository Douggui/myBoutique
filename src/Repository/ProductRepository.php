<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function myFindAll()
    {

        $queryBuilder = $this->createQueryBuilder('p');

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function myFind($id)
    {

        $queryBuilder = $this->createQueryBuilder('p')->andWhere('p.id=:id')->setParameter('id', $id);
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();
        //dd($query->getDQL());
        return $result;
    }
    //requete pour récuperer des produits a partir de prix min et max
    public function myFindPrice($min, $max)
    {

        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.price>=:min')
            ->andWhere('p.price<=:max')
            ->setParameters(['min' => $min * 100, 'max' => $max * 100])
            ->orderBy('p.price', 'ASC');
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();
        // dd($query->getDQL());
        return $result;
    }

    public function myFindCategory($cat)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->join('p.category', 'c');
        //->addSelect('c');
        // dd($cat->getString());

        if (!empty($cat->getString())) {
            $mots = explode(' ', $cat->getString());
            //dd($mots);
            $i = 0;
            foreach ($mots as $mot) {

                $queryBuilder->andWhere('p.name LIKE :mot' . $i . ' OR p.description LIKE :mot' . $i . ' OR p.subtitle LIKE :mot' . $i)
                    ->setParameter('mot' . $i, '%' . $mot . '%');
                $i++;
            }
        }
        if (count($cat->getCategories()) > 0) {
            $queryBuilder->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $cat->getCategories());
        }

        $query = $queryBuilder->getQuery();
        //dd($query->getDQL());
        $result = $query->getResult();
        return $result;
    }



    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
