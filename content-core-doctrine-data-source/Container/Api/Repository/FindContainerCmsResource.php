<?php

namespace Zrcms\ContentCoreDoctrineDataSource\Container\Api\Repository;

use Doctrine\ORM\EntityManager;
use Zrcms\Content\Model\CmsResource;
use Zrcms\ContentCore\Container\Model\ContainerCmsResource;
use Zrcms\ContentCore\Container\Model\ContainerCmsResourceBasic;
use Zrcms\ContentCoreDoctrineDataSource\Container\Entity\ContainerCmsResourceEntity;
use Zrcms\ContentDoctrine\Api\Repository\FindCmsResource;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainerCmsResource
    extends FindCmsResource
    implements \Zrcms\Content\Api\Repository\FindCmsResource
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        parent::__construct(
            $entityManager,
            ContainerCmsResourceEntity::class,
            ContainerCmsResourceBasic::class
        );
    }

    /**
     * @param string $id
     * @param array  $options
     *
     * @return ContainerCmsResource|CmsResource|null
     */
    public function __invoke(
        string $id,
        array $options = []
    ) {
        return parent::__invoke(
            $id,
            $options
        );
    }
}