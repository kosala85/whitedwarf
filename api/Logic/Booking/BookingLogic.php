<?php

namespace Api\Logic\Booking;

use Api\Core\Abstracts\LogicAbstract;
use Api\Data\Repositories\BookingRepository;

class BookingLogic extends LogicAbstract
{
	public function getAllBookings($arrFilters)
	{
		$bookingRepository = new BookingRepository();

        $this->db->transBegin();

        try
        {
            $result = $bookingRepository->selectBookingsByFilters($arrFilters);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
	}
}
