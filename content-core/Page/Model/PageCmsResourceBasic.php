<?php

namespace Zrcms\ContentCore\Page\Model;

use Zrcms\Content\Model\ContentVersion;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PageCmsResourceBasic extends PageCmsResourceAbstract implements PageCmsResource
{
    /**
     * @param null|string                $id
     * @param bool                       $published
     * @param ContentVersion|PageVersion $contentVersion
     * @param array                      $properties
     * @param string                     $createdByUserId
     * @param string                     $createdReason
     */
    public function __construct(
        $id,
        bool $published,
        ContentVersion $contentVersion,
        array $properties,
        string $createdByUserId,
        string $createdReason
    ) {
        parent::__construct(
            $id,
            $published,
            $contentVersion,
            $properties,
            $createdByUserId,
            $createdReason
        );
    }
}