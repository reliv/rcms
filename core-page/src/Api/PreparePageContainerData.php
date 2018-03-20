<?php

namespace Zrcms\CorePage\Api;

use Reliv\ArrayProperties\Property;
use Zrcms\CoreApplication\Api\GetGuidV4;
use Zrcms\CoreContainer\Api\PrepareBlockVersionsData;
use Zrcms\CoreContainer\Fields\FieldsContainerVersion;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PreparePageContainerData
{
    /**
     * @param string $pageContentId
     * @param string $siteCmsResourceId
     * @param array  $containersData
     *
     * @return array
     */
    public static function invoke(
        $pageContentId,
        string $siteCmsResourceId,
        array $containersData
    ): array {
        if (empty($pageContentId)) {
            $pageContentId = GetGuidV4::invoke();
        }

        foreach ($containersData as $containerName => $containerData) {
            $blockVersions = Property::getArray(
                $containerData,
                FieldsContainerVersion::BLOCK_VERSIONS,
                []
            );
            $containersData[$containerName][FieldsContainerVersion::NAME] = $containerName;
            $containersData[$containerName][FieldsContainerVersion::SITE_CMS_RESOURCE_ID] = $siteCmsResourceId;
            $containersData[$containerName][FieldsContainerVersion::BLOCK_VERSIONS] = PrepareBlockVersionsData::invoke(
                $blockVersions,
                BuildPageContainerVersionId::invoke($pageContentId, $containerName)
            );
        }

        return $containersData;
    }
}
