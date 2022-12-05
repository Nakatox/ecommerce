<?php

namespace App\Command;

use App\Repository\CartRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:clear-old-cart',
    description: 'Add a short description for your command',
)]
class ClearOldCartCommand extends Command
{
    protected static $defaultName = 'app:clear-old-cart';

    private CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Clears old carts.')
            ->setHelp('This command allows you to clear old carts.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $carts = $this->cartRepository->findAll();

        foreach ($carts as $cart) {
            if ($cart->getLastTimeUpdated() === null) {
                continue;
            }
            if ($cart->getLastTimeUpdated()->diff(new \DateTime())->days > 7) {
                $products = $cart->getProducts();
                foreach ($products as $product) {
                    $cart->removeProduct($product);
                }
                $this->cartRepository->save($cart, true);
            }
        }

        $output->writeln('Old carts cleared successfully.');

        return Command::SUCCESS;

    }
}
