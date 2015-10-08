<?php

/**
 * Class CalendarDate
 */
class CalendarDate
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $serviceId;
    /**
     * @var \DateTime
     */
    protected $date;
    /**
     * @var int
     */
    protected $exceptionType;

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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getExceptionType()
    {
        return $this->exceptionType;
    }

    /**
     * @param int $exceptionType
     */
    public function setExceptionType($exceptionType)
    {
        $this->exceptionType = $exceptionType;
    }
}