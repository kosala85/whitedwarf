<?php

namespace Api\Data\Repositories;

use Api\Core\Abstracts\RepositoryAbstract;
use Api\Data\Models\Booking;
use Api\Data\Models\BookingArchive;
use Api\Data\Models\Passenger;
use Api\Data\Models\Company;
use Api\Data\Models\People;
use Api\Data\Models\DriverRequest;
use Api\Data\Models\Transaction;
use Api\Data\Models\ModifiedFare;

class BookingRepository extends RepositoryAbstract
{

// _______________________________________________________________________________________________ common repo functions


    /**
     * Select Booking(s) based on different search criteria.
     *
     * @param $arrWhere
     * @param $arrOrder
     * @param $arrLimit
     * @param $arrColumns
     * @return mixed
     */
	public function selectBooking($arrWhere = [], $arrOrder = [], $arrLimit = [], $arrColumns = [])
	{
        return $this->db->select(Booking::TABLE, $arrWhere, $arrOrder, $arrLimit, $arrColumns);
	}

    /**
     * Get count of Bookings based on different criteria.
     *
     * @param $arrWhere
     * @return mixed
     */
	public function countBooking($arrWhere = [])
    {
        return $this->db->count(Booking::TABLE, $arrWhere);
    }


    /**
     * Create a new Booking.
     *
     * @param $arrRecord
     * @return mixed
     * @throws \Exception
     */
    public function insertBooking($arrRecord)
    {
        $this->db->transBegin();

        try
        {
            $result = $this->db->insert(Booking::TABLE, $arrRecord);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }

    }


