<?php

namespace Zrcms\HttpExpressive1\HttpApi\Acl;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zrcms\Acl\Api\IsAllowed;
use Zrcms\HttpExpressive1\Model\JsonApiResponse;
use Zrcms\HttpExpressive1\Model\ResponseCodes;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedCheckApi
{
    const SOURCE = 'zrcms-is-allowed-check-api';

    /**
     * @var IsAllowed
     */
    protected $isAllowed;

    /**
     * @var string
     */
    protected $aclOptions;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param IsAllowed $isAllowed
     * @param array     $aclOptions
     * @param string    $name
     */
    public function __construct(
        IsAllowed $isAllowed,
        array $aclOptions,
        string $name
    ) {
        $this->isAllowed = $isAllowed;
        $this->aclOptions = $aclOptions;
        $this->name = $name;
    }

    /**
     * __invoke
     *
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
        if (!$this->isAllowed->__invoke($request, $this->aclOptions)) {
            $apiMessages = [
                'type' => $this->name,
                'value' => 'Not allowed',
                'source' => self::SOURCE,
                'code' => ResponseCodes::NOT_ALLOWED,
                'primary' => true,
                'params' => []

            ];
            $response = new JsonApiResponse(
                [],
                $apiMessages,
                401
            );
        }

        return $next($request, $response);
    }
}