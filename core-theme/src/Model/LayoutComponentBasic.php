<?php

namespace Zrcms\CoreTheme\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class LayoutComponentBasic extends LayoutComponentAbstract implements LayoutComponent
{
    /**
     * @param string      $type
     * @param string      $name
     * @param string      $configUri
     * @param string      $moduleDirectory
     * @param array       $properties
     * @param string      $createdByUserId
     * @param string      $createdReason
     * @param string|null $createdDate
     */
    public function __construct(
        string $type,
        string $name,
        string $configUri,
        string $moduleDirectory,
        array $properties,
        string $createdByUserId,
        string $createdReason,
        $createdDate = null
    ) {
        parent::__construct(
            $type,
            $name,
            $configUri,
            $moduleDirectory,
            $properties,
            $createdByUserId,
            $createdReason,
            $createdDate
        );
    }
}
