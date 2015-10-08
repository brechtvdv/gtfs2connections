<?php

/**
 * Class Route
 */
class Route
{
    /**
     * @var string
     */
    protected $routeId;
    /**
     * @var string
     */
    protected $agencyId = null;
    /**
     * @var string
     */
    protected $routeShortName;
    /**
     * @var string
     */
    protected $routeLongName;
    /**
     * @var string
     */
    protected $routeDesc = null;
    /**
     * @var int
     */
    protected $routeType;
    /**
     * @var string
     */
    protected $routeUrl = null;
    /**
     * @var string
     */
    protected $routeColor = null;
    /**
     * @var string
     */
    protected $routeTextColor = null;

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
    public function getAgencyId()
    {
        return $this->agencyId;
    }

    /**
     * @param string $agencyId
     */
    public function setAgencyId($agencyId)
    {
        $this->agencyId = $agencyId;
    }

    /**
     * @return string
     */
    public function getRouteShortName()
    {
        return $this->routeShortName;
    }

    /**
     * @param string $routeShortName
     */
    public function setRouteShortName($routeShortName)
    {
        $this->routeShortName = $routeShortName;
    }

    /**
     * @return string
     */
    public function getRouteLongName()
    {
        return $this->routeLongName;
    }

    /**
     * @param string $routeLongName
     */
    public function setRouteLongName($routeLongName)
    {
        $this->routeLongName = $routeLongName;
    }

    /**
     * @return string
     */
    public function getRouteDesc()
    {
        return $this->routeDesc;
    }

    /**
     * @param string $routeDesc
     */
    public function setRouteDesc($routeDesc)
    {
        $this->routeDesc = $routeDesc;
    }

    /**
     * @return int
     */
    public function getRouteType()
    {
        return $this->routeType;
    }

    /**
     * @param int $routeType
     */
    public function setRouteType($routeType)
    {
        $this->routeType = $routeType;
    }

    /**
     * @return string
     */
    public function getRouteUrl()
    {
        return $this->routeUrl;
    }

    /**
     * @param string $routeUrl
     */
    public function setRouteUrl($routeUrl)
    {
        $this->routeUrl = $routeUrl;
    }

    /**
     * @return string
     */
    public function getRouteColor()
    {
        return $this->routeColor;
    }

    /**
     * @param string $routeColor
     */
    public function setRouteColor($routeColor)
    {
        $this->routeColor = $routeColor;
    }

    /**
     * @return string
     */
    public function getRouteTextColor()
    {
        return $this->routeTextColor;
    }

    /**
     * @param string $routeTextColor
     */
    public function setRouteTextColor($routeTextColor)
    {
        $this->routeTextColor = $routeTextColor;
    }


}