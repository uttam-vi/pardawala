<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderProductDetail
 *
 * @ORM\Table(name="order_product_detail")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderProductDetailRepository")
 */
class OrderProductDetail
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
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="ImageCategory")
     * @ORM\JoinColumn(name="image_category_id", referencedColumnName="id", nullable=false)
     */
    private $imageCategory;
    
    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="ProductImages")
     * @ORM\JoinColumn(name="product_images_id", referencedColumnName="id", nullable=false)
     */
    private $productImages;
    
    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="OrderProduct", inversedBy="orderProductDetail")
     * @ORM\JoinColumn(name="order_product_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $orderProduct;


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
     * @param \AppBundle\Entity\ImageCategory $imageCategory
     *
     * @return OrderProductDetail
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
     * Set productImages
     *
     * @param \AppBundle\Entity\ProductImages $productImages
     *
     * @return OrderProductDetail
     */
    public function setProductImages(\AppBundle\Entity\ProductImages $productImages)
    {
        $this->productImages = $productImages;

        return $this;
    }

    /**
     * Get productImages
     *
     * @return \AppBundle\Entity\ProductImages
     */
    public function getProductImages()
    {
        return $this->productImages;
    }

    /**
     * Set orderProduct
     *
     * @param \AppBundle\Entity\OrderProduct $orderProduct
     *
     * @return OrderProductDetail
     */
    public function setOrderProduct(\AppBundle\Entity\OrderProduct $orderProduct)
    {
        $this->orderProduct = $orderProduct;

        return $this;
    }

    /**
     * Get orderProduct
     *
     * @return \AppBundle\Entity\OrderProduct
     */
    public function getOrderProduct()
    {
        return $this->orderProduct;
    }
}
