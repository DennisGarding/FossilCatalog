<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Tests\Integration\ContainerTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TagRepositoryTest extends KernelTestCase
{
    use ContainerTrait;

    /**
     * installs tag1 - tag6 with equal display name and id
     *
     * @before
     */
    public function installTestData(): void
    {
        $sql = file_get_contents(__DIR__ . '/_fixtures/tags_default_test_data.sql');
        static::assertIsString($sql);

        static::getContainer()->get('doctrine')->getConnection()->executeQuery($sql);
    }

    /**
     * @after
     */
    public function clearDatabase()
    {
        static::getContainer()->get('doctrine')->getConnection()->executeQuery('DELETE FROM tag WHERE true');
    }

    /**
     * @dataProvider saveTestDataProvider
     *
     * @param array<int, array|bool|int> $requestData
     */
    public function testSave(array $requestData, bool $expectException, int $expectedCount): void
    {
        $tagRepository = self::getContainer()->get(TagRepository::class);

        if ($expectException) {
            static::expectException(\Exception::class);
        }

        $tagRepositorySaveResult = $tagRepository->save($requestData);

        $count = $tagRepository->getColumnCount();
        static::assertSame($expectedCount, $count);

        if ($expectException) {
            return;
        }

        $result = $tagRepository->find($tagId);
        static::assertInstanceOf(Tag::class, $result);
        static::assertSame($requestData['name'], $result->getName());

        $tagRepository->delete($tagId);
    }

    /**
     * @return array<string, array<int, array|bool|int>>
     */
    public static function saveTestDataProvider(): array
    {
        return [
            'creates a new tag with name test1' => [
                [
                    'name' => 'test1',
                ],
                false,
                7,
            ],

            'should update the tag with id 1' => [
                [
                    'id' => 1,
                    'name' => 'test2',
                ],
                false,
                6,
            ],
        ];
    }
}