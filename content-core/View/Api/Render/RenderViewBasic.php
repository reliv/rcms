<?php

namespace Zrcms\ContentCore\View\Api\Render;

use Zrcms\Content\Model\Content;
use Zrcms\ContentCore\Theme\Api\Render\RenderLayout;
use Zrcms\ContentCore\View\Fields\FieldsView;
use Zrcms\ContentCore\View\Model\ServiceAliasView;
use Zrcms\ServiceAlias\Api\GetServiceFromAlias;
use Zrcms\ServiceAlias\ServiceCheck;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderViewBasic implements RenderView
{
    /**
     * @var GetServiceFromAlias
     */
    protected $getServiceFromAlias;

    /**
     * @var string
     */
    protected $serviceAliasNamespace;

    /**
     * @var string
     */
    protected $defaultRenderServiceName;

    /**
     * @param GetServiceFromAlias $getServiceFromAlias
     * @param string              $defaultRenderServiceName
     */
    public function __construct(
        GetServiceFromAlias $getServiceFromAlias,
        string $defaultRenderServiceName = RenderViewLayout::class
    ) {
        $this->getServiceFromAlias = $getServiceFromAlias;
        $this->serviceAliasNamespace = ServiceAliasView::NAMESPACE_CONTENT_RENDERER;
        $this->defaultRenderServiceName = $defaultRenderServiceName;
    }

    /**
     * @param Content $view
     * @param array   $renderTags
     * @param array   $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        Content $view,
        array $renderTags,
        array $options = []
    ): string
    {
        $renderServiceAlias = $view->getProperty(
            FieldsView::RENDERER,
            ''
        );

        /** @var RenderLayout $render */
        $render = $this->getServiceFromAlias->__invoke(
            $this->serviceAliasNamespace,
            $renderServiceAlias,
            RenderLayout::class,
            $this->defaultRenderServiceName
        );

        ServiceCheck::assertNotSelfReference($this, $render);

        return $render->__invoke(
            $view,
            $renderTags,
            $options
        );
    }
}
