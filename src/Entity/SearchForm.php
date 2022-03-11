<?php

namespace App\Entity;

use App\Repository\SearchRepository;
use Doctrine\ORM\Mapping as ORM;


class SearchForm
{

    private $id;

    private $categories;
    private $string;


    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of string
     */ 
    public function getString()
    {
        return $this->string;
    }

    /**
     * Set the value of string
     *
     * @return  self
     */ 
    public function setString($string)
    {
        $this->string = $string;

        return $this;
    }
}
