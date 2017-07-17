<?php

namespace Zrcms\Core\PageView\Model;

use Zrcms\Content\Model\ContentAbstract;
use Zrcms\Core\Layout\Model\LayoutCmsResource;
use Zrcms\Core\Page\Model\PageCmsResource;
use Zrcms\Core\Site\Model\SiteCmsResource;
use Zrcms\Core\Theme\Model\Theme;
use Zrcms\Param\Param;

/**
 * ViewModel
 *
 * @author James Jervis - https://github.com/jerv13
 */
abstract class PageViewAbstract extends ContentAbstract implements PageView
{
    protected $siteCmsResource;

    protected $pageCmsResource;

    protected $theme;

    protected $layoutCmsResource;

    protected $layoutRenderData = [];

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
        $this->siteCmsResource = Param::getRequired(
            $properties,
            PageViewProperties::SITE_CMS_RESOURCE
        );

        $this->pageCmsResource = Param::getRequired(
            $properties,
            PageViewProperties::PAGE_CMS_RESOURCE
        );

        $this->theme = Param::getRequired(
            $properties,
            PageViewProperties::THEME
        );

        $this->layoutCmsResource = Param::getRequired(
            $properties,
            PageViewProperties::LAYOUT_CMS_RESOURCE
        );

        $this->layoutRenderData = Param::getRequired(
            $properties,
            PageViewProperties::LAYOUT_RENDER_DATA
        );

        parent::__construct(
            $properties,
            $createdByUserId,
            $createdReason
        );
    }

    /**
     * @return SiteCmsResource
     */
    public function getSiteCmsResource(): SiteCmsResource
    {
        return $this->siteCmsResource;
    }

    /**
     * @return PageCmsResource
     */
    public function getPageCmsResource(): PageCmsResource
    {
        return $this->pageCmsResource;
    }

    /**
     * @return Theme
     */
    public function getTheme(): Theme
    {
        return $this->theme;
    }

    /**
     * @return LayoutCmsResource
     */
    public function getLayoutCmsResource(): LayoutCmsResource
    {
        return $this->layoutCmsResource;
    }

    /**
     * @return array
     */
    public function getLayoutRenderData(): array
    {
        return $this->layoutRenderData;
    }
}