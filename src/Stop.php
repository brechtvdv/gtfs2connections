<?php

/**
 * Class Stop
 */
class Stop
{
    /**
     * @var string
     */
    protected $stopId;
    /**
     * @var string
     */
    protected $stopCode = null;
    /**
     * @var string
     */
    protected $stopName;
    /**
     * @var string
     */
    protected $stopDesc = null;
    /**
     * @var float
     */
    protected $stopLat;
    /**
     * @var float
     */
    protected $stopLon;
    /**
     * @var string
     */
    protected $zoneId = null;
    /**
     * @var string
     */
    protected $stopUrl = null;
    /**
     * @var string
     */
    protected $locationType = null;
    /**
     * @var string
     */
    protected $parentStation = null;
    /**
     * @var string
     */
    protected $stopTimezone = null;
    /**
     * @var int
     */
    protected $wheelchairBoarding = null;

    /**
     * @return string
     */
    public function getStopId()
    {
        return $this->stopId;
    }

    /**
     * @param string $stopId
     */
    public function setStopId($stopId)
    {
        $this->stopId = $stopId;
    }

    /**
     * @return string
     */
    public function getStopCode()
    {
        return $this->stopCode;
    }

    /**
     * @param string $stopCode
     */
    public function setStopCode($stopCode)
    {
        $this->stopCode = $stopCode;
    }

    /**
     * @return string
     */
    public function getStopName()
    {
        return $this->stopName;
    }

    /**
     * @param string $stopName
     */
    public function setStopName($stopName)
    {
        $this->stopName = $stopName;
    }

    /**
     * @return string
     */
    public function getStopDesc()
    {
        return $this->stopDesc;
    }

    /**
     * @param string $stopDesc
     */
    public function setStopDesc($stopDesc)
    {
        $this->stopDesc = $stopDesc;
    }

    /**
     * @return float
     */
    public function getStopLat()
    {
        return $this->stopLat;
    }

    /**
     * @param float $stopLat
     */
    public function setStopLat($stopLat)
    {
        $this->stopLat = $stopLat;
    }

    /**
     * @return float
     */
    public function getStopLon()
    {
        return $this->stopLon;
    }

    /**
     * @param float $stopLon
     */
    public function setStopLon($stopLon)
    {
        $this->stopLon = $stopLon;
    }

    /**
     * @return string
     */
    public function getZoneId()
    {
        return $this->zoneId;
    }

    /**
     * @param string $zoneId
     */
    public function setZoneId($zoneId)
    {
        $this->zoneId = $zoneId;
    }

    /**
     * @return string
     */
    public function getStopUrl()
    {
        return $this->stopUrl;
    }

    /**
     * @param string $stopUrl
     */
    public function setStopUrl($stopUrl)
    {
        $this->stopUrl = $stopUrl;
    }

    /**
     * @return string
     */
    public function getLocationType()
    {
        return $this->locationType;
    }

    /**
     * @param string $locationType
     */
    public function setLocationType($locationType)
    {
        $this->locationType = $locationType;
    }

    /**
     * @return string
     */
    public function getParentStation()
    {
        return $this->parentStation;
    }

    /**
     * @param string $parentStation
     */
    public function setParentStation($parentStation)
    {
        $this->parentStation = $parentStation;
    }

    /**
     * @return string
     */
    public function getStopTimezone()
    {
        return $this->stopTimezone;
    }

    /**
     * @param string $stopTimezone
     */
    public function setStopTimezone($stopTimezone)
    {
        $this->stopTimezone = $stopTimezone;
    }

    /**
     * @return int
     */
    public function getWheelchairBoarding()
    {
        return $this->wheelchairBoarding;
    }

    /**
     * @param int $wheelchairBoarding
     */
    public function setWheelchairBoarding($wheelchairBoarding)
    {
        $this->wheelchairBoarding = $wheelchairBoarding;
    }
}