<?php

namespace App\Command;

use App\Repository\ProductRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:logguer-alert',
    description: 'Add a short description for your command',
)]
class LogguerAlertCommand extends Command
{
    protected static $defaultName = 'app:logguer-alert';

    private $logguer;
    private $productRepository;
    private MailerInterface $mailer;


    public function __construct(LoggerInterface $logger, ProductRepository $productRepository, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->mailer = $mailer;


        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send email if a product is out of stock.')
            ->setHelp('This command allows you to send email if a product is out of stock.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = $this->productRepository->findBy(
            [
                'quantity' => 0
            ]
        );
        $message = '';

        foreach ($products as $product) {
            $message .= 'Product ' . $product->getName() . ' is out of stock.';
            $this->logger->error('Product ' . $product->getName() . ' is out of stock.');
        }
        $email = (new Email())
            ->from('admin@gmail.com')
            ->to('vendor@gmail.com')
            ->subject('Product out of stock')
            ->text($message)
        ;

        $this->mailer->send($email);

        return Command::SUCCESS;
    }
}
