<?php

namespace DBProject;

/**
 * Class Constants - Contains HTTP constants and status/error messages/text.
 * @package DBProject
 */
class Constants
{
    const HTTP_OK = 200;
    const HTTP_NO_CONTENT = 204;

    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const UNAUTHORIZED_TEXT = "User not authorized";

    const HTTP_INTERNAL_SERVER_ERROR = 500;
}
