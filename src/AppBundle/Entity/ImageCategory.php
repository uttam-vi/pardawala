<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ImageCategory
 *
 * @ORM\Table(name="image_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageCategoryRepository")
 */
class ImageCategory
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="sort_order", type="integer")
     */
    private $sortOrder;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\Image()
     */
    private $image;
    
    /**
     * @var productImages
     * 
     * @ORM\OneToMany(targetEntity="ProductImages", mappedBy="imageCategory")
     */
    private $productImages;
    
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
     * Set name
     *
     * @param string $name
     *
     * @return ImageCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productImages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * 
     * @return type
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Add productImage
     *
     * @param \AppBundle\Entity\ProductImages $productImage
     *
     * @return ImageCategory
     */
    public function addProductImage(\AppBundle\Entity\ProductImages $productImage)
    {
        $this->productImages[] = $productImage;

        return $this;
    }

    /**
     * Remove productImage
     *
     * @param \AppBundle\Entity\ProductImages $productImage
     */
    public function removeProductImage(\AppBundle\Entity\ProductImages $productImage)
    {
        $this->productImages->removeElement($productImage);
    }

    /**
     * Get productImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductImages()
    {
        return $this->productImages;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return ImageCategory
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return ImageCategory
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
}
