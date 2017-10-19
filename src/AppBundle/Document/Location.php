<?php

namespace AppBundle\Document;

/**
 * Class Location
 * @package AppBundle\Document
 */
class Location
{
    /** @var  string $id */
    protected $id;

    /** @var  string $address */
    protected $address;

    /**
     * Get id
     *
     * @return string|null $id
     */
    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string $address
     */
    public function getAddress() : ?string
    {
        return $this->address;
    }
}
