<?php

namespace Zrcms\CoreApplication\Api;

/**
 * @todo NOT USED?
 *
 * @author James Jervis - https://github.com/jerv13
 */
class StringToHtmlClassName
{
    /**
     * @todo Make this more efficient and more complete
     *
     * @param $value
     *
     * @return string
     */
    public static function invoke(string $value): string
    {
        $value = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $value);
        $value = strtolower($value);
        $value = ltrim($value, '/');
        $value = ltrim($value, '\\');
        $value = str_replace('/', '_', $value);
        $value = str_replace('\\', '_', $value);

        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function __invoke(string $value): string
    {
        return self::invoke($value);
    }
}
