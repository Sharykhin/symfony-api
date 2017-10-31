<?php

namespace AppBundle\Document;

/**
 * Class MaterialItem
 * @package AppBundle\Document
 */
class MaterialItem
{
    /** @var  string $id */
    protected $id;

    /** @var  string $code */
    protected $code;

    /** @var  string $description */
    protected $description;

    /** @var  string $unit */
    protected $unit;

    /** @var  float $quantity */
    protected $quantity;

    /** @var  float $unitPrice */
    protected $unitPrice;

    /** @var  float $total */
    protected $total;

    protected $location;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return string $code
     */
    public function getCode() : ?string
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * Set unit
     *
     * @param string $unit
     * @return $this
     */
    public function setUnit(string $unit)
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * Get unit
     *
     * @return string $unit
     */
    public function getUnit() : ?string
    {
        return $this->unit;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return $this
     */
    public function setQuantity(float $quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get quantity
     *
     * @return float $quantity
     */
    public function getQuantity() : ?float
    {
        return $this->quantity;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     * @return $this
     */
    public function setUnitPrice(float $unitPrice)
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float $unitPrice
     */
    public function getUnitPrice() : ?float
    {
        return $this->unitPrice;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return $this
     */
    public function setTotal(float $total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Get total
     *
     * @return float $total
     */
    public function getTotal() : ?float
    {
        return $this->total;
    }

    /**
     * Set location
     *
     * @param \AppBundle\Document\Location $location
     * @return $this
     */
    public function setLocation(\AppBundle\Document\Location $location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Get location
     *
     * @return \AppBundle\Document\Location $location
     */
    public function getLocation()
    {
        return $this->location;
    }
}
