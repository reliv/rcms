<?php

namespace Zrcms\Content\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use Zrcms\Content\Model\Content;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetContentRenderData
{
    /**
     * @param Content                $content
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return array ['templateTag' => '{html}']
     */
    public function __invoke(
        Content $content,
        ServerRequestInterface $request,
        array $options = []
    ): array;
}