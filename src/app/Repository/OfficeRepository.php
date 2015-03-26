<?php
namespace OfficeSearch\Repository;

use OfficeSearch\Entity\Office;
use Doctrine\DBAL\Connection;

class OfficeRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @param \Doctrine\DBAL\Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Find all offices in a given range (in km) of the given point
     *
     * @param float $latitude
     * @param float $longitude
     * @param array $filters
     * @param int $range
     * @return array
     */
    public function findByLocation($latitude, $longitude, $range, $filters = array())
    {
        $query = 'SELECT *,' . $this->getHaversineFormula() . 'as distance FROM offices AS o';
        $query .= $this->getFilterForHaversineFormula();

        if (in_array('isOpenInWeekends', $filters)) {
            $query .= ' AND o.is_open_in_weekends = "Y"';
        }
        if (in_array('hasSupportDesk', $filters)) {
            $query .= ' AND o.has_support_desk = "Y"';
        }

        $query .= ' HAVING distance <= :range ORDER BY distance';

        $result = $this->db->fetchAll($query, array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'range' => $range
        ));

        $offices = array();
        foreach ($result as $data) {
            $id = $data['id'];
            $offices[$id] = $this->getOffice($data);
        }

        return $offices;
    }

    /**
     * Calculate the distance in km between two points on a sphere.
     * The formula uses 2 parameters: latitude and longitude.
     *
     * @return string
     */
    protected function getHaversineFormula()
    {
        return ' 6371 * 2 * ASIN(SQRT(POWER(SIN((:latitude' .
        ' - abs(o.latitude)) * pi()/180 / 2),2) + COS(:latitude' .
        ' * pi()/180 ) * COS(abs(o.latitude) *  pi()/180) * POWER(SIN((:longitude' .
        ' - o.longitude) *  pi()/180 / 2), 2) )) ';
    }

    /**
     * Calculate the max. and min. latitude and longitude for the given range where 1° is ~ 111 km.
     * The filter uses 3 parameters: latitude, longitude and range.
     *
     * @return string
     */
    protected function getFilterForHaversineFormula()
    {
        return ' WHERE (o.latitude BETWEEN (:latitude - (:range / 111)) ' .
            'AND (:latitude + (:range / 111))) ' .
            'AND (o.longitude BETWEEN (:longitude - :range / abs(COS(radians(:latitude)) * 111)) ' .
            'AND (:longitude + :range / abs(COS(radians(:latitude)) * 111))) ';
    }

    /**
     * @param array $data
     * @return \OfficeSearch\Entity\Office
     */
    protected function getOffice($data)
    {
        $office = new Office();

        foreach ($data as $key => $value) {
            if ($key == 'distance') {
                continue;
            }
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $office->$setter($value);
        }

        return $office;
    }
}