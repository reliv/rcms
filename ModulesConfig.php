<?php

namespace Zrcms;

use Zend\ConfigAggregator\ConfigAggregator;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModulesConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        $zrcmsModules = [
            // Low level
            new \Zrcms\Logger\ModuleConfig(),
            new \Zrcms\Cache\ModuleConfig(),
            new \Zrcms\Param\ModuleConfig(),
            new \Zrcms\Mustache\ModuleConfig(),
            new \Zrcms\ServiceAlias\ModuleConfig(),

            new \Zrcms\Core\ModuleConfig(),

            // Mid level
            new \Zrcms\Acl\ModuleConfig(),
            new \Zrcms\Locale\ModuleConfig(),
            new \Zrcms\User\ModuleConfig(),

            new \Zrcms\CoreApplication\ModuleConfig(),
            new \Zrcms\CoreApplicationDoctrine\ModuleConfig(),

            new \Zrcms\CoreAdminTools\ModuleConfig(),
            new \Zrcms\CoreBlock\ModuleConfig(),
            new \Zrcms\CoreContainer\ModuleConfig(),
            new \Zrcms\CoreContainerDoctrine\ModuleConfig(),
            new \Zrcms\CoreCountry\ModuleConfig(),
            new \Zrcms\CoreLanguage\ModuleConfig(),
            new \Zrcms\CorePage\ModuleConfig(),
            new \Zrcms\CorePageDoctrine\ModuleConfig(),
            new \Zrcms\CoreRedirect\ModuleConfig(),
            new \Zrcms\CoreRedirectDoctrine\ModuleConfig(),
            new \Zrcms\CoreSite\ModuleConfig(),
            new \Zrcms\CoreSiteDoctrine\ModuleConfig(),
            new \Zrcms\CoreTheme\ModuleConfig(),
            new \Zrcms\CoreThemeDoctrine\ModuleConfig(),
            new \Zrcms\CoreView\ModuleConfig(),

            new \Zrcms\Importer\ModuleConfig(),
            new \Zrcms\Install\ModuleConfig(),

            new \Zrcms\ViewAssets\ModuleConfig(),
            new \Zrcms\ViewHead\ModuleConfig(),
            new \Zrcms\ViewHtmlTags\ModuleConfig(),

            new \Zrcms\Http\ModuleConfig(),

            new \Zrcms\HttpChangeLog\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpChangeLog\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreAdminTools\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreAdminTools\ModuleConfigRoutes(),

            new \Zrcms\HttpCore\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCore\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreBlock\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreBlock\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreContainer\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreContainer\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreCountry\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreCountry\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreLanguage\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreLanguage\ModuleConfigRoutes(),

            new \Zrcms\HttpCorePage\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCorePage\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreRedirect\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreRedirect\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreSite\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreSite\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreTheme\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreTheme\ModuleConfigRoutes(),

            new \Zrcms\HttpCoreView\ModuleConfig(),
            // @todo Routes should NOT be included by default
            new \Zrcms\HttpCoreView\ModuleConfigRoutes(),

            new \Zrcms\HttpLocale\ModuleConfig(),
            new \Zrcms\HttpRcmApiLib\ModuleConfig(),
            new \Zrcms\HttpRedirect\ModuleConfig(),
            new \Zrcms\HttpSiteExists\ModuleConfig(),
            new \Zrcms\HttpStatusPages\ModuleConfig(),

            // @todo HttpTest should NOT be included by default
            new \Zrcms\HttpTest\ModuleConfig(),
            // @todo HttpTest Routes should NOT be included by default
            new \Zrcms\HttpTest\ModuleConfigRoutes(),

            new \Zrcms\HttpUser\ModuleConfig(),
            new \Zrcms\HttpViewRender\ModuleConfig(),

            // @todo XampleComponent should NOT be included by default
            new \Zrcms\XampleComponent\ModuleConfig(),
        ];

        $configManager = new ConfigAggregator(
            $zrcmsModules
        );

        return $configManager->getMergedConfig();
    }
}
