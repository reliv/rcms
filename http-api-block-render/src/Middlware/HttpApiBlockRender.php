<?php

namespace Zrcms\HttpApiBlockRender\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Reliv\ArrayProperties\Property;
use Zrcms\Core\Api\Component\FindComponent;
use Zrcms\Core\Api\Content\ContentVersionToArray;
use Zrcms\CoreBlock\Api\Render\GetBlockRenderTags;
use Zrcms\CoreBlock\Api\Render\RenderBlock;
use Zrcms\CoreBlock\Api\Render\WrapRenderedBlockVersion;
use Zrcms\CoreBlock\Fields\FieldsBlockVersion;
use Zrcms\CoreBlock\Model\BlockVersionBasic;
use Zrcms\Http\Api\BuildMessageValue;
use Zrcms\Http\Api\BuildResponseOptions;
use Zrcms\Http\Response\ZrcmsJsonResponse;
use Zrcms\User\Api\GetUserIdByRequest;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HttpApiBlockRender implements MiddlewareInterface
{
    const SOURCE = 'block-render';
    const API_TYPE = 'zrcms-http-api';
    const PARAM_ID = 'block-id';
    const PARAM_CONTENT_ONLY = 'content-only';

    protected $findComponent;
    protected $getBlockRenderTags;
    protected $renderBlock;
    protected $wrapRenderedBlockVersion;
    protected $getUserIdByRequest;
    protected $contentVersionToArray;
    protected $notFoundStatus;
    protected $debug;

    /**
     * @param FindComponent            $findComponent
     * @param GetBlockRenderTags       $getBlockRenderTags
     * @param RenderBlock              $renderBlock
     * @param WrapRenderedBlockVersion $wrapRenderedBlockVersion
     * @param GetUserIdByRequest       $getUserIdByRequest
     * @param ContentVersionToArray    $contentVersionToArray
     * @param int                      $notFoundStatus
     * @param bool                     $debug
     */
    public function __construct(
        FindComponent $findComponent,
        GetBlockRenderTags $getBlockRenderTags,
        RenderBlock $renderBlock,
        WrapRenderedBlockVersion $wrapRenderedBlockVersion,
        GetUserIdByRequest $getUserIdByRequest,
        ContentVersionToArray $contentVersionToArray,
        int $notFoundStatus = 404,
        bool $debug = false
    ) {
        $this->findComponent = $findComponent;
        $this->getBlockRenderTags = $getBlockRenderTags;
        $this->renderBlock = $renderBlock;
        $this->wrapRenderedBlockVersion = $wrapRenderedBlockVersion;
        $this->getUserIdByRequest = $getUserIdByRequest;
        $this->contentVersionToArray = $contentVersionToArray;
        $this->notFoundStatus = $notFoundStatus;
        $this->debug = $debug;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface|ZrcmsJsonResponse
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        $blockComponentName = (string)$this->getBlockComponentName($request);

        $blockComponent = $this->findComponent->__invoke(
            'block',
            $blockComponentName
        );

        if (empty($blockComponent)) {
            return new ZrcmsJsonResponse(
                null,
                BuildMessageValue::invoke(
                    (string)$this->notFoundStatus,
                    'Not Found with block component name: ' . $blockComponentName,
                    self::API_TYPE,
                    self::SOURCE
                ),
                $this->notFoundStatus,
                [],
                BuildResponseOptions::invoke()
            );
        }

        $blockVersionData = $this->getBlockVersionData($request);

        $blockVersion = new BlockVersionBasic(
            $blockVersionData['id'],
            $blockVersionData['properties'],
            $blockVersionData['createdByUserId'],
            $blockVersionData['createdReason'],
            $blockVersionData['createdDate']
        );

        $renderTags = $this->getBlockRenderTags->__invoke(
            $blockVersion,
            $request
        );

        $renderHtml = $this->renderBlock->__invoke(
            $blockVersion,
            $renderTags
        );

        $contentOnly = Property::getBool(
            $request->getQueryParams(),
            self::PARAM_CONTENT_ONLY,
            false
        );

        if (!$contentOnly) {
            $renderHtml = $this->wrapRenderedBlockVersion->__invoke(
                $renderHtml,
                $blockVersion
            );
        }

        $result = [];

        $result['renderHtml'] = $renderHtml;
        $result['renderTags'] = $renderTags;
        $result['blockVersion'] = $this->contentVersionToArray->__invoke(
            $blockVersion
        );

        return new ZrcmsJsonResponse(
            $result
        );
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return null|string
     */
    protected function getBlockComponentName(ServerRequestInterface $request)
    {
        $blockVersionProperties = $request->getParsedBody();

        return Property::getString(
            $blockVersionProperties,
            FieldsBlockVersion::BLOCK_COMPONENT_NAME,
            null
        );
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    protected function getBlockVersionData(
        ServerRequestInterface $request
    ): array {
        $blockVersionProperties = $request->getParsedBody();

        $blockVersionData['id'] = Property::getString(
            $request->getQueryParams(),
            self::PARAM_ID
        );

        $blockVersionData['properties'] = $blockVersionProperties;

        $blockVersionData['createdByUserId'] = $this->getUserIdByRequest->__invoke(
            $request
        );

        $blockVersionData['createdReason'] = 'zrcms-http-api-render-block';

        $blockVersionData['createdDate'] = null;

        return $blockVersionData;
    }
}
