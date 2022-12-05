<?php

namespace App\Command;

use App\Repository\ClientRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:send-birthday-emails',
    description: 'Add a short description for your command',
)]
class SendBirthdayEmailsCommand extends Command
{
    protected static $defaultName = 'app:send-birthday-emails';

    private MailerInterface $mailer;
    private ClientRepository $clientRepository;

    public function __construct(MailerInterface $mailer, ClientRepository $clientRepository)
    {
        $this->mailer = $mailer;
        $this->clientRepository = $clientRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Sends birthday emails to customers.')
            ->setHelp('This command allows you to send birthday emails to customers.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientsRepo = $this->clientRepository->findAll();
        $clients = [];
        foreach ($clientsRepo as $client) {
            if ($client->getBirthDate() === null) {
                continue;
            }
            if ($client->getBirthDate()->format('m-d') === (new \DateTime())->format('m-d')) {
                $clients[] = $client;
            }
        }

        foreach ($clients as $client) {
            $email = (new Email())
                ->from('no-reply@example.com')
                ->to($client->getEmail())
                ->subject('Joyeux anniversaire !')
                ->text('Joyeux anniversaire !');

            $this->mailer->send($email);
        }

        $output->writeln('Birthday emails sent successfully.');

        return Command::SUCCESS;
    }
}