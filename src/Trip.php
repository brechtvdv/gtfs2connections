<?php

/**
 * Class Trip
 */
class Trip
{
    /**
     * @var string
     */
    protected $routeId;
    /**
     * @var string
     */
    protected $serviceId;
    /**
     * @var string
     */
    protected $tripId;
    /**
     * @var string
     */
    protected $tripHeadSign = null;
    /**
     * @var string
     */
    protected $tripShortName = null;
    /**
     * @var string
     */
    protected $directionId = null;
    /**
     * @var string
     */
    protected $blockId = null;
    /**
     * @var string
     */
    protected $shapeId = null;
    /**
     * @var int
     */
    protected $wheelchairAccessible = null;
    /**
     * @var int
     */
    protected $bikesAllowed = null;

    /**
     * @return string
     */
    public function getRouteId()
    {
        return $this->routeId;
    }

    /**
     * @param string $routeId
     */
    public function setRouteId($routeId)
    {
        $this->routeId = $routeId;
    }

    /**
     * @return string
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * @param string $serviceId
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    }

    /**
     * @return string
     */
    public function getTripId()
    {
        return $this->tripId;
    }

    /**
     * @param string $tripId
     */
    public function setTripId($tripId)
    {
        $this->tripId = $tripId;
    }

    /**
     * @return string
     */
    public function getTripHeadSign()
    {
        return $this->tripHeadSign;
    }

    /**
     * @param string $tripHeadSign
     */
    public function setTripHeadSign($tripHeadSign)
    {
        $this->tripHeadSign = $tripHeadSign;
    }

    /**
     * @return string
     */
    public function getTripShortName()
    {
        return $this->tripShortName;
    }

    /**
     * @param string $tripShortName
     */
    public function setTripShortName($tripShortName)
    {
        $this->tripShortName = $tripShortName;
    }

    /**
     * @return string
     */
    public function getDirectionId()
    {
        return $this->directionId;
    }

    /**
     * @param string $directionId
     */
    public function setDirectionId($directionId)
    {
        $this->directionId = $directionId;
    }

    /**
     * @return string
     */
    public function getBlockId()
    {
        return $this->blockId;
    }

    /**
     * @param string $blockId
     */
    public function setBlockId($blockId)
    {
        $this->blockId = $blockId;
    }

    /**
     * @return string
     */
    public function getShapeId()
    {
        return $this->shapeId;
    }

    /**
     * @param string $shapeId
     */
    public function setShapeId($shapeId)
    {
        $this->shapeId = $shapeId;
    }

    /**
     * @return int
     */
    public function getWheelchairAccessible()
    {
        return $this->wheelchairAccessible;
    }

    /**
     * @param int $wheelchairAccessible
     */
    public function setWheelchairAccessible($wheelchairAccessible)
    {
        $this->wheelchairAccessible = $wheelchairAccessible;
    }

    /**
     * @return int
     */
    public function getBikesAllowed()
    {
        return $this->bikesAllowed;
    }

    /**
     * @param int $bikesAllowed
     */
    public function setBikesAllowed($bikesAllowed)
    {
        $this->bikesAllowed = $bikesAllowed;
    }
}