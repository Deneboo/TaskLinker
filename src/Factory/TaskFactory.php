<?php

namespace App\Factory;

use App\Entity\Task;
use App\Enum\TaskStatus;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Task>
 */
final class TaskFactory extends PersistentObjectFactory
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
        return Task::class;
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
            'description' => self::faker()->text(),
            'startedAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('2025-01-01', '2026-06-30')
            ),
            'status' => self::faker()->randomElement(TaskStatus::cases()),
            'title' => self::faker()->randomElement([
                'Créer la page d’accueil',
                'Configurer la base de données',
                'Développer le formulaire de connexion',
                'Ajouter la gestion des utilisateurs',
                'Créer les fixtures',
                'Créer les vues',
                'Créer les formulaires',
                'Mettre en place les tests',
                'Mettre en place la sécurité',
                'Tester l’application',
                'Faire la documentation',
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
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }
}
