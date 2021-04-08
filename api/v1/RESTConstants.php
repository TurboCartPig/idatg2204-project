<?php

/**
 * Class RESTConstants class for application constants.
 */
class RESTConstants
{
    const API_URI = 'http://localhost/api/v1';

    // HTTP method names
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    // HTTP status codes
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED = 501;

    // Endpoints
    const ENDPOINT_CUSTOMERS = 'customers';
    const CUSTOMER_ID = '{:customer_id}';
    const SUMMARY = 'summary';

    const ENDPOINT_CUSTOMER_REP = 'customer_rep';
    const EMPLOYEE_ID = '{:employee_id}';

    const ENDPOINT_TRANSPORTERS = 'transporters';

    const ENDPOINT_PUBLIC = 'public';

    const ORDERS = 'orders';
    const ORDER_NUMBER = '{:order_number}';

    const SHIPMENTS = 'shipments';
    const SHIPMENT_NUMBER = '{:shipment_number}';

    const ENDPOINT_REPORT = 'create-report';

    // Defined database errors
    const DB_ERR_ATTRIBUTE_MISSING = 1;
    const DB_ERR_FK_INTEGRITY = 2;

    // Defined foreign key violations
    const DB_FK_DEALER_COUNTY = 1001;
    const DB_FK_CAR_DEALER = 1002;

    const ENDPOINT_REPORT_DEALER_STOCK = 'dealer-stock';
}
