<?php

/**
 * Class Frequency
 */
class Frequency
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $tripId;
    /**
     * @var \DateTime
     */
    protected $startTime;
    /**
     * @var \DateTime
     */
    protected $endTime;
    /**
     * @var int
     */
    protected $headwaySecs;
    /**
     * @var int
     */
    protected $exactTimes = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param DateTime $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param DateTime $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return int
     */
    public function getHeadwaySecs()
    {
        return $this->headwaySecs;
    }

    /**
     * @param int $headwaySecs
     */
    public function setHeadwaySecs($headwaySecs)
    {
        $this->headwaySecs = $headwaySecs;
    }

    /**
     * @return int
     */
    public function getExactTimes()
    {
        return $this->exactTimes;
    }

    /**
     * @param int $exactTimes
     */
    public function setExactTimes($exactTimes)
    {
        $this->exactTimes = $exactTimes;
    }
}