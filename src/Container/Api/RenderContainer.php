<?php

namespace Rcms\Core\Container\Api;

use Rcms\Core\Container\Model\Container;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface RenderContainer
{
    /**
     * @param Container $container
     * @param array     $options
     *
     * @return string
     */
    public function __invoke(
        Container $container,
        array $options = []
    ): string;
}