    /**
     * Update Booking(s) based on different criteria.
     *
     * @param $arrSet
     * @param $arrWhere
     * @return mixed
     * @throws \Exception
     */
    public function updateBooking($arrSet, $arrWhere)
    {
        $this->db->transBegin();

        try
        {
            $result = $this->db->update(Booking::TABLE, $arrSet, $arrWhere);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }


    /**
     * Delete Booking(s) based on different criteria.
     *
     * @param $arrWhere
     * @return mixed
     * @throws \Exception
     */
    public function deleteBooking($arrWhere)
    {
        $this->db->transBegin();

        try
        {
            $result = $this->db->delete(Booking::TABLE, $arrWhere);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }


// _____________________________________________________________________________________________ specific repo functions


    public function selectBookingsByFilters($arrFilters)
    {
        $columns = "";
        $table = "";
        $joins = "";
        $where = "";
        $limit = "";
        $order = "";

        $arrMappings = [
            'passenger_phone' => 'passenger_phone',
            'booked_from' => 'booked_from',
            'company_id' => 'company_id',
            'trip_status' => null,
            'booked_by' => null,
            'pickup_date' => null,
        ];

        $arrValues = [];

        $arrFilters = $this->rebuildFilter($arrFilters, $arrMappings);

        // static filters
        $arrStaticFilters = $arrFilters['static'];

        $intTripStatus = isset($arrStaticFilters['trip_status']) ? (int)$arrStaticFilters['trip_status'] : null;
        $intBookedBy = isset($arrStaticFilters['booked_by']) ? (int)$arrStaticFilters['booked_by'] : null;
        $dtePickupDate = isset($arrStaticFilters['pickup_date']) ? $arrStaticFilters['pickup_date'] : null;


        // select table based on trip status
        $table = Booking::TABLE . ' PL';

        if(in_array($intTripStatus, [Booking::TRIP_STATUS_COMPLETE,
            Booking::TRIP_STATUS_PASSENGER_CANCEL,
            Booking::TRIP_STATUS_DISPATCHER_CANCEL,
            Booking::TRIP_STATUS_EXPIRED]))
        {
            $table = BookingArchive::TABLE;
        }


        // return columns
        $columns = "
            /* trip details */
            PL.passengers_log_id AS trip_id,
            PL.travel_status,
            PL.bookby AS booked_by,
            PL.no_passengers,
            PL.dispatch_time,
            PL.pickup_time,
            DR.createdate AS request_time,
            PL.driver_reply,
            PL.notes_driver,
            DR.total_drivers,
            PL.approx_distance,
            T.distance,

            /* locations */
            PL.current_location,
            PL.pickup_latitude,
            PL.pickup_longitude,
            PL.drop_location,
            PL.drop_latitude,
            PL.drop_longitude,

            /* passenger */
            PG.id AS passenger_id,
            PG.name AS passenger_name,
            PG.phone AS passenger_phone,

            /* driver */
            PL.driver_id,
            PE.name AS driver_name,
            PE.reachable_mobile AS driver_phone,
            PE.reachable_mobile AS driver_reachable_phone,

            /* taxi */
            PL.taxi_id,
            PL.taxi_modelid,

            /* promo */
            PL.promocode,

            /* company */
            PL.company_id,
            C.company_name,

            /* costing */
            PL.approx_fare,
            T.fare,

            /* flags */
            MF.booking_from
        ";


        // joins
        $joins = "LEFT JOIN " . Passenger::TABLE . " PG ON PG.id = PL.passengers_id
                LEFT JOIN " . Company::TABLE . " C ON C.cid = PL.company_id
                LEFT JOIN " . People::TABLE . " PE ON PE.id = PL.driver_id
                LEFT JOIN " . DriverRequest::TABLE . " DR ON DR.trip_id = PL.passengers_log_id
                LEFT JOIN " . Transaction::TABLE . " T ON T.passengers_log_id = PL.passengers_log_id
                LEFT JOIN " . ModifiedFare::TABLE . " MF ON MF.trip_id = PL.passengers_log_id
         ";


        // constraints
        $where = "WHERE";

        // apply unique filters ___
        // get only trips with pickup date not exceeding 15 days from now
        $where .= " PL.pickup_date < TIMESTAMPADD(DAY, 15, CURDATE())";

        // omit out road pickup passengers
        $where .= " AND PL.passengers_id != " . Booking::PASSENGER_ROAD_PICKUP;


        // apply conditional filters ___
        // filter by 'Add Booking' or 'Pre Booking' conditions
        if(!empty($intBookedBy))
        {
            // 'Add Bookings' done by dispatcher and 'Flat Rate Pre Bookings' done by passenger
            if($intBookedBy === Booking::BOOK_BY_OPERATOR)
            {
                $where .= " AND (PL.bookby = " . Booking::BOOK_BY_OPERATOR .
                                 " AND PL.now_after != " . Booking::BOOK_LATER . ") 
                            OR (PL.booking_from = " . Booking::BOOK_FROM_DEVICE .
                                " AND MF.booking_from = " . ModifiedFare::BOOK_FROM_PASSENGER .
                                " AND PL.now_after = " . Booking::BOOK_LATER . ")";
            }

            // 'Pre Bookings' done by passenger excluding 'Flat Rate Pre Bookings'
            if($intBookedBy === Booking::BOOK_BY_PASSENGER)
            {
                $where .= " AND PL.now_after = " . Booking::BOOK_LATER . " AND MF.trip_id IS NULL";
            }
        }

        // filter by trip status
        if(!is_null($intTripStatus))
        {
            $now = new \DateTime();
            $now = $now->setTimezone(new \DateTimeZone('Asia/Colombo'));
            $now = $now->format('Y-m-d H:i:s');

            if($intTripStatus === Booking::TRIP_STATUS_EXPIRED) // expired trips
            {
                $where .= " AND PL.pickup_time < TIMESTAMPADD(MINUTE, -120, '$now')" .
                            " AND PL.travel_status IN (" . Booking::TRIP_STATUS_NOT_COMPLETE . ", "
                                                         . Booking::TRIP_STATUS_MISSED . ", "
                                                         . Booking::TRIP_STATUS_DISPATCHED . ")";
            }
            elseif(in_array($intTripStatus, [Booking::TRIP_STATUS_NOT_COMPLETE, Booking::TRIP_STATUS_MISSED, Booking::TRIP_STATUS_DISPATCHED])) // not complete or missed trips still not expired, waiting for reply (dispatched) trips
            {
                $where .= " AND PL.pickup_time > TIMESTAMPADD(MINUTE, -120, '$now')" .
                            " AND PL.travel_status IN (" . Booking::TRIP_STATUS_NOT_COMPLETE . ", "
                                                         . Booking::TRIP_STATUS_MISSED . ", "
                                                         . Booking::TRIP_STATUS_DISPATCHED . ")";
            }
            else // all other trips
            {
                $where .= " AND PL.travel_status = $intTripStatus";
            }

            // apply ordering based on the trip status
            if($intTripStatus === Booking::TRIP_STATUS_COMPLETE)
            {
                $order = " ORDER BY PL.drop_time DESC";
            }
            else
            {
                $order = " ORDER BY PL.pickup_time ASC";
            }
        }
        else
        {
            // set ordering when trip status is not sent
            $order = " ORDER BY PL.pickup_time ASC";
        }

        // filter by pickup date
        if(!is_null($dtePickupDate))
        {
            $date = new \DateTime($dtePickupDate);
            $date = $date->setTimezone(new \DateTimeZone('Asia/Colombo'));
            $date = $date->format('Y-m-d');

            $where .= " AND PL.pickup_date = '$date'";
        }
        else
        {
            $where .= " AND PL.pickup_date = CURDATE()";
        }

        // apply dynamic filters ___
        $where .= $this->db->generateConditionString($arrFilters['dynamic']);

        // generate dynamic value array
        $arrDynamicValues = $this->generateDynamicValueArray($arrFilters['dynamic']);

        $arrValues = array_merge($arrValues, $arrDynamicValues);

        $strQuery = "SELECT {$columns} FROM {$table} {$joins} {$where} {$limit} {$order}";

        return $this->db->query($strQuery, $arrValues);
    }

}