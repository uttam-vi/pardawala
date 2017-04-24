<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;
    
    /**
     * @var Job
     * 
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="user")
     */
    private $orderProduct;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add orderProduct
     *
     * @param \AppBundle\Entity\OrderProduct $orderProduct
     *
     * @return User
     */
    public function addOrderProduct(\AppBundle\Entity\OrderProduct $orderProduct)
    {
        $this->orderProduct[] = $orderProduct;

        return $this;
    }

    /**
     * Remove orderProduct
     *
     * @param \AppBundle\Entity\OrderProduct $orderProduct
     */
    public function removeOrderProduct(\AppBundle\Entity\OrderProduct $orderProduct)
    {
        $this->orderProduct->removeElement($orderProduct);
    }

    /**
     * Get orderProduct
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderProduct()
    {
        return $this->orderProduct;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
