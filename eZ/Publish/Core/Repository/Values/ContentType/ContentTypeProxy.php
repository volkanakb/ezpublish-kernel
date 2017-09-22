<?php

/**
 * File containing the eZ\Publish\Core\Repository\Values\ContentType\ContentType class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\Repository\Values\ContentType;

use eZ\Publish\API\Repository\Values\ContentType\ContentType as APIContentType;
use eZ\Publish\Core\Repository\Values\ProxyTrait;

/**
 * This class represents a proxy for a content type value, using generator to lazy load the actual content type.
 *
 * @internal Meant for internal use by Repository, type hint against API object instead.
 */
class ContentTypeProxy extends APIContentType
{
    use ProxyTrait;

    /**
     * @var \eZ\Publish\API\Repository\Values\ContentType\ContentType
     */
    private $object;

    public function getContentTypeGroups()
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->getContentTypeGroups();
    }

    public function getFieldDefinitions()
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->getFieldDefinitions();
    }

    public function getFieldDefinition($fieldDefinitionIdentifier)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->getFieldDefinition($fieldDefinitionIdentifier);
    }

    public function getNames()
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->getNames();
    }

    public function getName($languageCode = null)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->getFieldDefinition($languageCode);
    }

    public function getDescriptions()
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->getDescriptions();
    }

    public function getDescription($languageCode = null)
    {
        if (isset($this->generator)) {
            $this->loadObject();
        }

        return $this->object->getDescription($languageCode);
    }
}
