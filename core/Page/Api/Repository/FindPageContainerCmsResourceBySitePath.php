<?php

namespace Zrcms\Core\Page\Api\Repository;

use Zrcms\Content\Model\CmsResource;
use Zrcms\Core\Page\Model\PageContainerCmsResource;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface FindPageContainerCmsResourceBySitePath
{
    /**
     * @param string $siteCmsResourceId
     * @param string $pageContainerCmsResourcePath
     * @param array  $options
     *
     * @return PageContainerCmsResource|CmsResource|null
     */
    public function __invoke(
        string $siteCmsResourceId,
        string $pageContainerCmsResourcePath,
        array $options = []
    );
}
