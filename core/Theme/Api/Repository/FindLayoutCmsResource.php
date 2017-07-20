<?php

namespace Zrcms\Core\Theme\Api\Repository;

use Zrcms\Content\Api\Repository\FindCmsResource;
use Zrcms\Content\Model\CmsResource;
use Zrcms\Core\Theme\Model\LayoutCmsResource;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FindLayoutCmsResource extends FindCmsResource
{
    /**
     * @param string $layoutUri
     * @param array  $options
     *
     * @return LayoutCmsResource|CmsResource|null
     */
    public function __invoke(
        string $layoutUri,
        array $options = []
    );
}
