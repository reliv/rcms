<?php

namespace Zrcms\HttpApiContainer\CmsResource;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\ArrayProperties\Property;
use Zrcms\Core\Api\CmsResource\CmsResourceToArray;
use Zrcms\CoreContainer\Api\CmsResource\FindContainerCmsResourcesBySitePaths;
use Zrcms\Http\Api\BuildMessageValue;
use Zrcms\Http\Api\BuildResponseOptions;
use Zrcms\Http\Response\ZrcmsJsonResponse;
use Zrcms\HttpApi\Dynamic;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HttpApiFindContainerCmsResourceByHostDynamic
{
    const SOURCE = 'http-api-find-container-cms-resource-by-host-dynamic';
    const ATTRIBUTE_ZRCMS_CONTAINER_HOST = 'zrcms-container-host';
    const PARAM_PUBLISHED = 'published';

    protected $serviceContainer;
    protected $cmsResourceToArrayDefault;
    protected $notFoundStatusDefault;
    protected $debug;

    /**
     * @param ContainerInterface $serviceContainer
     * @param CmsResourceToArray $cmsResourceToArrayDefault
     * @param int                $notFoundStatusDefault
     * @param bool               $debug
     */
    public function __construct(
        ContainerInterface $serviceContainer,
        CmsResourceToArray $cmsResourceToArrayDefault,
        int $notFoundStatusDefault = 404,
        bool $debug = false
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->cmsResourceToArrayDefault = $cmsResourceToArrayDefault;
        $this->notFoundStatusDefault = $notFoundStatusDefault;
        $this->debug = $debug;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return ResponseInterface|ZrcmsJsonResponse
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        $dynamicApiConfig = $request->getAttribute(Dynamic::ATTRIBUTE_DYNAMIC_API_CONFIG);

        $apiConfig = Property::getArray(
            $dynamicApiConfig,
            Dynamic::MIDDLEWARE_NAME_API,
            []
        );

        $apiServiceName = Property::getString(
            $apiConfig,
            'api-service',
            null
        );

        if ($apiServiceName === null) {
            throw new \Exception('api-service must be defined');
        }

        /** @var FindContainerCmsResourceByHost $apiService */
        $apiService = $this->serviceContainer->get($apiServiceName);

        if (!$apiService instanceof FindContainerCmsResourceByHost) {
            throw new \Exception('api-service must be instance of ' . FindContainerCmsResourceByHost::class);
        }

        $host = $request->getAttribute(self::ATTRIBUTE_ZRCMS_CONTAINER_HOST);
        $queryParams = $request->getQueryParams();
        $published = Property::get(
            $queryParams,
            self::PARAM_PUBLISHED,
            null
        );

        $cmsResource = $apiService->__invoke(
            $host,
            $published,
            []
        );

        if (empty($cmsResource)) {
            $notFoundStatus = Property::getInt(
                $apiConfig,
                'not-found-status',
                $this->notFoundStatusDefault
            );

            return new ZrcmsJsonResponse(
                null,
                BuildMessageValue::invoke(
                    (string)$notFoundStatus,
                    'Not Found with host: ' . $host,
                    $request->getAttribute(Dynamic::ATTRIBUTE_DYNAMIC_API_TYPE),
                    self::SOURCE
                ),
                $notFoundStatus,
                [],
                BuildResponseOptions::invoke()
            );
        }

        $toArrayService = $this->cmsResourceToArrayDefault;

        $toArrayServiceName = Property::getString(
            $apiConfig,
            'to-array',
            null
        );

        if ($toArrayServiceName !== null) {
            /** @var CmsResourceToArray $toArrayService */
            $toArrayService = $this->serviceContainer->get($toArrayServiceName);
        }

        if (!$toArrayService instanceof CmsResourceToArray) {
            throw new \Exception(
                'to-array must be instance of ' . CmsResourceToArray::class
                . ' got .' . get_class($toArrayService)
                . ' for dynamic api: (' . $request->getAttribute(Dynamic::ATTRIBUTE_DYNAMIC_API_TYPE) . ')'
            );
        }

        return new ZrcmsJsonResponse(
            $toArrayService->__invoke($cmsResource),
            null,
            200,
            [],
            BuildResponseOptions::invoke()
        );
    }
}
