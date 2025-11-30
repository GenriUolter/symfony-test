<?php

namespace App\Command;

use App\DTO\User\Console\UserAdminConsoleCreateDTO;
use App\DTO\User\Domain\UserCreateDataMapper;
use App\Enums\RoleEnum;
use App\Exceptions\AppException;
use App\Services\UserService;
use App\Validation\ValidationErrorMapper;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an admin user',
)]
class CreateAdminCommand extends Command
{
    /**
     * @param ValidatorInterface $validator
     * @param UserService $userService
     * @param ValidationErrorMapper $validationErrorMapper
     * @param UserCreateDataMapper $userCreateDataMapper
     */
    public function __construct(
        private readonly ValidatorInterface    $validator,
        private readonly UserService           $userService,
        private readonly ValidationErrorMapper $validationErrorMapper,
        private readonly UserCreateDataMapper  $userCreateDataMapper,
    ) {
        parent::__construct($this->getName());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $adminLogin    = $io->ask('Enter admin login (min: 3, max 8 characters):');
        $adminPassword = $io->askHidden('Enter admin password (min: 6, max 8 characters):');

        $userAdminDTO = new UserAdminConsoleCreateDTO(
            login: $adminLogin,
            role: RoleEnum::Root->value,
            password: $adminPassword
        );

        try {
            $this->validationErrorMapper->throwIfNotValid(
                $this->validator->validate($userAdminDTO)
            );

            $this->userService->create(
                $this->userCreateDataMapper->fromConsoleDto(
                    $userAdminDTO
                )
            );

            $output->writeln('<info>Admin user created successfully!</info>');

            return Command::SUCCESS;
        } catch (AppException $exception) {
            $output->writeln("<error>{$exception->getMessage()}<error>");

            $this->handleAppException($exception, $output);
        } catch (Exception $exception) {
            $output->writeln("<error>{$exception->getMessage()}<error>");
        }

        return Command::FAILURE;
    }

    /**
     * @param AppException $exception
     * @param OutputInterface $output
     * @return void
     */
    private function handleAppException(AppException $exception, OutputInterface $output): void
    {
        if ($exception->getCode() !== Response::HTTP_UNPROCESSABLE_ENTITY) {
            return;
        }

        $validationErrorMessage = '';
        $failedValidationFields = $exception->getContent();

        foreach ($failedValidationFields as $failedValidationField => $failedValidationMessage) {
            $validationErrorMessage .= $failedValidationField
                                       . ': '
                                       . implode('. ', $failedValidationMessage) . PHP_EOL;
        }

        $output->writeln("<error>$validationErrorMessage<error>");
    }
}
