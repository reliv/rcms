<?php

namespace Zrcms\CoreApplication\Api\Component;

use Psr\Container\ContainerInterface;
use Zrcms\Core\Api\GetTypeValue;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildComponentObjectByTypeStrategyFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return BuildComponentObjectByTypeStrategy
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        ContainerInterface $serviceContainer
    ) {
        return new BuildComponentObjectByTypeStrategy(
            $serviceContainer,
            $serviceContainer->get(GetTypeValue::class)
        );
    }
}
