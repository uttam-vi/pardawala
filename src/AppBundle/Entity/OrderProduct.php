<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OrderProduct
 *
 * @ORM\Table(name="order_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderProductRepository")
 */
class OrderProduct
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
     * @ORM\Column(name="details", type="json_array", length=255, nullable=true)
     */
    private $details;

    /**
     * @var string
     *
     * @ORM\Column(name="discription", type="string", length=255)
     */
    private $discription;

    /**
     * @var string
     *
     * @ORM\Column(name="originImage", type="string", length=255)
     * @Assert\Image()
     */
    private $originImage;

    /**
     * @var string
     *
     * @ORM\Column(name="finalImage", type="string", length=255)
     * @Assert\Image()
     */
    private $finalImage;

    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orderProduct")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * Set details
     *
     * @param string $details
     *
     * @return OrderProduct
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set discription
     *
     * @param string $discription
     *
     * @return OrderProduct
     */
    public function setDiscription($discription)
    {
        $this->discription = $discription;

        return $this;
    }

    /**
     * Get discription
     *
     * @return string
     */
    public function getDiscription()
    {
        return $this->discription;
    }

    /**
     * Set originImage
     *
     * @param string $originImage
     *
     * @return OrderProduct
     */
    public function setOriginImage($originImage)
    {
        $this->originImage = $originImage;

        return $this;
    }

    /**
     * Get originImage
     *
     * @return string
     */
    public function getOriginImage()
    {
        return $this->originImage;
    }

    /**
     * Set finalImage
     *
     * @param string $finalImage
     *
     * @return OrderProduct
     */
    public function setFinalImage($finalImage)
    {
        $this->finalImage = $finalImage;

        return $this;
    }

    /**
     * Get finalImage
     *
     * @return string
     */
    public function getFinalImage()
    {
        return $this->finalImage;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return OrderProduct
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
