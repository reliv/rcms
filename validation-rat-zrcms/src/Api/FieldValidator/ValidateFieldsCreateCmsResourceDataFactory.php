<?php

namespace Zrcms\ValidationRatZrcms\Api\FieldValidator;

use Psr\Container\ContainerInterface;
use Reliv\ValidationRat\Api\FieldValidator\ValidateFieldsHasOnlyRecognizedFields;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateFieldsCreateCmsResourceDataFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return ValidateFieldsCreateCmsResourceData
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        ContainerInterface $serviceContainer
    ) {
        return new ValidateFieldsCreateCmsResourceData(
            $serviceContainer->get(BuildFieldValidationResults::class),
            $serviceContainer->get(ValidateFieldsHasOnlyRecognizedFields::class),
            ValidateFieldsUpdateCmsResourceData::DEFAULT_INVALID_CODE
        );
    }
}
