<?php

namespace Zrcms\HttpApi\CmsResource;

use Psr\Container\ContainerInterface;
use Zrcms\Core\Api\CmsResource\CmsResourcesToArray;
use Zrcms\Debug\IsDebug;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HttpApiFindCmsResourcesByDynamicFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return HttpApiFindCmsResourcesByDynamic
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        ContainerInterface $serviceContainer
    ) {
        return new HttpApiFindCmsResourcesByDynamic(
            $serviceContainer,
            $serviceContainer->get(CmsResourcesToArray::class),
            IsDebug::invoke()
        );
    }
}
