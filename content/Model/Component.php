<?php

namespace Zrcms\Content\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Component extends Immutable, Properties, Trackable
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
    );

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * Directory where file are located
     *
     * @return string
     */
    public function getDirectory(): string ;
}