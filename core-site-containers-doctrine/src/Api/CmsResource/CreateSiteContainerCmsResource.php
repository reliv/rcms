<?php

namespace Zrcms\CoreSiteContainerDoctrine\Api\CmsResource;

use Doctrine\ORM\EntityManager;
use Zrcms\Core\Exception\CmsResourceExists;
use Zrcms\Core\Exception\ContentVersionNotExists;
use Zrcms\Core\Model\CmsResource;
use Zrcms\CoreApplicationDoctrine\Api\CmsResource\CreateCmsResource;
use Zrcms\CoreSiteContainer\Api\CmsResource\CreateSiteContainerCmsResource as ParentCreate;
use Zrcms\CoreContainer\Model\ContainerCmsResource;
use Zrcms\CoreContainer\Model\ContainerCmsResourceBasic;
use Zrcms\CoreContainer\Model\ContainerVersionBasic;
use Zrcms\CoreSiteContainerDoctrine\Entity\SiteContainerCmsResourceEntity;
use Zrcms\CoreSiteContainerDoctrine\Entity\SiteContainerCmsResourceHistoryEntity;
use Zrcms\CoreSiteContainerDoctrine\Entity\SiteContainerVersionEntity;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateSiteContainerCmsResource extends CreateCmsResource implements ParentCreate
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
            SiteContainerCmsResourceEntity::class,
            SiteContainerCmsResourceHistoryEntity::class,
            SiteContainerVersionEntity::class,
            ContainerCmsResourceBasic::class,
            ContainerVersionBasic::class,
            []
        );
    }

    /**
     * @param null|string $id
     * @param bool        $published
     * @param string      $contentVersionId
     * @param string      $modifiedByUserId
     * @param string      $modifiedReason
     * @param null|string $modifiedDate
     *
     * @return ContainerCmsResource|CmsResource
     * @throws CmsResourceExists
     * @throws ContentVersionNotExists
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function __invoke(
        $id,
        bool $published,
        string $contentVersionId,
        string $modifiedByUserId,
        string $modifiedReason,
        $modifiedDate = null
    ): CmsResource {
        return parent::__invoke(
            $id,
            $published,
            $contentVersionId,
            $modifiedByUserId,
            $modifiedReason,
            $modifiedDate
        );
    }
}
