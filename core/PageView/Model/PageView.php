<?php

namespace Zrcms\Core\PageView\Model;

use Zrcms\Content\Model\Content;
use Zrcms\Core\Layout\Model\LayoutCmsResource;
use Zrcms\Core\Page\Model\PageCmsResource;
use Zrcms\Core\Site\Model\SiteCmsResource;
use Zrcms\Core\Theme\Model\Theme;

/**
 * ViewModel
 *
 * @author James Jervis - https://github.com/jerv13
 */
interface PageView extends Content
{
    /**
     * @return SiteCmsResource
     */
    public function getSiteCmsResource(): SiteCmsResource;

    /**
     * @return PageCmsResource
     */
    public function getPageCmsResource(): PageCmsResource;

    /**
     * @return Theme
     */
    public function getTheme(): Theme;

    /**
     * @return LayoutCmsResource
     */
    public function getLayoutCmsResource(): LayoutCmsResource;

    /**
     * @return array
     */
    public function getLayoutRenderData(): array;
}