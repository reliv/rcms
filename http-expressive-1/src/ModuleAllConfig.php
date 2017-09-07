<?php

namespace Zrcms\HttpExpressive1;

use Zend\ConfigAggregator\ConfigAggregator;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleAllConfig
{
    /**
     * __invoke
     *
     * @return array
     */
    public function __invoke()
    {
        $zrcmsModules = [
            new ModuleConfig(),
            new HttpApiSiteConfig(),
        ];

        $configManager = new ConfigAggregator(
            $zrcmsModules
        );

        return $configManager->getMergedConfig();
    }
}