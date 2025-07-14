<?php

declare(strict_types=1);

namespace Tests\Src\Shop\User\Infrastructure\Repository;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\User\Domain\Exception\DuplicateUserEmailException;
use Src\Shop\User\Domain\Exception\InvalidCredentialsException;
use Src\Shop\User\Domain\Exception\UserNotFoundByEmailException;
use Src\Shop\User\Domain\Token;
use Src\Shop\User\Infrastructure\Mapper\UserMapper;
use Src\Shop\User\Infrastructure\Persistence\Eloquent\UserEloquentModel;
use Src\Shop\User\Infrastructure\Repository\EloquentUserRepository;
use Tests\Src\Shop\User\Domain\UserMother;
use Tests\Src\Shop\User\Domain\ValueObject\UserEmailMother;
use Tests\Src\Shop\User\Domain\ValueObject\UserPasswordMother;
use Tests\Utils\IntegrationDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\CustomerScenarioBuilder;
use Tests\Utils\Scenarios\LoginScenarioBuilder;

final class EloquentUserRepositoryTest extends IntegrationDatabaseTransactionsLaravelTestCase
{
    private EloquentUserRepository $repository;

    #[Group('integration')]
    public function test_it_should_create_a_user(): void
    {
        $customer = CustomerScenarioBuilder::customerMotherMakeAndPersist();
        $user = UserMother::make(customerId: $customer->id());

        $this->repository->save($user);

        $this->assertDatabaseHas('users', [
            'id' => $user->id()->value(),
            'email' => $user->email()->value(),
        ], $this->connectionLaravel);
    }

    #[Group('integration')]
    public function test_it_should_throw_exception_on_duplicate_email(): void
    {
        $sameEmail = UserEmailMother::make();
        $user1 = UserMother::make(
            customerId: CustomerScenarioBuilder::customerMotherMakeAndPersist()->id(),
            email: $sameEmail, // Using the same email
        );
        $user2 = UserMother::make(
            customerId: CustomerScenarioBuilder::customerMotherMakeAndPersist()->id(),
            email: $sameEmail, // Using the same email
        );
        $this->repository->save($user1);
        $this->expectException(DuplicateUserEmailException::class);

        $this->repository->save($user2); // Duplicated
    }

    #[Group('integration')]
    public function test_it_should_find_a_user_by_email(): void
    {
        $customer = CustomerScenarioBuilder::customerMotherMakeAndPersist();
        $user = UserMother::make(customerId: $customer->id());
        $this->repository->save($user);

        $userPersisted = $this->repository->findByEmail($user->email());

        $this->assertSame($user->email()->value(), $userPersisted->email()->value());
    }

    #[Group('integration')]
    public function test_it_throws_exception_when_user_not_found_by_email(): void
    {
        $this->expectException(UserNotFoundByEmailException::class);

        $this->repository->findByEmail(
            UserEmailMother::make('nonexistent@example.com'),
        );
    }

    #[Group('integration')]
    public function test_it_should_create_a_token(): void
    {
        $customer = CustomerScenarioBuilder::customerMotherMakeAndPersist();
        $user = UserMother::make(customerId: $customer->id());
        $this->repository->save($user);

        $tokenPersisted = $this->repository->createToken($user->email());

        $this->assertInstanceOf(Token::class, $tokenPersisted);
        $this->assertNotEmpty($tokenPersisted->tokenString()->value());
        $this->assertSame('Bearer', $tokenPersisted->tokenType()->value());
    }

    #[Group('integration')]
    public function test_it_throws_exception_when_user_not_found_by_email_for_token(): void
    {
        $this->expectException(UserNotFoundByEmailException::class);

        $this->repository->createToken(
            UserEmailMother::make('noone@example.com'),
        );
    }

    #[Group('integration')]
    public function test_it_should_login_user_and_return_authenticated_user(): void
    {
        // Arrange
        $customer = CustomerScenarioBuilder::customerMotherMakeAndPersist();
        $user = UserMother::make(
            customerId: $customer->id(),
            password: UserPasswordMother::make('Password123!'),
        );
        $this->repository->save($user);

        // Act
        $authenticatedUser = $this->repository->login($user->email(), 'Password123!');

        // Assert
        $this->assertSame($user->email()->value(), $authenticatedUser->email()->value());
        $this->assertSame($user->id()->value(), $authenticatedUser->id()->value());
    }

    #[Group('integration')]
    public function test_it_should_throw_exception_when_login_fails(): void
    {
        // Arrange
        $customer = CustomerScenarioBuilder::customerMotherMakeAndPersist();
        $user = UserMother::make(
            customerId: $customer->id(),
            password: UserPasswordMother::make('Password123!'),
        );
        $this->repository->save($user);

        // Expect exception
        $this->expectException(InvalidCredentialsException::class);

        // Act - Wrong password
        $this->repository->login($user->email(), 'wrong_password');
    }

    #[Group('integration')]
    public function test_it_should_logout_and_delete_all_tokens(): void
    {
        $user = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum()->user;
        $userEloquent = UserEloquentModel::find($user->id()->value());
        $this->repository->createToken($user->email());  // Sanctum::actingAs() mocks the token, so it must be created.
        $this->assertCount(1, $userEloquent->fresh()->tokens);

        $this->repository->logout();

        $this->assertCount(0, $userEloquent->fresh()->tokens);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentUserRepository(new UserMapper());
    }
}
