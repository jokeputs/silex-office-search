<?php
namespace OfficeSearch\Entity;

class Office
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var bool
     */
    protected  $isOpenInWeekends;

    /**
     * @var bool
     */
    protected $hasSupportDesk;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsOpenInWeekends()
    {
        return $this->isOpenInWeekends;
    }

    /**
     * @param bool|string $isOpenInWeekends
     * @return $this
     */
    public function setIsOpenInWeekends($isOpenInWeekends)
    {
        if (is_string($isOpenInWeekends)) {
            if ($isOpenInWeekends == 'Y') {
                $this->isOpenInWeekends = true;
            }
            elseif ($isOpenInWeekends == 'N') {
                $this->isOpenInWeekends = false;
            }
        }
        else {
            $this->isOpenInWeekends = $isOpenInWeekends;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getHasSupportDesk()
    {
        return $this->hasSupportDesk;
    }

    /**
     * @param bool|string $hasSupportDesk
     * @return $this
     */
    public function setHasSupportDesk($hasSupportDesk)
    {
        if (is_string($hasSupportDesk)) {
            if ($hasSupportDesk == 'Y') {
                $this->hasSupportDesk = true;
            }
            elseif ($hasSupportDesk == 'N') {
                $this->hasSupportDesk = false;
            }
        }
        else {
            $this->hasSupportDesk = $hasSupportDesk;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getStreet() . ', ' . $this->getCity();
    }
}