<?php

namespace Api\Data\Models;

class DriverRequest
{
    const TABLE = 'driver_request_details';

    const STATUS_AVAILABLE_TRIP = 0;
    const STATUS_SENT_TO_DRIVER_AWAIT_REPLY = 1;
    const STATUS_TIMED_OUT = 2;
    const STATUS_ACCEPTED = 3;
    const STATUS_PASSENGER_CANCELLED = 4;
    const STATUS_SYSTEM_AUTO_CANCELLED = 10;
    const STATUS_SAME_TIME = 11;
}