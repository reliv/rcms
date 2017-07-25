<?php

namespace Zrcms\ContentCoreDoctrineDataSource\Block\Model;

use Zrcms\ContentCore\Block\Model\BlockVersion;
use Zrcms\ContentCore\Block\Model\BlockVersionAbstract;
use Zrcms\Param\Param;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BlockVersionEntity extends BlockVersionAbstract implements BlockVersion
{
    /**
     * @param array  $properties
     * @param string $createdByUserId
     * @param string $createdReason
     */
    public function __construct(
        array $properties,
        string $createdByUserId,
        string $createdReason
    ) {
        $this->createdDate = Param::getRequired(
            $properties,
            PropertiesBlockVersionEntity::CREATED_BY_USER_ID
        );

        // Id is required to preserve interface and for caching
        $this->id = Param::getRequired(
            $properties,
            PropertiesBlockVersionEntity::ID
        );

        parent::__construct(
            $properties,
            $createdByUserId,
            $createdReason
        );
    }
}