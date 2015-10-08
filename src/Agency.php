<?php

/**
 * Class Agency
 */
class Agency
{
    /**
     * @var string
     */
    protected $agencyId = null;
    /**
     * @var string
     */
    protected $agencyName;
    /**
     * @var string
     */
    protected $agencyUrl;
    /**
     * @var string
     */
    protected $agencyTimezone;
    /**
     * @var string
     */
    protected $agencyLang = null;
    /**
     * @var string
     */
    protected $agencyPhone = null;
    /**
     * @var string
     */
    protected $agencyFareUrl = null;

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
    public function getAgencyName()
    {
        return $this->agencyName;
    }

    /**
     * @param string $agencyName
     */
    public function setAgencyName($agencyName)
    {
        $this->agencyName = $agencyName;
    }

    /**
     * @return string
     */
    public function getAgencyUrl()
    {
        return $this->agencyUrl;
    }

    /**
     * @param string $agencyUrl
     */
    public function setAgencyUrl($agencyUrl)
    {
        $this->agencyUrl = $agencyUrl;
    }

    /**
     * @return string
     */
    public function getAgencyTimezone()
    {
        return $this->agencyTimezone;
    }

    /**
     * @param string $agencyTimezone
     */
    public function setAgencyTimezone($agencyTimezone)
    {
        $this->agencyTimezone = $agencyTimezone;
    }

    /**
     * @return string
     */
    public function getAgencyLang()
    {
        return $this->agencyLang;
    }

    /**
     * @param string $agencyLang
     */
    public function setAgencyLang($agencyLang)
    {
        $this->agencyLang = $agencyLang;
    }

    /**
     * @return string
     */
    public function getAgencyPhone()
    {
        return $this->agencyPhone;
    }

    /**
     * @param string $agencyPhone
     */
    public function setAgencyPhone($agencyPhone)
    {
        $this->agencyPhone = $agencyPhone;
    }

    /**
     * @return string
     */
    public function getAgencyFareUrl()
    {
        return $this->agencyFareUrl;
    }

    /**
     * @param string $agencyFareUrl
     */
    public function setAgencyFareUrl($agencyFareUrl)
    {
        $this->agencyFareUrl = $agencyFareUrl;
    }
}