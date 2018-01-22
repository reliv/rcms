<?php

namespace Zrcms\HttpApi;

use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;
use Zrcms\HttpApi\Acl\HttpApiIsAllowedDynamic;
use Zrcms\HttpApi\Acl\HttpApiIsAllowedFindComponent;
use Zrcms\HttpApi\CmsResource\HttpApiFindCmsResourceDynamic;
use Zrcms\HttpApi\CmsResource\HttpApiFindCmsResourcesByDynamic;
use Zrcms\HttpApi\CmsResource\HttpApiFindCmsResourcesPublishedDynamic;
use Zrcms\HttpApi\CmsResource\HttpApiUpsertCmsResourceDynamic;
use Zrcms\HttpApi\Component\HttpApiFindComponent;
use Zrcms\HttpApi\Params\HttpApiLimit;
use Zrcms\HttpApi\Params\HttpApiOffset;
use Zrcms\HttpApi\Params\HttpApiOrderBy;
use Zrcms\HttpApi\Params\HttpApiWhere;
use Zrcms\HttpApi\Validate\HttpApiValidateFieldsDynamic;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfigRoutes
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'routes' => [
                /**
                 * FindResource
                 *
                 * zrcms-implementation = site
                 * zrcms-api = find-cms-resource
                 */
                'zrcms.api.cms-resource.{zrcms-implementation}.find.{id}' => [
                    'name' => 'zrcms.api.cms-resource.{zrcms-implementation}.find.{id}',
                    'path' => '/zrcms/api/cms-resource/{zrcms-implementation}/find/{id}',
                    'middleware' => [
                        'acl' => HttpApiIsAllowedDynamic::class,
                        // @todo 'validate-id' => HttpApiValidateIdAttributeDynamic::class,
                        'api' => HttpApiFindCmsResourceDynamic::class,
                    ],
                    'options' => [
                        'zrcms-api' => 'find-cms-resource'
                    ],
                    'allowed_methods' => ['GET'],
                ],
                /**
                 * FindResourcesBy
                 *
                 * zrcms-implementation = site
                 * zrcms-api = find-cms-resources-by
                 */
                'zrcms.api.cms-resources.{zrcms-implementation}.find-by' => [
                    'name' => 'zrcms.api.cms-resources.{zrcms-implementation}.find-by',
                    'path' => '/zrcms/api/cms-resources/{zrcms-implementation}/find-by',
                    'middleware' => [
                        'acl' => HttpApiIsAllowedDynamic::class,
                        'param-limit' => HttpApiLimit::class,
                        'param-offset' => HttpApiOffset::class,
                        'param-order-by' => HttpApiOrderBy::class,
                        //'validate-param-order-by' => XXX::class,
                        'param-where' => HttpApiWhere::class,
                        //'validate-param-where' => XXX::class,
                        'api' => HttpApiFindCmsResourcesByDynamic::class,
                    ],
                    'options' => [
                        'zrcms-api' => 'find-cms-resources-by'
                    ],
                    'allowed_methods' => ['GET'],
                ],

                /**
                 * FindCmsResourcesPublished
                 *
                 * zrcms-implementation = site
                 * zrcms-api = find-cms-resources-published
                 */
                'zrcms.api.cms-resources.{zrcms-implementation}.find-published' => [
                    'name' => 'zrcms.api.cms-resources.{zrcms-implementation}.find-published',
                    'path' => '/zrcms/api/cms-resources/{zrcms-implementation}/find-published',
                    'middleware' => [
                        'acl' => HttpApiIsAllowedDynamic::class,
                        'param-limit' => HttpApiLimit::class,
                        'param-offset' => HttpApiOffset::class,
                        'param-order-by' => HttpApiOrderBy::class,
                        //'validate-param-order-by' => XXX::class,
                        'param-where' => HttpApiWhere::class,
                        //'validate-param-where' => XXX::class,
                        'api' => HttpApiFindCmsResourcesPublishedDynamic::class,
                    ],
                    'options' => [
                        'zrcms-api' => 'find-cms-resources-published'
                    ],
                    'allowed_methods' => ['GET'],
                ],

                /**
                 * UpsertCmsResource
                 *
                 * zrcms-implementation = site
                 * zrcms-api = upsert-cms-resource
                 */
                'zrcms.api.cms-resource.{zrcms-implementation}.upsert' => [
                    'name' => 'zrcms.api.cms-resource.{zrcms-implementation}.upsert',
                    'path' => '/zrcms/api/cms-resource/{zrcms-implementation}/upsert',
                    'middleware' => [
                        'acl' => HttpApiIsAllowedDynamic::class,
                        'body-parser' => BodyParamsMiddleware::class,
                        'validate-fields' => HttpApiValidateFieldsDynamic::class,
                        'api' => HttpApiUpsertCmsResourceDynamic::class,
                    ],
                    'options' => [
                        'zrcms-api' => 'upsert-cms-resource'
                    ],
                    'allowed_methods' => ['POST', 'PUT'],
                ],

                /**
                 * FindComponent
                 */
                'zrcms.api.component.{type}.find.{name}' => [
                    'name' => 'zrcms.api.component.{type}.find.{name}',
                    'path' => '/zrcms/api/component/{type}/find/{name}',
                    'middleware' => [
                        'acl' => HttpApiIsAllowedFindComponent::class,
                        'api' => HttpApiFindComponent::class,
                    ],
                    'options' => [
                    ],
                    'allowed_methods' => ['GET'],
                ],
            ],
        ];
    }
}
