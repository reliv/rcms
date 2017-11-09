<?php

namespace Zrcms\ContentCore\Page\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildPageContainerVersionId
{
    /**
     * @param string $pageContentId
     * @param string $containerName
     *
     * @return string
     */
    public static function invoke(
        string $pageContentId,
        string $containerName
    ):string {
        return 'page.' . $pageContentId . '.container.' . $containerName;
    }
}
