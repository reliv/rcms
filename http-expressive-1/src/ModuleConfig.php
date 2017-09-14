<?php

namespace Zrcms\HttpExpressive1;

use Zrcms\Acl\Api\IsAllowedAny;
use Zrcms\ContentCore\Basic\Api\Component\ReadBasicComponentConfigApplicationConfig;
use Zrcms\ContentCore\Basic\Api\Repository\FindBasicComponent;
use Zrcms\ContentCore\Site\Api\GetSiteCmsResourceByRequest;
use Zrcms\ContentCore\View\Api\GetViewByRequest;
use Zrcms\ContentCore\View\Api\Render\GetViewLayoutTags;
use Zrcms\ContentCore\View\Api\Render\RenderView;
use Zrcms\ContentCore\View\Model\ServiceAliasView;
use Zrcms\ContentCoreConfigDataSource\Content\Model\ComponentRegistryFields;
use Zrcms\ContentRedirect\Api\Repository\FindRedirectCmsResourceBySiteRequestPath;
use Zrcms\HttpExpressive1\Api\GetStatusPage;
use Zrcms\HttpExpressive1\Api\GetStatusPageBasic;
use Zrcms\HttpExpressive1\Api\View\Render\GetViewLayoutMetaPageData;
use Zrcms\HttpExpressive1\HttpAlways\ContentRedirect;
use Zrcms\HttpExpressive1\HttpAlways\LocaleFromSite;
use Zrcms\HttpExpressive1\HttpAlways\ParamLogOut;
use Zrcms\HttpExpressive1\HttpAlways\RequestWithOriginalUri;
use Zrcms\HttpExpressive1\HttpAlways\RequestWithView;
use Zrcms\HttpExpressive1\HttpAlways\RequestWithViewRenderPage;
use Zrcms\HttpExpressive1\HttpRender\FinalHandler\NotFoundStatusPage;
use Zrcms\HttpExpressive1\HttpRender\Acl\IsAllowedToViewPage;
use Zrcms\HttpExpressive1\HttpRender\RenderPage;
use Zrcms\HttpExpressive1\HttpRender\ResponseMutatorNoop;
use Zrcms\HttpExpressive1\HttpRender\ResponseMutatorStatusPage;
use Zrcms\HttpExpressive1\Model\HttpExpressiveComponent;
use Zrcms\HttpExpressive1\Model\PropertiesHttpExpressiveComponent;
use Zrcms\Locale\Api\SetLocale;
use Zrcms\User\Api\LogOut;
use Zrcms\ViewHtmlTags\Api\Render\RenderTag;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * __invoke
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    /**
                     * Api ===========================================
                     */
                    GetViewLayoutMetaPageData::class => [
                        'arguments' => [
                            RenderTag::class,
                            // @todo Real ACL??
                            IsAllowedAny::class,
                            [
                                'literal' => [
                                    //IsAllowedRcmUser::OPTION_RESOURCE_ID => 'sites',
                                    //IsAllowedRcmUser::OPTION_PRIVILEGE => 'admin',
                                ]
                            ],
                        ],
                    ],

                    GetStatusPage::class => [
                        'class' => GetStatusPageBasic::class,
                        'arguments' => [
                            GetSiteCmsResourceByRequest::class,
                            FindBasicComponent::class,
                        ],
                    ],

                    /**
                     * HttpAlways ===========================================
                     */
                    ContentRedirect::class => [
                        'arguments' => [
                            GetSiteCmsResourceByRequest::class,
                            FindRedirectCmsResourceBySiteRequestPath::class,
                        ],
                    ],

                    LocaleFromSite::class => [
                        'arguments' => [
                            SetLocale::class,
                            GetSiteCmsResourceByRequest::class
                        ],
                    ],

                    ParamLogOut::class => [
                        'arguments' => [
                            LogOut::class,
                        ],
                    ],

                    RequestWithOriginalUri::class => [],

                    RequestWithViewRenderPage::class => [
                        'arguments' => [
                            GetViewLayoutTags::class,
                            RenderView::class,
                        ],
                    ],

                    RequestWithView::class => [
                        'arguments' => [
                            GetViewByRequest::class,
                        ],
                    ],

                    /**
                     * HttpRender ===========================================
                     */
                    /* ACL EXAMPLE *
                    IsAllowedCheck::class => [
                        'arguments' => [
                            IsAllowedRcmUser::class,
                            [
                                'literal' => [
                                    IsAllowedRcmUser::OPTION_RESOURCE_ID => 'admin',
                                    IsAllowedRcmUser::OPTION_PRIVILEGE => 'read'
                                ]
                            ]
                        ],
                    ],
                    /* */
                    IsAllowedToViewPage::class => [],

                    NotFoundStatusPage::class => [
                        'arguments' => [
                            GetStatusPage::class,
                            RenderPage::class,
                        ],
                    ],

                    RenderPage::class => [
                        'arguments' => [
                            GetViewByRequest::class,
                            GetViewLayoutTags::class,
                            RenderView::class,
                        ],
                    ],
                    ResponseMutatorNoop::class => [],
                    ResponseMutatorStatusPage::class => [
                        'arguments' => [
                            GetStatusPage::class,
                            RenderPage::class,
                        ],
                    ],
                ],
            ],

            'zrcms-components' => [
                'basic' => [
                    /* 'zrcms-http-expressive-1' */
                    HttpExpressiveComponent::NAME => [
                        ComponentRegistryFields::NAME
                        => HttpExpressiveComponent::NAME,

                        ComponentRegistryFields::CONFIG_LOCATION
                        => HttpExpressiveComponent::NAME,

                        ComponentRegistryFields::COMPONENT_CONFIG_READER
                        => ReadBasicComponentConfigApplicationConfig::SERVICE_ALIAS,

                        ComponentRegistryFields::COMPONENT_CLASS
                        => HttpExpressiveComponent::class,

                        /* Map of HTTP status to the name of a SiteVersion property with the corresponding path */
                        PropertiesHttpExpressiveComponent::STATUS_TO_SITE_PATH_PROPERTY => [
                            '401' => [
                                'path' => '/not-authorized',
                                'type' => 'render',
                            ],
                            '404' => [
                                'path' => '/not-found',
                                'type' => 'render',
                            ],
                        ],
                    ],
                ],
                'view-layout-tags' => [
                    GetViewLayoutMetaPageData::RENDER_TAG_META_PAGE_DATA
                    => __DIR__ . '/../config/meta-page-data',
                ],
            ],

            'zrcms-service-alias' => [
                ServiceAliasView::NAMESPACE_COMPONENT_VIEW_LAYOUT_TAGS_GETTER => [
                    GetViewLayoutMetaPageData::RENDER_TAG_META_PAGE_DATA
                    => GetViewLayoutMetaPageData::class
                ],
            ],
        ];
    }
}
