<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Api\Core\Abstracts\ControllerAbstract;
use Api\Core\Enums\ResponseCodeEnum;
use Api\Logic\Booking\BookingLogic;
use Api\Validations\BookingRules;

class BookingController extends ControllerAbstract
{
    private $booking;


    public function __construct($app)
    {
        parent::__construct($app);

        $this->booking = new BookingLogic();
    }


    public function index(Request $request, Response $response)
	{
//        $this->validator->validate($this->arrRequestBody, HelloRules::SELECT);

        $data = $this->booking->getAllBookings($this->arrRequestParams);

        return $response->withJson($data, ResponseCodeEnum::HTTP_OK);
	}

}
