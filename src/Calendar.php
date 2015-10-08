<?php

/**
 * Class Calendar
 */
class Calendar
{
    /**
     * @var string
     */
    protected $serviceId;
    /**
     * @var int
     */
    protected $monday;
    /**
     * @var int
     */
    protected $tuesday;
    /**
     * @var int
     */
    protected $wednesday;
    /**
     * @var int
     */
    protected $thursday;
    /**
     * @var int
     */
    protected $friday;
    /**
     * @var int
     */
    protected $saturday;
    /**
     * @var int
     */
    protected $sunday;
    /**
     * @var \DateTime
     */
    protected $startDate;
    /**
     * @var \DateTime
     */
    protected $endDate;

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
     * @return int
     */
    public function getMonday()
    {
        return $this->monday;
    }

    /**
     * @param int $monday
     */
    public function setMonday($monday)
    {
        $this->monday = $monday;
    }

    /**
     * @return int
     */
    public function getTuesday()
    {
        return $this->tuesday;
    }

    /**
     * @param int $tuesday
     */
    public function setTuesday($tuesday)
    {
        $this->tuesday = $tuesday;
    }

    /**
     * @return int
     */
    public function getWednesday()
    {
        return $this->wednesday;
    }

    /**
     * @param int $wednesday
     */
    public function setWednesday($wednesday)
    {
        $this->wednesday = $wednesday;
    }

    /**
     * @return int
     */
    public function getThursday()
    {
        return $this->thursday;
    }

    /**
     * @param int $thursday
     */
    public function setThursday($thursday)
    {
        $this->thursday = $thursday;
    }

    /**
     * @return int
     */
    public function getFriday()
    {
        return $this->friday;
    }

    /**
     * @param int $friday
     */
    public function setFriday($friday)
    {
        $this->friday = $friday;
    }

    /**
     * @return int
     */
    public function getSaturday()
    {
        return $this->saturday;
    }

    /**
     * @param int $saturday
     */
    public function setSaturday($saturday)
    {
        $this->saturday = $saturday;
    }

    /**
     * @return int
     */
    public function getSunday()
    {
        return $this->sunday;
    }

    /**
     * @param int $sunday
     */
    public function setSunday($sunday)
    {
        $this->sunday = $sunday;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }
}