<?php

namespace Zrcms\CorePageDoctrine\Api\CmsResource;

use Doctrine\ORM\EntityManager;
use Zrcms\Core\Exception\CmsResourceNotExists;
use Zrcms\Core\Exception\ContentVersionNotExists;
use Zrcms\Core\Model\CmsResource;
use Zrcms\CoreApplicationDoctrine\Api\CmsResource\UpsertCmsResource;
use Zrcms\CorePage\Api\CmsResource\UpsertPageDraftCmsResource as CoreUpsert;
use Zrcms\CorePage\Model\PageDraftCmsResource;
use Zrcms\CorePage\Model\PageDraftCmsResourceBasic;
use Zrcms\CorePage\Model\PageVersionBasic;
use Zrcms\CorePageDoctrine\Entity\PageDraftCmsResourceEntity;
use Zrcms\CorePageDoctrine\Entity\PageDraftCmsResourceHistoryEntity;
use Zrcms\CorePageDoctrine\Entity\PageVersionEntity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpsertPageDraftCmsResource extends UpsertCmsResource implements CoreUpsert
{
    /**
     * @param EntityManager $entityManager
     *
     * @throws \Zrcms\CoreApplicationDoctrine\Exception\InvalidEntityException
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        parent::__construct(
            $entityManager,
            PageDraftCmsResourceEntity::class,
            PageDraftCmsResourceHistoryEntity::class,
            PageVersionEntity::class,
            PageDraftCmsResourceBasic::class,
            PageVersionBasic::class,
            []
        );
    }

    /**
     * @param PageDraftCmsResource|CmsResource $cmsResource
     * @param string                           $contentVersionId
     * @param string                           $modifiedByUserId
     * @param string                           $modifiedReason
     * @param string|null                      $modifiedDate
     *
     * @return PageDraftCmsResource|CmsResource
     * @throws CmsResourceNotExists
     * @throws ContentVersionNotExists
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     * @throws \Zrcms\Core\Exception\TrackingInvalid
     */
    public function __invoke(
        CmsResource $cmsResource,
        string $contentVersionId,
        string $modifiedByUserId,
        string $modifiedReason,
        $modifiedDate = null
    ): CmsResource {
        return parent::__invoke(
            $cmsResource,
            $contentVersionId,
            $modifiedByUserId,
            $modifiedReason,
            $modifiedDate
        );
    }
}
