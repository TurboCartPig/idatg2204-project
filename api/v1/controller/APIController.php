<?php
require_once 'RequestHandler.php';
require_once 'endpoints/CustomersEndpoint.php';
require_once 'endpoints/CustomerRepEndpoint.php';
require_once 'endpoints/TransportersEndpoint.php';
require_once 'endpoints/PublicEndpoint.php';
require_once 'ReportController.php';
require_once 'RESTConstants.php';
require_once 'database/AuthorizationModel.php';
require_once 'ErrorHandler.php';

/**
 * Class APIController this is the main controller for the API - it is just a dispatcher forwarding the requests to
 *       the DealersEndpoint, UsedCarsEndpoint, or ReportController depending on the what endpoint is addressed
 */
class APIController extends RequestHandler
{

    /**
     * The constructor defines the valid requests to be the dealers, used cars and report controller endpoints.
     * @see RequestHandler
     */
    public function __construct()
    {
        parent::__construct();
        $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMERS;
        $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMER_REP;
        $this->validRequests[] = RESTConstants::ENDPOINT_TRANSPORTERS;
        $this->validRequests[] = RESTConstants::ENDPOINT_PUBLIC;
        $this->validRequests[] = RESTConstants::ENDPOINT_REPORT;
    }

    /**
     * Verifies that the request contains a valid authorisation token. The authorisation scheme is quite simple -
     * assuming that there is only one authorisation token for the complete API
     * @param string $token the authorisation token to be verified
     * @param string $endpointPath the request endpoint
     * @throws APIException with the code set to HTTP_FORBIDDEN if the token is not valid
     */
    public function authorise(string $token, string $endpointPath) {
        if (!(new AuthorizationModel())->isValid($token)) {
            throw new APIException(RESTConstants::HTTP_FORBIDDEN, $endpointPath);
        }
    }

    /**
     * The main function handling the client request to the api. The call is forwarded to the implemented endpoint
     * controllers
     * @param array $uri
     * @param string $endpointPath
     * @param string $requestMethod
     * @param array $queries
     * @param array $payload
     * @return array
     * @throws APIException as described in the superclass
     * @throws BadRequestException
     * @see RequestHandler
     * @see CustomersEndpoint for the customers endpoint controller
     * @see CustomerRepEndpoint for the customer rep endpoint controller
     * @see TransportersEndpoint for the transporters endpoint controller
     * @see PublicEndpoint for the public endpoint controller
     * @see ReportController for the report-generator endpoint controller
     */
    public function handleRequest(array $uri, string $endpointPath, string $requestMethod,
                                  array $queries, array $payload): array
    {
        // Valid requests checked here - valid methods for each request checked in the special endpoint controllers
        $endpointUri = $uri[0];
        if (!$this->isValidRequest($endpointUri)) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
        }
        $endpointPath .= '/' . $uri[0];
        switch ($endpointUri)  {
            case RESTConstants::ENDPOINT_CUSTOMERS:
                $endpoint  = new CustomersEndpoint();
                break;
            case RESTConstants::ENDPOINT_CUSTOMER_REP:
                $endpoint  = new CustomerRepEndpoint();
                break;
            case RESTConstants::ENDPOINT_TRANSPORTERS:
                $endpoint  = new TransportersEndpoint();
                break;
            case RESTConstants::ENDPOINT_PUBLIC:
                $endpoint  = new CustomersEndpoint();
                break;
            case RESTConstants::ENDPOINT_REPORT:
                $endpoint  = new ReportController();
                break;
        }
        return $endpoint->handleRequest(array_slice($uri, 1), $endpointPath, $requestMethod,
            $queries, $payload);

    }
}