<?php

namespace App\Tests\Unit\Static\Validation;

use App\Static\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ValidationTest extends TestCase
{
    public function testValidateWithViolations(): void
    {
        $constraints = [
            'foo' => [new NotBlank(), new Type(Validator::TYPE_INT)],
            'bar' => [new NotBlank(), new Type(Validator::TYPE_STRING)],
            'fooBar' => [new NotBlank(), new Type(Validator::TYPE_STRING), new Length(['min' => 10])],
        ];

        $data = [
            'foo' => 'WrongValue',
            'bar' => true,
            'fooBar' => 12345,
        ];

        $result = Validator::validate($data, $constraints);

        static::assertTrue($result->hasViolations());
        static::assertSame(3, $result->getViolationCount());
        static::assertSame('This value should be of type integer.', $result->getForField('foo'));
        static::assertSame('This value should be of type string.', $result->getForField('bar'));
        static::assertSame('This value should be of type string.<br />This value is too short. It should have 10 characters or more.', $result->getForField('fooBar'));
    }
}
