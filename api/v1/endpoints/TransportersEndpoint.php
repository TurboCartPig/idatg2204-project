<?php
require_once 'RESTConstants.php';
require_once 'controller/ResourceController.php';
require_once 'ErrorHandler.php';
require_once 'database/TransportersModel.php';

/**
 * Class DealersEndpoint implementing the dealers endpoint controller.
 */
class TransportersEndpoint extends ResourceController
{
    /**
     * @var CustomersModel
     */
    private $customerModel;

    /**
     * DealersEndpoint constructor. It specifies which sub resource requests are allowed It also defines which functions
     * are implemented on the collection and the resource.
     * @see RequestHandler::$validRequests
     * @see RequestHandler::$validMethods
     */
    public function __construct()
    {
        parent::__construct();
        $this->validRequests[] = RESTConstants::SHIPMENTS;
        $this->validRequests[] = RESTConstants::SHIPMENT_NUMBER;

        // Valid collection method calls vs implementation status
        $this->validMethods[''] = array();
        $this->validMethods[''][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;
        //$this->validMethods[''][RESTConstants::METHOD_POST] = RESTConstants::HTTP_OK;

        $this->validMethods[RESTConstants::SHIPMENTS] = array();
        $this->validMethods[RESTConstants::SHIPMENTS][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;

        // Valid resource method calls vs implementation status
        $this->validMethods[RESTConstants::SHIPMENT_NUMBER] = array();
        $this->validMethods[RESTConstants::SHIPMENT_NUMBER][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;

        $this->customerModel = new CustomersModel();
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
        return $this->customerModel->getCustomerSummary($filter);
    }

    /**
     * @param int $id
     * @return array|null
     * @throws BadRequestException as other request handling methods
     * @see ResourceController::doRetrieveResource
     */
    protected function doRetrieveResource(int $id): ?array
    {
        return $this->customerModel->getOneCustomer($id);
    }

    /**
     * @param array $payload
     * @return array
     * @throws BadRequestException as other request handling methods
     * @see ResourceController::doCreateResource
     */
    protected function doCreateResource(array $payload): array
    {
        return $this->customerModel->createCustomer($payload);
    }

    /**
     * @param array $payload
     * @see ResourceController::doUpdateResource
     */
    protected function doUpdateResource(array $payload)
    {
        $this->customerModel->updateResource($payload);
    }

    /**
     * @param int $id
     * @see ResourceController::doDeleteResource
     */
    protected function doDeleteResource(int $id)
    {
        $this->customerModel->deleteResource($id);
    }
}