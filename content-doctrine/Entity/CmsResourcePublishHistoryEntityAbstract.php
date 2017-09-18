<?php

namespace Zrcms\ContentDoctrine\Entity;

use Zrcms\Content\Exception\CmsResourceInvalid;
use Zrcms\Content\Model\ImmutableTrait;
use Zrcms\Content\Model\PropertiesTrait;
use Zrcms\Content\Model\TrackableTrait;

/**
 * A history record of the state of
 *
 * @author James Jervis - https://github.com/jerv13
 */
abstract class CmsResourcePublishHistoryEntityAbstract
{
    use ImmutableTrait;
    use TrackableTrait;
    use CmsResourcePublishHistoryEntityTrait;

    /**
     * @var null|string
     */
    protected $id = null;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var CmsResourceEntity
     */
    protected $cmsResourceEntity;

    /**
     * @var array
     */
    protected $cmsResourceProperties;

    /**
     * @var ContentEntity
     */
    protected $contentVersion;

    /**
     * @param string|null       $id
     * @param string            $action
     * @param CmsResourceEntity $cmsResourceEntity
     * @param string            $publishedByUserId
     * @param string            $publishReason
     */
    public function __construct(
        $id,
        string $action,
        CmsResourceEntity $cmsResourceEntity,
        string $publishedByUserId,
        string $publishReason
    ) {
        // Enforce immutability
        if (!$this->isNew()) {
            return;
        }
        $this->new = false;

        $this->id = $id;

        $this->action = $action;

        $this->cmsResourceEntity = $cmsResourceEntity;

        $this->cmsResourceProperties = $cmsResourceEntity->getProperties();

        $this->contentVersion = $cmsResourceEntity->getContentVersion();

        $this->setCreatedData(
            $publishedByUserId,
            $publishReason
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string)$this->id;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return ContentEntity
     */
    public function getContentVersion()
    {
        return $this->contentVersion;
    }

    /**
     * @return array
     */
    public function getCmsResourceProperties(): array
    {
        return $this->cmsResourceProperties;
    }

    /**
     * @return CmsResourceEntity
     */
    public function getCmsResource()
    {
        return $this->cmsResourceEntity;
    }

    /**
     * @param CmsResourceEntity $cmsResource
     *
     * @return void
     * @throws CmsResourceInvalid
     */
    protected function assertValidCmsResource($cmsResource)
    {
        if (!$cmsResource instanceof CmsResourceEntity) {
            throw new CmsResourceInvalid(
                'CmsResource must be instance of: ' . CmsResourceEntity::class
                . ' got: ' . var_export($cmsResource, true)
                . ' for: ' . get_class($this)
            );
        }
    }
}
