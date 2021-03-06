<?php

namespace Zrcms\CoreBlock\Api\Component;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface PrepareComponentConfigBlock
{
    /**
     * @param array $blockConfig
     * @param array $options
     *
     * @return array
     */
    public function __invoke(
        array $blockConfig,
        array $options = []
    ): array;
}
