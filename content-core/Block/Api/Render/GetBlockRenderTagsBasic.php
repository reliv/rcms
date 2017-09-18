<?php

namespace Zrcms\ContentCore\Block\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use Zrcms\Content\Model\Content;
use Zrcms\ContentCore\Block\Api\GetMergedConfig;
use Zrcms\ContentCore\Block\Api\Repository\GetBlockData;
use Zrcms\ContentCore\Block\Model\Block;
use Zrcms\ContentCore\Block\Fields\FieldsBlock;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetBlockRenderTagsBasic implements GetBlockRenderTags
{
    /**
     * @var GetBlockData
     */
    protected $getBlockData;

    /**
     * @var GetMergedConfig
     */
    protected $getMergedConfig;

    /**
     * @param GetBlockData    $getBlockData
     * @param GetMergedConfig $getMergedConfig
     */
    public function __construct(
        GetBlockData $getBlockData,
        GetMergedConfig $getMergedConfig
    ) {
        $this->getBlockData = $getBlockData;
        $this->getMergedConfig = $getMergedConfig;
    }

    /**
     * @param Block|Content          $block
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return string [] ['render-tag}' => '{html}']
     */
    public function __invoke(
        Content $block,
        ServerRequestInterface $request,
        array $options = []
    ): array
    {
        $config = $this->getMergedConfig->__invoke(
            $block
        );

        return [
            FieldsBlock::RENDER_DATA_ID
            => $block->getId(),

            FieldsBlock::RENDER_DATA_CONFIG
            => $config,

            FieldsBlock::RENDER_DATA_DATA
            => $this->getBlockData->__invoke(
                $block,
                $request
            )
        ];
    }
}
