<?php

namespace App\Factory;

use App\Entity\User;
use App\Enum\UserContract;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
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
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $firstName = self::faker()->firstName();
        $lastName = self::faker()->lastName();
        $number = self::faker()->numberBetween(1, 99);

        return [
            'contract' => self::faker()->randomElement(UserContract::cases()),
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => self::faker()->unique()->bothify(
                strtolower($firstName . '.' . $lastName . $number . '@example.com')
            ),
            'hiredAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('2025-01-01', '2026-06-30')
            ),
            'password' => password_hash('password', PASSWORD_DEFAULT),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }
}
