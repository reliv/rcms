<?php

namespace Zrcms\Core\View\Api\Repository;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FindViewRenderDataGetters
{
    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     * @param array      $options
     *
     * @return array [GetViewRenderData]
     */
    public function __invoke(
        array $criteria,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        array $options = []
    ): array;
}