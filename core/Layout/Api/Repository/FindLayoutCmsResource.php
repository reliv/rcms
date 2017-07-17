<?php

namespace Zrcms\Core\Layout\Api\Repository;

use Zrcms\Content\Api\Repository\FindCmsResource;
use Zrcms\Content\Model\CmsResource;
use Zrcms\Core\Layout\Model\LayoutCmsResource;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FindLayoutCmsResource extends FindCmsResource
{
    /**
     * @param string $uri
     * @param array  $options
     *
     * @return LayoutCmsResource|CmsResource|null
     */
    public function __invoke(
        string $uri,
        array $options = []
    );
}