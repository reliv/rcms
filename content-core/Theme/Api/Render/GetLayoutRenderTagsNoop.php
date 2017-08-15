<?php

namespace Zrcms\ContentCore\Theme\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use Zrcms\Content\Model\Content;
use Zrcms\ContentCore\Theme\Model\Layout;

/**
 * @deprecated NOT NEEDED?
 * @author James Jervis - https://github.com/jerv13
 */
class GetLayoutRenderTagsNoop implements GetLayoutRenderTags
{
    /**
     * @param Layout|Content         $layout
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return string[] ['{render-tag}' => '{html}']
     * @throws \Exception
     */
    public function __invoke(
        Content $layout,
        ServerRequestInterface $request,
        array $options = []
    ): array
    {
        return [];
    }
}