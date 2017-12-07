<?php

namespace Zrcms\CoreContainer\Api\Render;

use Zrcms\Core\Model\Content;
use Zrcms\CoreBlock\Api\Render\RenderBlock;
use Zrcms\CoreContainer\Api\Render\WrapRenderedContainer;
use Zrcms\CoreContainer\Model\Container;
use Zrcms\CoreContainer\Model\ContainerVersion;
use Zrcms\Param\Param;

class RenderContainerRows implements RenderContainer
{
    /**
     * @var RenderBlock
     */
    protected $renderBlock;

    /**
     * @var WrapRenderedContainer
     */
    protected $wrapRenderedContainer;

    /**
     * @param RenderBlock           $renderBlock
     * @param WrapRenderedContainer $wrapRenderedContainer
     */
    public function __construct(
        RenderBlock $renderBlock,
        WrapRenderedContainer $wrapRenderedContainer
    ) {
        $this->renderBlock = $renderBlock;
        $this->wrapRenderedContainer = $wrapRenderedContainer;
    }

    /**
     * @param Container|ContainerVersion|Content $container
     * @param array             $renderTags ['render-tag' => '{html}']
     * @param array             $options
     *
     * @return string
     */
    public function __invoke(
        Content $container,
        array $renderTags,
        array $options = []
    ): string
    {
        $comment = Param::get(
            $options,
            'comment',
            'container'
        );

        $containerInnerHtml = '<!-- <' . $comment . ' ' . $container->getId() . '> -->';
        foreach ($renderTags as $row) {
            $containerInnerHtml .= "\n<div class=\"row\">\n";
            if (is_array($row)) {
                foreach ($row as $block) {
                    $containerInnerHtml .= $block;
                }
            } else {
                $containerInnerHtml .= (string)$row;
            }
            $containerInnerHtml .= "\n</div>\n";
        }
        $containerInnerHtml .= '<!-- </' . $comment . ' ' . $container->getId() . '> -->';

        return $this->wrapRenderedContainer->__invoke(
            $containerInnerHtml,
            $container
        );
    }
}