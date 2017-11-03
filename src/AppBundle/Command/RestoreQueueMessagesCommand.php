<?php

namespace AppBundle\Command;

use AppBundle\Contract\Queue\IMailPublisher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RestoreQueueMessagesCommand
 * @package AppBundle\Command
 */
class RestoreQueueMessagesCommand extends Command
{
    /** @var ContainerInterface $container */
    protected $container;

    /** @var IMailPublisher $mailPublisher */
    protected $mailPublisher;

    /**
     * RestoreQueueMessagesCommand constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container,
        IMailPublisher $mailPublisher
    )
    {
        $this->container = $container;
        $this->mailPublisher = $mailPublisher;
        parent::__construct(null);
    }


    protected function configure() : void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:queue:restore')

            // the short description shown while running "php bin/console list"
            ->setDescription('Try to resend failed messages.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to resend failed messages back to the queue...')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $logPath = $this->container->getParameter('kernel.logs_dir') . '/failed_queue.log';
        $file = new \SplFileObject($logPath, "r");
        $newPath = $this->container->getParameter('kernel.logs_dir').'/tmp_failed_queue.log';
        $newFile = new \SplFileObject($newPath, 'w');
        while(!$file->eof()) {
            $newFile->fwrite($file->fgets());
        }
        unset($file);
        file_put_contents($logPath, "");

        unset ($newFile);
        $file =  new \SplFileObject($newPath);
        while(!$file->eof()) {
            $data = json_decode($file->fgets(), true);
            if (is_array($data)) {
                $output->writeln("<info>sending {$data['context']['mail']} mail</info>");
                $this->mailPublisher->publish($data['context']['mail'], $data['context']['payload']);
            }
        }
        unset($file);
        unlink($newPath);
        exit(0);
    }
}
