<?php

namespace Zrcms\Core\Container\Api\Render;

use Psr\Container\ContainerInterface;
use Zrcms\Content\Api\Render\RenderContent;
use Zrcms\Content\Model\Content;
use Zrcms\Core\Container\Model\Container;
use Zrcms\Core\Container\Model\PropertiesContainer;

class RenderContainerBasic implements RenderContent
{
    /**
     * @var ContainerInterface
     */
    protected $serviceContainer;

    /**
     * @var string
     */
    protected $defaultRenderContainerServiceName;

    /**
     * @param ContainerInterface $serviceContainer
     * @param string             $defaultRenderContainerServiceName
     */
    public function __construct(
        $serviceContainer,
        string $defaultRenderContainerServiceName = RenderContainerRows::class
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->defaultRenderContainerServiceName = $defaultRenderContainerServiceName;
    }

    /**
     * @param Container|Content $Container
     * @param array             $renderData ['templateTag' => '{html}']
     * @param array             $options
     *
     * @return string
     */
    public function __invoke(
        Content $Container,
        array $renderData,
        array $options = []
    ): string
    {
        // Get version renderer or use default
        $renderContainerServiceName = $Container->getProperty(
            PropertiesContainer::RENDERER,
            $this->defaultRenderContainerServiceName
        );

        /** @var RenderContainer $renderContainerService */
        $renderContainerService = $this->serviceContainer->get(
            $renderContainerServiceName
        );

        return $renderContainerService->__invoke(
            $Container,
            $renderData
        );
    }
}
