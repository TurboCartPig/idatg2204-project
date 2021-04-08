<?php
require_once 'RESTConstants.php';
require_once 'ErrorHandler.php';
require_once 'RequestHandler.php';


/**
 * Class ReportController implementing the generate report controller.
 */
class ReportController extends RequestHandler
{
    /**
     * ReportController constructor. It specifies which sub resource requests are allowed.
     * @see RequestHandler::$validRequests
     * @see RequestHandler::$validMethods
     */
    public function __construct()
    {
        parent::__construct();
        $this->validRequests[] = RESTConstants::ENDPOINT_REPORT_DEALER_STOCK;
        $this->validMethods[RESTConstants::ENDPOINT_REPORT_DEALER_STOCK][RESTConstants::METHOD_GET] = RESTConstants::HTTP_NOT_IMPLEMENTED;
    }

    /**
     * The main function handling the client request to the report controller. Dealer stock report requests are
     * forwarded to the handleDealerStockRequest()
     * @param array $uri
     * @param string $endpointPath
     * @param string $requestMethod
     * @param array $queries
     * @param array $payload
     * @return array
     * @throws APIException as described in the superclass
     * @throws BadRequestException as described in the superclass
     * @see RequestHandler
     * @see handleDealerStockRequest for the handling of dealer stock report requests
     */
    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {
        // No report specified
        if (count($uri) == 0) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
            // A specific report requested
        } else if (count($uri) == 1) {
            if ($this->isValidRequest($uri[0]) != RESTConstants::HTTP_OK) {
                throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath . '/' . $uri[0]);
            }
            if (($outcome = $this->isValidMethod($uri[0], $requestMethod)) != RESTConstants::HTTP_OK) {
                throw new APIException($outcome, $endpointPath . '/' . $uri[0]);
            }
            // Sub-reports requested
        } else if (count($uri) > 1) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath . '/' . implode('/', $uri));
        }

        switch ($uri[0]) {
            case RESTConstants::ENDPOINT_REPORT_DEALER_STOCK:
                return $this->handleDealerStockRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
                break;
        }
        return [];
    }

    /**
     * The function handling the dealer stock report requests.
     * @param array $uri
     * @param string $endpointPath
     * @param string $requestMethod
     * @param array $queries
     * @param array $payload
     * @return array
     * @see RequestHandler::handleRequest
     */
    public function handleDealerStockRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {

        return array();
    }


}