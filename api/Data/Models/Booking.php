<?php

namespace Api\Data\Models;

class Booking
{
    const TABLE = 'passengers_log';

    const BOOK_BY_PASSENGER = 1;
    const BOOK_BY_OPERATOR = 2;

    const BOOK_NOW = 0;
    const BOOK_LATER = 1;

    const BOOK_FROM_WEB = 0;
    const BOOK_FROM_DEVICE = 1;
    const BOOK_FROM_STREET = 2;

    const TRIP_STATUS_NOT_COMPLETE = 0;
    const TRIP_STATUS_COMPLETE = 1;
    const TRIP_STATUS_IN_PROGRESS = 2;
    const TRIP_STATUS_START_PICKUP = 3;
    const TRIP_STATUS_PASSENGER_CANCEL = 4;
    const TRIP_STATUS_WAITING_PAYMENT = 5;
    const TRIP_STATUS_MISSED = 6;
    const TRIP_STATUS_DISPATCHED = 7;
    const TRIP_STATUS_DISPATCHER_CANCEL = 8;
    const TRIP_STATUS_CONFIRMED = 9;
    const TRIP_STATUS_EXPIRED = 10;

    const COMPANY_NONE = 0;

    const PASSENGER_ROAD_PICKUP = 716;
}