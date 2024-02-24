<?php

namespace App\Tests\Unit\Validation\Validator;

use App\Validation\IdConstraint;
use App\Validation\Validator;
use App\Validation\Validator\TagValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagValidatorTest extends TestCase
{
    public function testValidate(): void
    {
        $data = [
            'name' => 'Test',
        ];

        $validator = $this->createTagValidator();

        $result = $validator->validate($data);

        static::assertFalse($result->hasViolations());
        static::assertSame(0, $result->getViolationCount());
        static::assertCount(0, $result->getViolations());
    }

    public function testValidateWithoutDisplayName(): void
    {
        $data = [
            'name' => null,
        ];

        $validator = $this->createTagValidator();
        $result = $validator->validate($data);

        static::assertTrue($result->hasViolations());
        static::assertSame(1, $result->getViolationCount());
        static::assertCount(1, $result->getViolations());
        static::assertArrayHasKey('name', $result->getViolations());
        static::assertSame('Please provide a Tag name', $result->getViolations()['name']);
    }

    public function testValidateWithEmptyString(): void
    {
        $data = [
            'name' => '',
        ];

        $validator = $this->createTagValidator();
        $result = $validator->validate($data);

        static::assertTrue($result->hasViolations());
        static::assertSame(1, $result->getViolationCount());
        static::assertCount(1, $result->getViolations());
        static::assertArrayHasKey('name', $result->getViolations());
        static::assertSame('Please provide a Tag name', $result->getViolations()['name']);
    }

    public function testValidateWithEmptyStringWithSpaces(): void
    {
        $data = [
            'name' => '   ',
        ];

        $validator = $this->createTagValidator();
        $result = $validator->validate($data);

        static::assertTrue($result->hasViolations());
        static::assertSame(1, $result->getViolationCount());
        static::assertCount(1, $result->getViolations());
        static::assertArrayHasKey('name', $result->getViolations());
        static::assertSame('Please provide a Tag name', $result->getViolations()['name']);
    }

    public function testValidateWithExpectIdAndNoIdGiven(): void
    {
        $data = [
            'name' => 'Test',
        ];

        $validator = $this->createTagValidator();

        $result = $validator->validate($data, Validator::EXPECT_ID);

        static::assertTrue($result->hasViolations());
        static::assertSame(1, $result->getViolationCount());
        static::assertCount(1, $result->getViolations());
        static::assertArrayHasKey('id', $result->getViolations());
        static::assertSame('Please provide a Tag ID', $result->getViolations()['id']);
    }

    public function testValidateWithExpectIdAndIdIsNotNumeric(): void
    {
        $data = [
            'name' => 'Test',
            'id' => '12abc',
        ];

        $validator = $this->createTagValidator();

        $result = $validator->validate($data, Validator::EXPECT_ID);

        static::assertTrue($result->hasViolations());
        static::assertSame(1, $result->getViolationCount());
        static::assertCount(1, $result->getViolations());
        static::assertArrayHasKey('id', $result->getViolations());
        static::assertSame('This value should be of type integer.', $result->getViolations()['id']);
    }

    public function testValidateWithExpectIdAndIdIsNumericString(): void
    {
        $data = [
            'name' => 'Test',
            'id' => '42',
        ];

        $validator = $this->createTagValidator();

        $result = $validator->validate($data, Validator::EXPECT_ID);

        static::assertFalse($result->hasViolations());
        static::assertSame(0, $result->getViolationCount());
        static::assertCount(0, $result->getViolations());
    }

    public function testValidateWithExpectIdAndIdIsNull(): void
    {
        $data = [
            'name' => 'Test',
            'id' => null,
        ];

        $validator = $this->createTagValidator();

        $result = $validator->validate($data, Validator::EXPECT_ID);

        static::assertTrue($result->hasViolations());
        static::assertSame(1, $result->getViolationCount());
        static::assertCount(1, $result->getViolations());
        static::assertArrayHasKey('id', $result->getViolations());
        static::assertSame('Please provide a Tag ID', $result->getViolations()['id']);
    }

    private function createTagValidator(): TagValidator
    {
        $translatorMock = $this->createTranslatorMock();

        return new TagValidator(new IdConstraint($translatorMock), $translatorMock);
    }

    private function createTranslatorMock(): TranslatorInterface
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $translatorMock->method('trans')->willReturnMap([
            ['admin.tags.messages.errors.noTagName', [], null, null, 'Please provide a Tag name'],
            ['admin.tags.messages.errors.emptyId', [], null, null, 'Please provide a Tag ID'],
            ['admin.category.messages.errors.emptyId', [], null, null, 'Please provide a Tag ID'],
        ]);

        return $translatorMock;
    }
}