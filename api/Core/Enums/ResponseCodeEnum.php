<?php

namespace Api\Core\Enums;

class ResponseCodeEnum
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;

    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_UNPROCESSABLE = 422;

    const HTTP_SERVER_ERROR = 500;
}
