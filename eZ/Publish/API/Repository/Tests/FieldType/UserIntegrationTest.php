<?php

/**
 * File contains: eZ\Publish\API\Repository\Tests\FieldType\UserIntegrationTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\API\Repository\Tests\FieldType;

use eZ\Publish\Core\FieldType\User\Value as UserValue;
use eZ\Publish\Core\Repository\Values\User\User;
use eZ\Publish\API\Repository\Values\Content\Field;

/**
 * Integration test for use field type.
 *
 * @group integration
 * @group field-type
 */
class UserIntegrationTest extends BaseIntegrationTest
{
    /**
     * Get name of tested field type.
     *
     * @return string
     */
    public function getTypeName()
    {
        return 'ezuser';
    }

    /**
     * Get expected settings schema.
     *
     * @return array
     */
    public function getSettingsSchema()
    {
        return array();
    }

    /**
     * Get a valid $fieldSettings value.
     *
     * @return mixed
     */
    public function getValidFieldSettings()
    {
        return array();
    }

    /**
     * Get $fieldSettings value not accepted by the field type.
     *
     * @return mixed
     */
    public function getInvalidFieldSettings()
    {
        return array(
            'somethingUnknown' => 0,
        );
    }

    /**
     * Get expected validator schema.
     *
     * @return array
     */
    public function getValidatorSchema()
    {
        return array();
    }

    /**
     * Get a valid $validatorConfiguration.
     *
     * @return mixed
     */
    public function getValidValidatorConfiguration()
    {
        return array();
    }

    /**
     * Get $validatorConfiguration not accepted by the field type.
     *
     * @return mixed
     */
    public function getInvalidValidatorConfiguration()
    {
        return array(
            'unknown' => array('value' => 23),
        );
    }

    /**
     * Get initial field externals data.
     *
     * @return array
     */
    public function getValidCreationFieldData()
    {
        return new UserValue(array('login' => 'hans'));
    }

    /**
     * Get name generated by the given field type (either via Nameable or fieldType->getName()).
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'hans';
    }

    /**
     * Asserts that the field data was loaded correctly.
     *
     * Asserts that the data provided by {@link getValidCreationFieldData()}
     * was stored and loaded correctly.
     *
     * @param Field $field
     */
    public function assertFieldDataLoadedCorrect(Field $field)
    {
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\FieldType\\User\\Value',
            $field->value
        );

