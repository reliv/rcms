<?php

namespace Zrcms\Core\Tracking\Model;

use Zrcms\Core\Tracking\Exception\TrackingException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class TrackableAbstract implements Trackable
{
    use TrackableTrait;

    /**
     * @param string $createdByUserId <tracking>
     * @param string $createdReason   <tracking>
     */
    public function __construct(
        string $createdByUserId,
        string $createdReason,
        string $trackingId
    ) {
        $this->setCreatedData(
            $createdByUserId,
            $createdReason,
            $trackingId
        );
    }
}