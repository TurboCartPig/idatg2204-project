<?php
require_once 'RequestHandler.php';

abstract class ResourceController extends RequestHandler
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The main function handling the client request to the resource endpoint. Collection requests are forwarded to the
     * handleCollectionRequest(), while resource requests are forwarded to the handleResourceRequest().
     * @param array $uri
     * @param string $endpointPath
     * @param string $requestMethod
     * @param array $queries
     * @param array $payload
     * @return array
     * @throws APIException as described in the superclass
     * @throws BadRequestException as described in the superclass
     * @see RequestHandler::handleRequest()
     * @see handleCollectionRequest for the handling og collection requests
     * @see handleResourceRequest for the handling of resource requests
     */
    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {
        // Collection request
        if (count($uri) == 0) {
            if (($outcome = $this->isValidMethod('', $requestMethod)) != RESTConstants::HTTP_OK) {
                throw new APIException($outcome, $endpointPath);
            }

            return $this->handleCollectionRequest($endpointPath, $requestMethod, $queries, $payload);
            // Resource request
        } else if (count($uri) == 1) {
            // There are no sub-resources for dealer resources
            if (!ctype_digit($uri[0])) {
                throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath . '/' . $uri[0]);
            }
            if (($outcome = $this->isValidMethod(RESTConstants::CUSTOMER_ID, $requestMethod)) != RESTConstants::HTTP_OK) {
                throw new APIException($outcome, $endpointPath . '/' . $uri[0]);
            }

            // Sub resource requests are not supported
            return $this->handleResourceRequest($endpointPath . '/' . $uri[0], intval($uri[0]), $requestMethod, $payload);
        } else if (count($uri) > 1) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath . '/' . implode('/', $uri));
        }
        return [];
    }

    /**
     * The function handling the collection requests. The function is a dispatcher method that forwards the retrieval
     * and create requests to the respective request handlers.
     * @param string $endpointPath
     * @param string $requestMethod
     * @param array $queries
     * @param array $payload
     * @return array
     * @throws APIException as other request handling methods
     * @throws BadRequestException as other request handling methods
     * @see RequestHandler
     * @see handleRetrieveCollectionRequest for the handling of collection retrieval requests
     * @see handleCreateResourceRequest for the handling of resource creation requests
     */
    public function handleCollectionRequest(string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {
        $model = new CustomersModel();

        $res = array();
        try {
            switch ($requestMethod) {
                case RESTConstants::METHOD_GET:
                    $res['result'] = $this->doRetrieveCollection($queries);
                    if (count($res['result']) > 0) {
                        $res['status'] =  RESTConstants::HTTP_OK;
                    } else {
                        throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
                    }
                    break;
                case RESTConstants::METHOD_POST:
                    $res['result'] = $this->doCreateResource($payload);
                    $res['status'] = RESTConstants::HTTP_CREATED;
                    break;
            }
        } catch (BadRequestException $e) {
            throw new BadRequestException($e->getCode(), $e->getDetailCode(), $endpointPath, $e->getMessage(), $e);
        }
        return $res;
    }

    /**
     * The function handling the resource requests. The function is a dispatcher method that forwards the retrieval,
     * update, and delete requests to the respective request handlers. (Only resource retrieval is currently implemented.)
     * @param string $endpointPath
     * @param int $id
     * @param string $requestMethod
     * @param array $payload
     * @return array
     * @throws APIException as other request handling methods
     * @throws BadRequestException as other request handling methods
     * @see RequestHandler::handleRetrieveResourceRequest
     * @see RequestHandler::handleUpdateResourceRequest
     * @see RequestHandler::handleRDeleteResourceRequest
     */
    public function handleResourceRequest(string $endpointPath, int $id, string $requestMethod, array $payload): array
    {
        $model = new CustomersModel();

        $res = array();
        try {
            switch ($requestMethod) {
                case RESTConstants::METHOD_GET:
                    $res['result'] = $this->doRetrieveResource($id);
                    if ($res['result']) {
                        $res['status'] =  RESTConstants::HTTP_OK;
                    } else {
                        throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
                    }
                    break;
                case RESTConstants::METHOD_PUT:
                    $res['result'] = $this->doUpdateResource($payload);
                    $res['status'] = RESTConstants::HTTP_OK;
                    break;
                case RESTConstants::METHOD_DELETE:
                    $res['result'] = $this->doDeleteResource($id);
                    $res['status'] = RESTConstants::HTTP_OK;
                    break;
            }
        } catch (BadRequestException $e) {
            throw new BadRequestException($e->getCode(), $e->getDetailCode(), $endpointPath, $e->getMessage(), $e);
        }
        return $res;
    }

    /**
     * The function handling the collection retrieval requests.
     * @param array $queries filter parameters being passed from the client
     * @return array the collection of resources
     * @throws BadRequestException as other request handling methods
     * @see handleCollectionRequest
     */
    protected abstract function doRetrieveCollection(array $queries): array;
    /**
     * The function handling the resource creation requests.
     * @param array $payload the resource attributes sent from the client
     * @return array the resource as being stored in the database - including the id created by the database
     * @throws BadRequestException as other request handling methods
     * @see handleCollectionRequest
     */
    protected abstract function doCreateResource(array $payload): array;

    /**
     * The function handling the resource retrieval requests.
     * @param $id int the resource to be retrieved
     * @return array the requested resource
     * @throws BadRequestException as other request handling methods
     * @see handleResourceRequest
     */
    protected abstract function doRetrieveResource(int $id): ?array;

    /**
     * The function handling the resource update requests.
     * @param array $payload the resource as it should be updated
     * @throws BadRequestException as other request handling methods
     * @see handleResourceRequest
     */
    protected abstract function doUpdateResource(array $payload);

    /**
     * The function handling the resource deletion requests.
     * @param $id int the resource to be deleted
     * @throws BadRequestException as other request handling methods
     * @see handleResourceRequest
     */
    protected abstract function doDeleteResource(int $id);
}
