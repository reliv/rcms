<?php

namespace Zrcms\HttpExpressive1\HttpApi\Content\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zrcms\Content\Model\PropertiesCmsResource;
use Zrcms\HttpExpressive1\Model\ResponseCodes;
use Zrcms\HttpResponseHandler\Api\HandleResponseApi;
use Zrcms\HttpResponseHandler\Model\HandleResponseOptions;
use Zrcms\User\Api\GetUserIdByRequest;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UnpublishCmsResource
{
    const SOURCE = 'zrcms-unpublish-cms-resource';

    /**
     * @var \Zrcms\Content\Api\Action\UnpublishCmsResource
     */
    protected $unpublishCmsResource;

    /**
     * @var GetUserIdByRequest
     */
    protected $getUserIdByRequest;

    /**
     * @var HandleResponseApi
     */
    protected $handleResponseApi;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param \Zrcms\Content\Api\Action\UnpublishCmsResource $unpublishCmsResource
     * @param GetUserIdByRequest                             $getUserIdByRequest
     * @param HandleResponseApi                              $handleResponseApi
     * @param string                                         $name
     */
    public function __construct(
        \Zrcms\Content\Api\Action\UnpublishCmsResource $unpublishCmsResource,
        GetUserIdByRequest $getUserIdByRequest,
        HandleResponseApi $handleResponseApi,
        string $name
    ) {
        $this->unpublishCmsResource = $unpublishCmsResource;
        $this->getUserIdByRequest = $getUserIdByRequest;
        $this->handleResponseApi = $handleResponseApi;
        $this->name = $name;
    }

    /**
     * __invoke
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $cmsResourceId = (string)$request->getAttribute(
            PropertiesCmsResource::ID
        );

        if (empty($cmsResourceId)) {
            $response = new JsonResponse(
                null,
                400
            );

            return $this->handleResponseApi->__invoke(
                $response,
                [
                    HandleResponseOptions::API_MESSAGES => [
                        'type' => $this->name,
                        'value' => 'ID not received',
                        'source' => self::SOURCE,
                        'code' => ResponseCodes::ID_NOT_RECEIVED,
                        'primary' => true,
                        'params' => []
                    ]
                ]
            );
        }

        $success = $this->unpublishCmsResource->__invoke(
            $cmsResourceId,
            $this->getUserIdByRequest->__invoke($request),
            get_class($this)
        );

        if (!$success) {
            $response = new JsonResponse(
                false,
                400
            );

            return $this->handleResponseApi->__invoke(
                $response,
                [
                    HandleResponseOptions::API_MESSAGES => [
                        'type' => $this->name,
                        'value' => 'Update failed',
                        'source' => self::SOURCE,
                        'code' => ResponseCodes::FAILED,
                        'primary' => true,
                        'params' => []
                    ]
                ]
            );
        }

        $response = new JsonResponse(
            $success
        );

        return $this->handleResponseApi->__invoke(
            $response
        );
    }
}
