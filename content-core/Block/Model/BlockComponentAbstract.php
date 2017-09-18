<?php

namespace Zrcms\ContentCore\Block\Model;

use Zrcms\Content\Model\ComponentAbstract;
use Zrcms\ContentCore\Block\Fields\FieldsBlockComponent;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class BlockComponentAbstract extends ComponentAbstract
{
    /**
     * @param string $classification
     * @param string $name
     * @param string $configLocation
     * @param array  $properties
     * @param string $createdByUserId
     * @param string $createdReason
     */
    public function __construct(
        string $classification,
        string $name,
        string $configLocation,
        array $properties,
        string $createdByUserId,
        string $createdReason
    ) {
        parent::__construct(
            $classification,
            $name,
            $configLocation,
            $properties,
            $createdByUserId,
            $createdReason
        );
    }

    /**
     * Default config values
     *
     * @return array
     */
    public function getDefaultConfig(): array
    {
        return $this->getProperty(
            FieldsBlockComponent::DEFAULT_CONFIG,
            []
        );
    }

    /**
     * @return bool
     */
    public function isCacheable(): bool
    {
        return $this->getProperty(
            FieldsBlockComponent::CACHEABLE,
            false
        );
    }
}
