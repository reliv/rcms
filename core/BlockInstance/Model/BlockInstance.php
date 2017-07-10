<?php

namespace Zrcms\Core\BlockInstance\Model;

use Zrcms\Tracking\Model\Trackable;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface BlockInstance extends Trackable
{
    /**
     * @return string
     */
    public function getUid(): string;

    /**
     * <identifier>
     *
     * @return string
     */
    public function getUri(): string;

    /**
     * @return string
     */
    public function getBlockName(): string;

    /**
     * @return array The instance config for this block instance.
     * This is what admins can edit in the CMS
     */
    public function getConfig(): array;

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function getConfigValue(string $name, $default = null);

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function getDataValue(string $name, $default = null);

    /**
     * @return array
     */
    public function getSystemProperties(): array;

    /**
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function getSystemProperty(string $name, $default = null);
}
