<?php

namespace Zrcms\Importer\Api;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Reliv\ArrayProperties\Property;
use Reliv\Json\Json;
use Zrcms\CoreContainer\Api\CmsResource\UpsertContainerCmsResource;
use Zrcms\CoreContainer\Api\Content\InsertContainerVersion;
use Zrcms\CoreContainer\Fields\FieldsContainerVersion;
use Zrcms\CoreContainer\Model\ContainerCmsResourceBasic;
use Zrcms\CoreContainer\Model\ContainerVersionBasic;
use Zrcms\CorePage\Api\CmsResource\UpsertPageCmsResource;
use Zrcms\CorePage\Api\CmsResource\UpsertPageTemplateCmsResource;
use Zrcms\CorePage\Api\Content\InsertPageVersion;
use Zrcms\CorePage\Fields\FieldsPageVersion;
use Zrcms\CorePage\Model\PageCmsResourceBasic;
use Zrcms\CorePage\Model\PageTemplateCmsResourceBasic;
use Zrcms\CorePage\Model\PageVersionBasic;
use Zrcms\CoreRedirect\Api\CmsResource\FindRedirectCmsResource;
use Zrcms\CoreRedirect\Api\CmsResource\UpsertRedirectCmsResource;
use Zrcms\CoreRedirect\Api\Content\InsertRedirectVersion;
use Zrcms\CoreRedirect\Model\RedirectCmsResourceBasic;
use Zrcms\CoreRedirect\Model\RedirectVersionBasic;
use Zrcms\CoreSite\Api\CmsResource\FindSiteCmsResource;
use Zrcms\CoreSite\Api\CmsResource\UpsertSiteCmsResource;
use Zrcms\CoreSite\Api\Content\InsertSiteVersion;
use Zrcms\CoreSite\Model\SiteCmsResource;
use Zrcms\CoreSite\Model\SiteCmsResourceBasic;
use Zrcms\CoreSite\Model\SiteVersionBasic;

class Import
{
    const OPTION_LOGGER = 'logger';
    const OPTION_SLEEP = 'sleep';
    const OPTION_SKIP_DUPLICATES = 'skip-duplicates';
    protected $defaultSleep = 0;
    protected $defaultSkipDuplicates = true;

    protected $findSiteCmsResource;
    protected $insertSiteVersion;
    protected $upsertSiteCmsResource;
    protected $insertPageVersion;
    protected $upsertPageCmsResource;
    protected $upsertPageTemplateCmsResource;
    protected $insertContainerVersion;
    protected $upsertContainerCmsResource;
    protected $findRedirectCmsResource;
    protected $insertRedirectVersion;
    protected $upsertRedirectCmsResource;

    /**
     * @param FindSiteCmsResource           $findSiteCmsResource
     * @param InsertSiteVersion             $insertSiteVersion
     * @param UpsertSiteCmsResource         $upsertSiteCmsResource
     * @param InsertPageVersion             $insertPageVersion
     * @param UpsertPageCmsResource         $upsertPageCmsResource
     * @param UpsertPageTemplateCmsResource $upsertPageTemplateCmsResource
     * @param InsertContainerVersion        $insertContainerVersion
     * @param UpsertContainerCmsResource    $upsertContainerCmsResource
     * @param FindRedirectCmsResource       $findRedirectCmsResource
     * @param InsertRedirectVersion         $insertRedirectVersion
     * @param UpsertRedirectCmsResource     $upsertRedirectCmsResource
     */
    public function __construct(
        FindSiteCmsResource $findSiteCmsResource,
        InsertSiteVersion $insertSiteVersion,
        UpsertSiteCmsResource $upsertSiteCmsResource,
        InsertPageVersion $insertPageVersion,
        UpsertPageCmsResource $upsertPageCmsResource,
        UpsertPageTemplateCmsResource $upsertPageTemplateCmsResource,
        InsertContainerVersion $insertContainerVersion,
        UpsertContainerCmsResource $upsertContainerCmsResource,
        FindRedirectCmsResource $findRedirectCmsResource,
        InsertRedirectVersion $insertRedirectVersion,
        UpsertRedirectCmsResource $upsertRedirectCmsResource
    ) {
        $this->findSiteCmsResource = $findSiteCmsResource;
        $this->insertSiteVersion = $insertSiteVersion;
        $this->upsertSiteCmsResource = $upsertSiteCmsResource;
        $this->insertPageVersion = $insertPageVersion;
        $this->upsertPageCmsResource = $upsertPageCmsResource;
        $this->upsertPageTemplateCmsResource = $upsertPageTemplateCmsResource;
        $this->insertContainerVersion = $insertContainerVersion;
        $this->upsertContainerCmsResource = $upsertContainerCmsResource;
        $this->findRedirectCmsResource = $findRedirectCmsResource;
        $this->insertRedirectVersion = $insertRedirectVersion;
        $this->upsertRedirectCmsResource = $upsertRedirectCmsResource;
    }

