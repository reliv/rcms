<?php

namespace Zrcms\HttpApi\CmsResource;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zrcms\Core\Api\CmsResource\CmsResourceToArray;
use Zrcms\Core\Api\CmsResource\FindCmsResourcesBy;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HttpApiFindCmsResourcesBy
{
    const SOURCE = 'zrcms-find-cms-resources-by';

    protected $findCmsResourcesBy;
    protected $cmsResourceToArray;
    protected $name;

    /**
     * @param FindCmsResourcesBy $findCmsResourcesBy
     * @param CmsResourceToArray $cmsResourceToArray
     * @param string             $name
     */
    public function __construct(
        FindCmsResourcesBy $findCmsResourcesBy,
        CmsResourceToArray $cmsResourceToArray,
        string $name
    ) {
        $this->findCmsResourcesBy = $findCmsResourcesBy;
        $this->cmsResourceToArray = $cmsResourceToArray;
        $this->name = $name;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next = null
    ) {
        // @todo Write me
    }
}