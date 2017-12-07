<?php

namespace Zrcms\CorePage\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use Zrcms\Core\Model\Content;
use Zrcms\CorePage\Fields\FieldsPage;
use Zrcms\CorePage\Model\Page;
use Zrcms\Param\Param;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetPageRenderTagsHtml implements GetPageRenderTags
{
    const OPTION_CONTAINER_NAME = 'containerName';

    const DEFAULT_CONTAINER_NAME = Page::DEFAULT_CONTAINER_NAME;

    /**
     * @param Page|Content           $page
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return string[] ['{render-tag' => '{html}']
     * @throws \Exception
     */
    public function __invoke(
        Content $page,
        ServerRequestInterface $request,
        array $options = []
    ): array {
        $containerName = Param::get(
            $options,
            self::OPTION_CONTAINER_NAME,
            self::DEFAULT_CONTAINER_NAME
        );

        $renderTags = [];

        $renderTags[$containerName] = $page->getProperty(
            FieldsPage::PRE_RENDERED_HTML
        );

        return $renderTags;
    }
}