<?php

namespace App\Factory;

use App\Entity\Project;
use App\Enum\ProjectStatus;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Project>
 */
final class ProjectFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Project::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'startedAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('2025-01-01', '2026-06-30')
            ),
            'status' => self::faker()->randomElement(ProjectStatus::cases()),
            'title' => self::faker()->unique()->randomElement([
                'Refonte du site web TomTroc',
                'Application de gestion des tâches TaskLinker',
                'Plateforme de réservation d\'automobiles Vehicloc',
                'Développement du site e-commerce MyShop',
                'Création de la blogoshère DearDiary',
                'Application de suivi des dépenses MoneyTracker',
            ]),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Project $project): void {})
        ;
    }
}