    /**
     * This imports sites, pages, and containers from an exported JSON array into ZRCMS.
     *
     * @param string $json
     * @param string $createdByUserId
     * @param array  $options
     *
     * @return void
     * @throws \Exception
     */
    public function __invoke(
        string $json,
        string $createdByUserId,
        array $options = []
    ) {
        $startTime = time();

        $data = Json::decode(
            $json,
            true
        );

        $createdReason = 'Import script ' . get_class($this);

        $this->createRedirects(
            $data['redirects'],
            $createdByUserId,
            $createdReason,
            $options
        );

        $this->createSites(
            $data,
            $createdByUserId,
            $createdReason,
            $options
        );

        $this->log(
            LogLevel::INFO,
            'Import COMPLETE in ' . (time() - $startTime) . ' seconds',
            $options
        );
    }

    /**
     * @param array $options
     *
     * @return void
     */
    protected function sleep(array $options)
    {
        $sleep = Property::getInt(
            $options,
            self::OPTION_SLEEP,
            $this->defaultSleep
        );

        if ($sleep) {
            usleep($sleep * 1000000);
        }
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    protected function skipDuplicates(array $options): bool
    {
        return Property::getBool(
            $options,
            self::OPTION_SKIP_DUPLICATES,
            $this->defaultSkipDuplicates
        );
    }

    /**
     * @param string $level
     * @param string $message
     * @param array  $options
     *
     * @return void
     */
    protected function log(
        string $level,
        string $message,
        array $options
    ) {
        $logger = Property::get(
            $options,
            self::OPTION_LOGGER
        );

        if ($logger instanceof LoggerInterface) {
            $logger->log($level, $message);
            $this->sleep($options);
        }
    }

    /**
     * @param array  $data
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $options
     *
     * @return void
     * @throws \Zrcms\Core\Exception\CmsResourceNotExists
     * @throws \Zrcms\Core\Exception\ContentVersionNotExists
     */
    protected function createSites(
        array $data,
        string $createdByUserId,
        string $createdReason,
        array $options = []
    ) {
        $this->log(
            LogLevel::INFO,
            'Import Sites:',
            $options
        );

        foreach ($data['sites'] as $site) {
            $existing = $this->findSiteCmsResource->__invoke(
                $site['id']
            );

            if (!empty($existing) && $this->skipDuplicates($options)) {
                $this->log(
                    LogLevel::WARNING,
                    'SKIP Site - Already exists: ('
                    . 'siteId: ' . $site['id']
                    . ', host: ' . $site['properties']['host']
                    . ')',
                    $options
                );
                continue;
            }

            $this->log(
                LogLevel::INFO,
                'Import Site: ('
                . 'siteId: ' . $site['id']
                . ', host: ' . $site['properties']['host']
                . ')',
                $options
            );

            $published = Property::getBool($site, 'published', true);

            $version = new SiteVersionBasic(
                null,
                $site['properties'],
                $createdByUserId,
                $createdReason
            );

            $version = $this->insertSiteVersion->__invoke(
                $version
            );

            $publishedSiteCmsResource = $this->upsertSiteCmsResource->__invoke(
                new SiteCmsResourceBasic(
                    $site['id'],
                    $published,
                    $version,
                    $createdByUserId,
                    $createdReason
                ),
                $version->getId(),
                $createdByUserId,
                $createdReason
            );

            $this->createPages(
                $publishedSiteCmsResource,
                $site['pages'],
                $createdByUserId,
                $createdReason,
                $options
            );

            $this->createPageTemplates(
                $publishedSiteCmsResource,
                $site['pageTemplates'],
                $createdByUserId,
                $createdReason,
                $options
            );

            $this->createContainers(
                $publishedSiteCmsResource,
                $site['containers'],
                $createdByUserId,
                $createdReason,
                $options
            );

            if (!$published) {
                $this->log(
                    LogLevel::WARNING,
                    'UNPUBLISH SiteCmsResource ID: ' . $publishedSiteCmsResource->getId(),
                    $options
                );
            }
        }
    }

    /**
     * @param SiteCmsResource $siteCmsResource
     * @param array           $pages
     * @param string          $createdByUserId
     * @param string          $createdReason
     * @param array           $options
     *
     * @return void
     * @throws \Zrcms\Core\Exception\CmsResourceNotExists
     * @throws \Zrcms\Core\Exception\ContentVersionNotExists
     */
    protected function createPages(
        SiteCmsResource $siteCmsResource,
        array $pages,
        string $createdByUserId,
        string $createdReason,
        array $options = []
    ) {
        $this->log(
            LogLevel::INFO,
            'Import Pages: ('
            . 'siteId: ' . $siteCmsResource->getId()
            . ')',
            $options
        );

        foreach ($pages as $page) {
            $this->log(
                LogLevel::INFO,
                'Import Page: ' . $page['properties']['path'] . ' ID: ' . $page['id'],
                $options
            );

            $page['properties'][FieldsPageVersion::SITE_CMS_RESOURCE_ID] = $siteCmsResource->getId();

            $published = Property::getBool($page, 'published', true);

            $version = new PageVersionBasic(
                null,
                $page['properties'],
                $createdByUserId,
                $createdReason
            );

            $version = $this->insertPageVersion->__invoke(
                $version
            );

            $publishedPageCmsResource = $this->upsertPageCmsResource->__invoke(
                new PageCmsResourceBasic(
                    $page['id'],
                    $published,
                    $version,
                    $createdByUserId,
                    $createdReason
                ),
                $version->getId(),
                $createdByUserId,
                $createdReason
            );

            if (!$published) {
                $this->log(
                    LogLevel::WARNING,
                    'UNPUBLISH PageCmsResource ID: ' . $publishedPageCmsResource->getId(),
                    $options
                );
            }
        }
    }

    /**
     * @param SiteCmsResource $siteCmsResource
     * @param array           $pageTemplates
     * @param string          $createdByUserId
     * @param string          $createdReason
     * @param array           $options
     *
     * @return void
     * @throws \Zrcms\Core\Exception\CmsResourceNotExists
     * @throws \Zrcms\Core\Exception\ContentVersionNotExists
     */
    protected function createPageTemplates(
        SiteCmsResource $siteCmsResource,
        array $pageTemplates,
        string $createdByUserId,
        string $createdReason,
        array $options = []
    ) {
        $this->log(
            LogLevel::INFO,
            'Import Page Templates: ('
            . 'siteId: ' . $siteCmsResource->getId()
            . ')',
            $options
        );

        foreach ($pageTemplates as $pageTemplate) {
            $this->log(
                LogLevel::INFO,
                'Import Page Template: ' . $pageTemplate['properties']['path'],
                $options
            );

            $pageTemplate['properties'][FieldsPageVersion::SITE_CMS_RESOURCE_ID] = $siteCmsResource->getId();

            $published = Property::getBool($pageTemplate, 'published', true);

            $version = new PageVersionBasic(
                null,
                $pageTemplate['properties'],
                $createdByUserId,
                $createdReason
            );

            $version = $this->insertPageVersion->__invoke(
                $version
            );

            $publishedPageTemplateCmsResource = $this->upsertPageTemplateCmsResource->__invoke(
                new PageTemplateCmsResourceBasic(
                    $pageTemplate['id'],
                    $published,
                    $version,
                    $createdByUserId,
                    $createdReason
                ),
                $version->getId(),
                $createdByUserId,
                $createdReason
            );

            if (!$published) {
                $this->log(
                    LogLevel::WARNING,
                    'UNPUBLISH PageTemplateCmsResource ID: ' . $publishedPageTemplateCmsResource->getId(),
                    $options
                );
            }
        }
    }

    /**
     * @param SiteCmsResource $siteCmsResource
     * @param array           $containers
     * @param string          $createdByUserId
     * @param string          $createdReason
     * @param array           $options
     *
     * @return void
     * @throws \Zrcms\Core\Exception\CmsResourceNotExists
     * @throws \Zrcms\Core\Exception\ContentVersionNotExists
     */
    protected function createContainers(
        SiteCmsResource $siteCmsResource,
        array $containers,
        string $createdByUserId,
        string $createdReason,
        array $options = []
    ) {
        $this->log(
            LogLevel::INFO,
            'Import Containers: ('
            . 'siteId: ' . $siteCmsResource->getId()
            . ')',
            $options
        );

        foreach ($containers as $container) {
            $this->log(
                LogLevel::INFO,
                'Import Container: ' . $container['properties']['path'],
                $options
            );

            $container['properties'][FieldsContainerVersion::SITE_CMS_RESOURCE_ID] = $siteCmsResource->getId();

            $published = Property::getBool($container, 'published', true);

            $version = new ContainerVersionBasic(
                null,
                $container['properties'],
                $createdByUserId,
                $createdReason
            );

            $version = $this->insertContainerVersion->__invoke(
                $version
            );

            $publishedContainerCmsResource = $this->upsertContainerCmsResource->__invoke(
                new ContainerCmsResourceBasic(
                    $container['id'],
                    $published,
                    $version,
                    $createdByUserId,
                    $createdReason
                ),
                $version->getId(),
                $createdByUserId,
                $createdReason
            );

            if (!$published) {
                $this->log(
                    LogLevel::WARNING,
                    'UNPUBLISH ContainerCmsResource ID: ' . $publishedContainerCmsResource->getId(),
                    $options
                );
            }
        }
    }

    /**
     * @param SiteCmsResource $siteCmsResource
     * @param array           $layouts
     * @param string          $createdByUserId
     * @param string          $createdReason
     * @param array           $options
     *
     * @return void
     */
    protected function createLayouts(
        SiteCmsResource $siteCmsResource,
        array $layouts,
        string $createdByUserId,
        string $createdReason,
        array $options = []
    ) {
        // @todo
    }

    /**
     * @param array  $redirects
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $options
     *
     * @return void
     * @throws \Zrcms\Core\Exception\CmsResourceNotExists
     * @throws \Zrcms\Core\Exception\ContentVersionNotExists
     */
    protected function createRedirects(
        array $redirects,
        string $createdByUserId,
        string $createdReason,
        array $options = []
    ) {
        $this->log(
            LogLevel::INFO,
            'Import Redirects: ',
            $options
        );

        foreach ($redirects as $redirect) {
            $existing = $this->findRedirectCmsResource->__invoke(
                $redirect['id']
            );

            if (!empty($existing) && $this->skipDuplicates($options)) {
                $this->log(
                    LogLevel::WARNING,
                    'SKIP redirect - Already exists: ('
                    . 'redirect Id: ' . $redirect['id']
                    . ')',
                    $options
                );
                continue;
            }

            $this->log(
                LogLevel::INFO,
                'Import Redirect: ' . $redirect['properties']['requestPath'],
                $options
            );

            $published = Property::getBool($redirect, 'published', true);

            $version = new RedirectVersionBasic(
                null,
                $redirect['properties'],
                $createdByUserId,
                $createdReason
            );

            $version = $this->insertContainerVersion->__invoke(
                $version
            );

            $publishedRedirectCmsResource = $this->upsertRedirectCmsResource->__invoke(
                new RedirectCmsResourceBasic(
                    $redirect['id'],
                    $published,
                    $version,
                    $createdByUserId,
                    $createdReason
                ),
                $version->getId(),
                $createdByUserId,
                $createdReason
            );

            if (!$published) {
                $this->log(
                    LogLevel::WARNING,
                    'UNPUBLISH RedirectCmsResource ID: ' . $publishedRedirectCmsResource->getId(),
                    $options
                );
            }
        }
    }
}
