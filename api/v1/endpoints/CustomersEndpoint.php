<?php
require_once 'RESTConstants.php';
require_once 'controller/ResourceController.php';
require_once 'ErrorHandler.php';
require_once 'database/CustomersModel.php';

/**
 * Class DealersEndpoint implementing the dealers endpoint controller.
 */
class CustomersEndpoint extends ResourceController
{
    /**
     * DealersEndpoint constructor. It specifies which sub resource requests are allowed It also defines which functions
     * are implemented on the collection and the resource.
     * @see RequestHandler::$validRequests
     * @see RequestHandler::$validMethods
     */
    public function __construct()
    {
        parent::__construct();
        $this->validRequests[] = RESTConstants::CUSTOMER_ID;
        $this->validRequests[] = RESTConstants::SUMMARY;

        // Valid collection method calls vs implementation status
        $this->validMethods[''] = array();
        $this->validMethods[''][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;
        //$this->validMethods[''][RESTConstants::METHOD_POST] = RESTConstants::HTTP_OK;

        $this->validMethods[RESTConstants::SUMMARY] = array();
        $this->validMethods[RESTConstants::SUMMARY][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;

        // Valid resource method calls vs implementation status
        $this->validMethods[RESTConstants::CUSTOMER_ID] = array();
        $this->validMethods[RESTConstants::CUSTOMER_ID][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;
    }

    /**
     * @param array $queries
     * @return array
     * @see ResourceController::doRetrieveCollection
     */
    protected function doRetrieveCollection(array $queries): array
    {
        $filter = null;
        if (isset($queries['customers'])) {
            $filter = array();
            $filter['customers'] = preg_split('/[,][\s]*/', $queries['customers']);
            
        }
        return (new CustomersModel())->getCollection($filter);
        //if (isset($queries['summary'])) {
        //    $filter = array();
        //    $filter['summary'] = preg_split('/[,][\s]*/', $queries['summary']);
        //    return (new CustomersModel())->getCollection($filter);
        //}
        
    }

    /**
     * @param int $id
     * @return array|null
     * @throws BadRequestException as other request handling methods
     * @see ResourceController::doRetrieveResource
     */
    protected function doRetrieveResource(int $id): ?array
    {
        return (new CustomersModel())->getOneCustomer($id);
    }

    /**
     * @param array $payload
     * @return array
     * @throws BadRequestException as other request handling methods
     * @see ResourceController::doCreateResource
     */
    protected function doCreateResource(array $payload): array
    {
        return (new CustomersModel())->createCustomer($payload);
    }

    /**
     * @param array $payload
     * @see ResourceController::doUpdateResource
     */
    protected function doUpdateResource(array $payload)
    {
        (new CustomersModel())->updateResource($payload);
    }

    /**
     * @param int $id
     * @see ResourceController::doDeleteResource
     */
    protected function doDeleteResource(int $id)
    {
        (new CustomersModel())->deleteResource($id);
    }
}
