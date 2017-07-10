<?php

namespace Zrcms\Core\Page\Api;

use Zrcms\Core\Page\Model\PageDraft;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface CreatePageDraft
{
    /**
     * @param string $uri
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $properties
     * @param array  $options
     *
     * @return PageDraft
     */
    public function __invoke(
        string $uri,
        string $createdByUserId,
        string $createdReason,
        array $properties,
        array $options = []
    ): PageDraft;
}
