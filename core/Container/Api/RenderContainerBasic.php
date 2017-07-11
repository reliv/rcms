<?php

namespace Zrcms\Core\Container\Api;

use Zrcms\Core\BlockInstance\Api\FindBlockInstancesByContainer;
use Zrcms\Core\BlockInstance\Api\RenderBlockInstance;
use Zrcms\Core\Container\Model\Container;

class RenderContainerBasic implements RenderContainer
{
    /**
     * @var FindBlockInstancesByContainer
     */
    protected $findBlockInstancesByContainer;

    protected $renderBlockInstance;

    protected $wrapRenderedBlockInstance;

    protected $wrapRenderedContainer;

    /**
     * @param FindBlockInstancesByContainer $findBlockInstancesByContainer
     * @param RenderBlockInstance           $renderBlockInstance
     * @param WrapRenderedContainer         $wrapRenderedContainer
     */
    public function __construct(
        FindBlockInstancesByContainer $findBlockInstancesByContainer,
        RenderBlockInstance $renderBlockInstance,
        WrapRenderedContainer $wrapRenderedContainer
    ) {
        $this->findBlockInstancesByContainer = $findBlockInstancesByContainer;
        $this->renderBlockInstance = $renderBlockInstance;
        $this->wrapRenderedContainer = $wrapRenderedContainer;
    }

    /**
     * @param Container $container
     * @param array     $containerRenderData
     * @param array     $options
     *
     * @return string
     */
    public function __invoke(
        Container $container,
        array $containerRenderData,
        array $options = []
    ): string
    {
        $containerInnerHtml = '';
        foreach ($containerRenderData as $row) {
            $containerInnerHtml .= '<div class="row">';
            foreach ($row as $block) {
                $containerInnerHtml .= $block;
            }
            $containerInnerHtml .= '</div>';
        }

        return $this->wrapRenderedContainer->__invoke(
            $containerInnerHtml,
            $container
        );
    }
}