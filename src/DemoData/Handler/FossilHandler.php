<?php

namespace App\DemoData\Handler;

use App\DemoData\EntityOptions;
use App\DemoData\Random;
use App\DemoData\Random\EntityObjectOptions;
use App\DemoData\Random\NameOptions;
use App\DemoData\Random\NameOptions\AmmoniteNames;
use App\DemoData\Random\NameOptions\CountryNames;
use App\DemoData\Random\NameOptions\FindingPlaceNames;
use App\DemoData\Random\NameOptions\FormationNames;
use App\DemoData\Random\NameOptions\GeniusNames;
use App\DemoData\Random\StringOptions;
use App\DemoData\Random\StringOptions\Numbers;
use App\DemoData\Random\StringOptions\UpperCaseLetters;
use App\DemoData\Random\SystemSeriesStageResult;
use App\Entity\Category;
use App\Entity\EarthAgeSeries;
use App\Entity\EarthAgeStage;
use App\Entity\EarthAgeSystem;
use App\Entity\Fossil;
use App\Entity\Tag;
use App\Repository\CategoryRepository;
use App\Repository\EarthAgeSystemRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class FossilHandler implements HandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CategoryRepository $categoryRepository,
        private readonly TagRepository $tagRepository,
        private readonly EarthAgeSystemRepository $earthAgeSystemRepository,
    ) {}

    public function supports(): string
    {
        return Fossil::class;
    }

    public function create(EntityOptions $options): void
    {
        for ($i = 0; $i < $options->amount; $i++) {
            $fossil = $this->createFossil();

            $this->entityManager->persist($fossil);

            if ($i % 100 === 0) {
                $this->entityManager->flush();
            }
        }

        $this->entityManager->flush();
    }

    public function createFossil(): Fossil
    {
        $systemSeriesStage = $this->createSystemSeriesStage();

        $fossil = new Fossil();
        $fossil->setShowInGallery(mt_rand(0, 1) === 1);
        $fossil->setNumber($this->createNumber());
        $this->addCategories($fossil);
        $this->addTags($fossil);
        $fossil->setDateOfDiscovery(Random::date(new \DateTimeImmutable('3 years ago'), new \DateTimeImmutable('now')));
        $fossil->setFoundInCountry($this->createCountryName());
        $fossil->setFindingPlace($this->createFindingPlaceName());
        $fossil->setCoordinates($this->createCoordinates());
        $fossil->setFindingNotes(Random::loremIpsum());
        $fossil->setEarthAgeSystem($systemSeriesStage->getSystem()->getName());
        $fossil->setEarthAgeSeries($systemSeriesStage->getSeries()->getName());
        $fossil->setEarthAgeStage($systemSeriesStage->getStage()->getName());
        $fossil->setFormation($this->createFormationName());
        $fossil->setStratigraphicMember($this->createFormationName());
        $fossil->setStratigraphicNotes(Random::loremIpsum());
        $fossil->setGenius($this->createGeniusName());
        $fossil->setSpecies($this->createSpeciesName());
        $fossil->setSize(Random::string(new StringOptions(mt_rand(1, 3), [new Numbers()])) . ' cm');
        $fossil->setTaxonomyNotes(Random::loremIpsum());
        $fossil->setCreatedAt(new \DateTimeImmutable());
        $fossil->setUpdatedAt(new \DateTimeImmutable());

        return $fossil;
    }

    private function createNumber(): string
    {
        $prefix = Random::string(new StringOptions(2, [new UpperCaseLetters()]));
        $number = Random::string(new StringOptions(6, [new Numbers()]));

        return $prefix . $number;
    }

    private function createSpeciesName(): string
    {
        return Random::name(
            new NameOptions(
                names: AmmoniteNames::getNames(),
                divider: '',
                stringOptions: new StringOptions(0, []),
            )
        );
    }

    private function createCountryName(): string
    {
        return Random::name(
            new NameOptions(
                names: CountryNames::getNames(),
                divider: '',
                stringOptions: new StringOptions(0, []),
            )
        );
    }

    private function createFindingPlaceName(): string
    {
        return Random::name(
            new NameOptions(
                names: FindingPlaceNames::getNames(),
                divider: '',
                stringOptions: new StringOptions(0, []),
            )
        );
    }

    private function createFormationName(): string
    {
        return Random::name(
            new NameOptions(
                names: FormationNames::getNames(),
                divider: '',
                stringOptions: new StringOptions(0, []),
            )
        );
    }

    private function createGeniusName(): string
    {
        return Random::name(
            new NameOptions(
                names: GeniusNames::getNames(),
                divider: '',
                stringOptions: new StringOptions(0, []),
            )
        );
    }

    private function createCoordinates(): string
    {
        $latitude = mt_rand(49, 53) . '.' . mt_rand(100000, 999999);
        $longitude = mt_rand(7, 11) . '.' . mt_rand(100000, 999999);

        return $latitude . ', ' . $longitude;
    }

    private function addCategories(Fossil $fossil): void
    {
        $categories = Random::entity(
            new EntityObjectOptions(
                length: mt_rand(1, 4),
                entities: $this->categoryRepository->findAll(),
            )
        );

        foreach ($categories as $category) {
            if (!$category instanceof Category) {
                continue;
            }

            $fossil->addCategory($category);
        }
    }

    private function addTags(Fossil $fossil): void
    {
        $tags = Random::entity(
            new EntityObjectOptions(
                length: mt_rand(1, 4),
                entities: $this->tagRepository->findAll(),
            )
        );

        foreach ($tags as $tag) {
            if (!$tag instanceof Tag) {
                continue;
            }
            $fossil->addTag($tag);
        }
    }

    private function createSystemSeriesStage(): SystemSeriesStageResult
    {
        $systems = $this->earthAgeSystemRepository->createQueryBuilder('system')
            ->where('system.active = true')
            ->andWhere('system.name != :name')
            ->setParameter('name', 'Präkambrium')
            ->getQuery()
            ->getResult();

        $systemArray = Random::entity(new EntityObjectOptions(1, $systems));
        /** @var EarthAgeSystem $system */
        $system = array_shift($systemArray);

        $allSeries = $system->getEarthAgeSeries();
        // @phpstan-ignore-next-line
        $seriesArray = Random::entity(new EntityObjectOptions(1, $allSeries->getIterator()->getArrayCopy()));
        /** @var EarthAgeSeries $series */
        $series = array_shift($seriesArray);

        $stages = $series->getEarthAgeStage();
        // @phpstan-ignore-next-line
        $stagesArray = Random::entity(new EntityObjectOptions(1, $stages->getIterator()->getArrayCopy()));
        /** @var EarthAgeStage $stage */
        $stage = array_shift($stagesArray);

        return new SystemSeriesStageResult($system, $series, $stage);
    }
}
