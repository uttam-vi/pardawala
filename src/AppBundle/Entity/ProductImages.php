<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductImages
 *
 * @ORM\Table(name="product_images")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductImagesRepository")
 */
class ProductImages
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
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\Image()
     */
    private $image;
    
    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="ImageCategory", inversedBy="productImages")
     * @ORM\JoinColumn(name="img_cat_id", referencedColumnName="id", nullable=false)
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
     * Set image
     *
     * @param string $image
     *
     * @return ProductImages
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
     * Set imageCategory
     *
     * @param \AppBundle\Entity\ImageCategory $imageCategory
     *
     * @return ProductImages
     */
    public function setImageCategory(\AppBundle\Entity\ImageCategory $imageCategory)
    {
        $this->imageCategory = $imageCategory;

        return $this;
    }

    /**
     * Get imageCategory
     *
     * @return \AppBundle\Entity\ImageCategory
     */
    public function getImageCategory()
    {
        return $this->imageCategory;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ProductImages
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
}
