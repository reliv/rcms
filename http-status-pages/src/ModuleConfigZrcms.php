<?php

namespace Zrcms\HttpStatusPages;

use Zrcms\Core\Fields\FieldsComponentConfig;
use Zrcms\Core\Model\ComponentBasic;
use Zrcms\HttpStatusPages\Fields\FieldsHttpStatusPagesComponent;
use Zrcms\HttpStatusPages\Model\HttpStatusPagesComponent;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfigZrcms
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'zrcms-components' => [
                'basic.zrcms-http-status-pages' => 'app-config:zrcms-http-status-pages',
            ],

            /**
             * ===== ZRCMS Field Models =====
             * ['{model-name}' => '{model-class}']
             */
            'zrcms-fields-model' => [
                'http-status-pages-component' => FieldsHttpStatusPagesComponent::class,
            ],

            /**
             * ===== ZRCMS Field Model Extends =====
             * ['{model-name}' => '{extends-model-name}']
             */
            'zrcms-fields-model-extends' => [
                'http-status-pages-component' => 'component',
            ],

            /**
             * ===== ZRCMS Fields =====
             * ['{model-name}' => '{fields-config}']
             */
            'zrcms-fields' => [
                'http-status-pages-component' => [
                    [
                        'name' => fieldsHttpStatusPagesComponent::COMPONENT_CONFIG_READER,
                        'type' => 'zrcms-service',
                        'label' => 'Component Config Reader',
                        'required' => false,
                        'default' => 'json',
                        'options' => [],
                    ],
                    [
                        'name' => fieldsHttpStatusPagesComponent::COMPONENT_CLASS,
                        'type' => 'class',
                        'label' => 'Component Class',
                        'required' => false,
                        'default' => ComponentBasic::class,
                        'options' => [],
                    ],
                    [
                        'name' => fieldsHttpStatusPagesComponent::STATUS_TO_SITE_PATH_PROPERTY,
                        'type' => 'array',
                        'label' => 'Map of HTTP status to the path and a type',
                        'required' => false,
                        'default' => [],
                        'options' => [],
                    ],
                ],
            ],

            'zrcms-http-status-pages' => [
                FieldsComponentConfig::TYPE => 'basic',
                FieldsComponentConfig::NAME => HttpStatusPagesComponent::NAME,
                FieldsComponentConfig::MODULE_DIRECTORY => __DIR__ . '/..',
                FieldsComponentConfig::COMPONENT_CLASS
                => HttpStatusPagesComponent::class,

                /**
                 * Map of HTTP status to the path and a type
                 * 'status-to-site-page-path-property-map'
                 */
                FieldsHttpStatusPagesComponent::STATUS_TO_SITE_PATH_PROPERTY => [
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
        ];
    }
}