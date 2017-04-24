<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProductSub
 *
 * @ORM\Table(name="order_product_sub")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderProductSubRepository")
 */
class OrderProductSub
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="imageCategory", type="string", length=255)
     */
    private $imageCategory;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set imageCategory
     *
     * @param string $imageCategory
     *
     * @return OrderProductSub
     */
    public function setImageCategory($imageCategory)
    {
        $this->imageCategory = $imageCategory;

        return $this;
    }

    /**
     * Get imageCategory
     *
     * @return string
     */
    public function getImageCategory()
    {
        return $this->imageCategory;
    }
}

