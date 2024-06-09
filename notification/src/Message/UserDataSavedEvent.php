<?php
namespace App\Message;

/**
 * Class UserDataSavedEvent
 *
 * This class represents an event that is dispatched when user data is saved.
 */
class UserDataSavedEvent
{
    private int $userId;
    private string $email;
    private string $firstName;
    private string $lastName;

    /**
     * UserDataSavedEvent constructor.
     *
     * @param int $userId The ID of the user
     * @param string $email The email of the user
     * @param string $firstName The first name of the user
     * @param string $lastName The last name of the user
     */
    public function __construct(int $userId, string $email, string $firstName, string $lastName)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Get the user ID.
     *
     * @return int The ID of the user
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Get the user's email.
     *
     * @return string The email of the user
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the user's first name.
     *
     * @return string The first name of the user
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the user's last name.
     *
     * @return string The last name of the user
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }
}
