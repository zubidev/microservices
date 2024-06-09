<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\UserDataSavedEvent;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;

class UsersController extends AbstractController
{
    private $userService;
    private $messageBus;
    private $entityManager;

    public function __construct(UserService $userService, MessageBusInterface $messageBus, EntityManagerInterface $entityManager)
    {
        $this->userService = $userService;
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
    }

    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validate data
        $validationResult = $this->validateUserData($data);
        if ($validationResult['success'] === false) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Validation error',
                'data' => $validationResult['data'],
            ], Response::HTTP_BAD_REQUEST);
        }

        // Check if the user already exists
        $existingUser = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Email is already in use',
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }

        // Proceed with user creation
        try {
            $userId = $this->userService->createUser($data['email'], $data['firstName'], $data['lastName']);
            $this->messageBus->dispatch(new UserDataSavedEvent($userId, $data['email'], $data['firstName'], $data['lastName']));

            return new JsonResponse([
                'success' => true,
                'message' => 'User created successfully',
                'data' => [
                    'firstName' => $data['firstName'],
                    'lastName' => $data['lastName'],
                    'email' => $data['email'],
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            $this->logger->error('Failed to create user: ' . $e->getMessage());

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create user',
                'data' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Validate user data using Symfony Validator Component.
     *
     * @param array $data
     * @return array
     */
    private function validateUserData(array $data): array
    {
        // Define constraints
        $constraints = [
            'firstName' => [
                new Assert\NotBlank(message: "Firstname should not be blank"),
                new Assert\NotNull()
            ],
            'lastName' => [
                new Assert\NotBlank(message: "Lastname should not be blank"),
                new Assert\NotNull()
            ],
            'email' => [
                new Assert\NotBlank(message: "Email should not be blank"),
                new Assert\Email(message: "Email value is not a valid email address."),
            ]
        ];

        // Validate data
        $validator = Validation::createValidator();
        $violations = $validator->validate($data, new Assert\Collection($constraints));

        // Check for violations
        $errors = [];
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            return [
                'success' => false,
                'message' => 'Validation error',
                'data' => $errors,
            ];
        }

        return [
            'success' => true,
            'message' => 'Validation successful',
            'data' => [],
        ];
    }
}
