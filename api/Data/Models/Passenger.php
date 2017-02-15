<?php

namespace Api\Data\Models;

class Passenger
{
    const TABLE = 'passengers';

    const ACTIVE = 'A';
    const INACTIVE = 'D';

    const CREATED_BY_PASSENGER = 1;
    const CREATED_BY_ADMIN = 2;
    const CREATED_BY_COMPANY = 3;

    const ACTIVATED = 1;
}