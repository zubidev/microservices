<?php

namespace App\Message;

/**
 * Class UserDataSavedEvent
 *
 * This class represents an event message that carries user data
 * saved event information.
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
     * Get the ID of the user.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Get the email of the user.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the first name of the user.
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the last name of the user.
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }
}
