<?php

namespace AppBundle\Command;

use AppBundle\Contract\Service\User\IUserCreate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use AppBundle\Exception\FormValidateException;

/**
 * Class CreateUserCommand
 * @package AppBundle\Command
 */
class CreateUserCommand extends Command
{
    /** @var IUserCreate $userCreate */
    protected $userCreate;

    protected $helper;

    /**
     * CreateUserCommand constructor.
     * @param IUserCreate $userCreate
     */
    public function __construct(
        IUserCreate $userCreate
    )
    {
        $this->userCreate = $userCreate;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:user:create')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $email = $this->askEmail($input, $output);
        $login = $this->askLogin($input, $output);
        $firstName = $this->askFirstName($input, $output);
        $lastName = $this->askLastName($input, $output);
        $password = $this->askPassword($input, $output);
        $role = $this->askRole($input, $output);

        $this->tryToRegister([
            'email' => $email,
            'login' => $login,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'password' => $password,
            'role' => $role
        ], $input, $output);
    }

    /**
     * @param array $parameters
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function tryToRegister(array $parameters, InputInterface $input, OutputInterface $output) : void
    {
        try {
            $user = $this->userCreate->execute($parameters);
            $output->writeln("<fg=green>User {$user->getLogin()} has been created.</>");
            exit(0);
        } catch (FormValidateException $exception) {
            $errors = $exception->getErrors();
            $output->writeln('<fg=red>Ups...Some errors were occurred.</>');
            foreach ($errors as $key => $error) {
                $output->writeln("<fg=red>{$error}</>");
                switch ($key) {
                    case 'email':
                        $parameters['email'] = $this->askEmail($input, $output);
                        break;
                    case 'login':
                        $parameters['login'] = $this->askLogin($input, $output);
                        break;
                    case 'first_name':
                        $parameters['first_name'] = $this->askFirstName($input, $output);
                        break;
                    case 'last_name':
                        $parameters['last_name'] = $this->askLastName($input, $output);
                        break;
                    case 'role':
                        $parameters['role'] = $this->askRole($input, $output);
                        break;
                    case 'password':
                        $parameters['password'] = $this->askPassword($input, $output);
                        break;

                }
            }

            $this->tryToRegister($parameters, $input, $output);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function askEmail(InputInterface $input, OutputInterface $output) : string
    {
        $helper = $this->getHelper('question');
        $question = new Question("<info>Please enter the email: <fg=blue>[required]</>: </info>", '');
        $email = $helper->ask($input, $output, $question);
        return $email;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function askLogin(InputInterface $input, OutputInterface $output) : string
    {
        $helper = $this->getHelper('question');
        $question = new Question('<info>Please enter the login: <fg=blue>[required]</>: </info>', '');
        $login = $helper->ask($input, $output, $question);
        return $login;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function askRole(InputInterface $input, OutputInterface $output) : string
    {
        $helper = $this->getHelper('question');
        $question = new Question('<info>Please enter the role: [ROLE_USER, ROLE_ADMIN, ROLE_SUPER_ADMIN]: </info>');
        $role = $helper->ask($input, $output, $question);
        if (!in_array($role, ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'])) {
            $output->writeln("<fg=red>Incorrect value {$role}. Use one of predefined roles.</>");
            return $this->askRole($input, $output);
        }
        return $role;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function askFirstName(InputInterface $input, OutputInterface $output) : string
    {
        $helper = $this->getHelper('question');
        $question = new Question('<info>Please enter the first name: </info>', '');
        $firstName = $helper->ask($input, $output, $question);
        return $firstName;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function askLastName(InputInterface $input, OutputInterface $output) : string
    {
        $helper = $this->getHelper('question');
        $question = new Question('<info>Please enter the last name: </info>', '');
        $lastName = $helper->ask($input, $output, $question);
        return $lastName;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function askPassword(InputInterface $input, OutputInterface $output) : string
    {
        $helper = $this->getHelper('question');
        $question = new Question('<info>Please enter the password: <fg=blue>[required]</>: </info>', '');
        $password = $helper->ask($input, $output, $question);
        return $password;
    }
}