        $expectedData = array(
            'hasStoredLogin' => true,
            'login' => 'hans',
            'email' => 'hans@example.com',
            'passwordHashType' => User::PASSWORD_HASH_PHP_DEFAULT,
            'enabled' => true,
        );

        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
        );

        $this->assertNotNull($field->value->contentId);
    }

    /**
     * Get field data which will result in errors during creation.
     *
     * This is a PHPUnit data provider.
     *
     * The returned records must contain of an error producing data value and
     * the expected exception class (from the API or SPI, not implementation
     * specific!) as the second element. For example:
     *
     * <code>
     * array(
     *      array(
     *          new DoomedValue( true ),
     *          'eZ\\Publish\\API\\Repository\\Exceptions\\ContentValidationException'
     *      ),
     *      // ...
     * );
     * </code>
     *
     * @return array[]
     */
    public function provideInvalidCreationFieldData()
    {
        return array();
    }

    public function testCreateContentFails($failingValue = null, $expectedException = null)
    {
        $this->markTestSkipped('Values are ignored on creation.');
    }

    /**
     * Get update field externals data.
     *
     * @return array
     */
    public function getValidUpdateFieldData()
    {
        return new UserValue(
            array(
                'login' => 'change', // Change is intended to not get through
                'email' => 'change', // Change is intended to not get through
                'passwordHash' => 'change', // Change is intended to not get through
                'passwordHashType' => 'change', // Change is intended to not get through
                'enabled' => 'change', // Change is intended to not get through
            )
        );
    }

    /**
     * Get externals updated field data values.
     *
     * This is a PHPUnit data provider
     *
     * @return array
     */
    public function assertUpdatedFieldDataLoadedCorrect(Field $field)
    {
        // No update possible through field type
        $this->assertFieldDataLoadedCorrect($field);
    }

    /**
     * Get field data which will result in errors during update.
     *
     * This is a PHPUnit data provider.
     *
     * The returned records must contain of an error producing data value and
     * the expected exception class (from the API or SPI, not implementation
     * specific!) as the second element. For example:
     *
     * <code>
     * array(
     *      array(
     *          new DoomedValue( true ),
     *          'eZ\\Publish\\API\\Repository\\Exceptions\\ContentValidationException'
     *      ),
     *      // ...
     * );
     * </code>
     *
     * @return array[]
     */
    public function provideInvalidUpdateFieldData()
    {
        return array(
            array(
                null,
                'eZ\\Publish\\Core\\Base\\Exceptions\\ContentValidationException',
            ),
            // @todo: Define more failure cases ...
        );
    }

    /**
     * Asserts the the field data was loaded correctly.
     *
     * Asserts that the data provided by {@link getValidCreationFieldData()};
     * was copied and loaded correctly.
     *
     * @param Field $field
     */
    public function assertCopiedFieldDataLoadedCorrectly(Field $field)
    {
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\FieldType\\User\\Value',
            $field->value
        );

        $expectedData = array(
            'hasStoredLogin' => false,
            'contentId' => null,
            'login' => null,
            'email' => null,
            'passwordHash' => null,
            'passwordHashType' => null,
            'enabled' => false,
            'maxLogin' => null,
        );

        $this->assertPropertiesCorrect(
            $expectedData,
            $field->value
        );
    }

    /**
     * Get data to test to hash method.
     *
     * This is a PHPUnit data provider
     *
     * The returned records must have the the original value assigned to the
     * first index and the expected hash result to the second. For example:
     *
     * <code>
     * array(
     *      array(
     *          new MyValue( true ),
     *          array( 'myValue' => true ),
     *      ),
     *      // ...
     * );
     * </code>
     *
     * @return array
     */
    public function provideToHashData()
    {
        return array(
            array(
                new UserValue(array('login' => 'hans')),
                array(
                    'login' => 'hans',
                    'hasStoredLogin' => null,
                    'contentId' => null,
                    'email' => null,
                    'passwordHash' => null,
                    'passwordHashType' => null,
                    'enabled' => null,
                    'maxLogin' => null,
                ),
            ),
        );
    }

    /**
     * Get hashes and their respective converted values.
     *
     * This is a PHPUnit data provider
     *
     * The returned records must have the the input hash assigned to the
     * first index and the expected value result to the second. For example:
     *
     * <code>
     * array(
     *      array(
     *          array( 'myValue' => true ),
     *          new MyValue( true ),
     *      ),
     *      // ...
     * );
     * </code>
     *
     * @return array
     */
    public function provideFromHashData()
    {
        return array(
            array(
                array('login' => 'hans'),
                new UserValue(array('login' => 'hans')),
            ),
        );
    }

    /**
     * Overwrite normal content creation.
     *
     * @param mixed $fieldData
     */
    protected function createContent($fieldData, $contentType = null)
    {
        if ($contentType === null) {
            $contentType = $this->testCreateContentType();
        }

        $repository = $this->getRepository();
        $userService = $repository->getUserService();

        // Instantiate a create struct with mandatory properties
        $userCreate = $userService->newUserCreateStruct(
            'hans',
            'hans@example.com',
            'password',
            'eng-US',
            $contentType
        );
        $userCreate->enabled = true;

        // Set some fields required by the user ContentType
        $userCreate->setField('name', 'Example User');

        // ID of the "Editors" user group in an eZ Publish demo installation
        $group = $userService->loadUserGroup(13);

        // Create a new user instance.
        $user = $userService->createUser($userCreate, array($group));

        // Create draft from user content object
        $contentService = $repository->getContentService();

        return $contentService->createContentDraft($user->content->contentInfo, $user->content->versionInfo);
    }

    public function testCreateContentWithEmptyFieldValue()
    {
        $this->markTestSkipped('User field will never be created empty');
    }

    public function providerForTestIsEmptyValue()
    {
        return array(
            array(new UserValue()),
            array(new UserValue(array())),
        );
    }

    public function providerForTestIsNotEmptyValue()
    {
        return array(
            array(
                $this->getValidCreationFieldData(),
            ),
        );
    }

    public function testRemoveFieldDefinition()
    {
        $repository = $this->getRepository();
        $contentService = $repository->getContentService();
        $contentTypeService = $repository->getContentTypeService();
        $content = $this->testPublishContent();
        $countBeforeRemoval = count($content->getFields());

        $contentType = $contentTypeService->loadContentType($content->contentInfo->contentTypeId);
        $contentTypeDraft = $contentTypeService->createContentTypeDraft($contentType);

        $userFieldDefinition = null;
        foreach ($contentTypeDraft->getFieldDefinitions() as $fieldDefinition) {
            if ($fieldDefinition->fieldTypeIdentifier === 'ezuser') {
                $userFieldDefinition = $fieldDefinition;
                break;
            }
        }

        if ($userFieldDefinition === null) {
            $this->fail("'ezuser' field definition was not found");
        }

        $contentTypeService->removeFieldDefinition($contentTypeDraft, $userFieldDefinition);
        $contentTypeService->publishContentTypeDraft($contentTypeDraft);

        $content = $contentService->loadContent($content->id);

        $this->assertCount($countBeforeRemoval - 1, $content->getFields());
        $this->assertNull($content->getFieldValue($userFieldDefinition->identifier));
    }

    public function testAddFieldDefinition()
    {
        $this->markTestIncomplete(
            'Currently cannot be tested since user can be properly created only through UserService'
        );
    }

    /**
     * @param mixed $failingValue
     * @param string $expectedException
     *
     * @dataProvider provideInvalidUpdateFieldData
     */
    public function testUpdateContentFails($failingValue, $expectedException)
    {
        $this->markTestIncomplete(
            'Currently cannot be tested since user can be properly created only through UserService'
        );
    }
}
