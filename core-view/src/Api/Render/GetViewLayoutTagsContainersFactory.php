<?php

namespace Zrcms\CoreView\Api\Render;

use Psr\Container\ContainerInterface;
use Zrcms\CoreContainer\Api\Render\GetContainerRenderTags;
use Zrcms\CoreContainer\Api\Render\RenderContainer;
use Zrcms\CoreView\Api\GetContainerNamesByLayoutSiteContainers;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetViewLayoutTagsContainersFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetViewLayoutTagsContainers
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        ContainerInterface $serviceContainer
    ) {
        return new GetViewLayoutTagsContainers(
            $serviceContainer->get(GetContainerNamesByLayoutSiteContainers::class),
            $serviceContainer->get(GetContainerRenderTags::class),
            $serviceContainer->get(RenderContainer::class)
        );
    }
}
