<?php
namespace App\EventSubscriber;

use App\Entity\Product;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PriceUpdateSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate => 'onEntityChanged',
        ];
    }

    public function onEntityChanged(GenericEvent $event)
    {
        $product = $event->getSubject();

        if ($product instanceof Product) {

            $clients = $product->getCarts()->map(function ($cart) {
                return $cart->getClient();
            })->toArray();

            foreach ($clients as $client) {
                $email = (new Email())
                    ->from('noreply@example.com')
                    ->to($client->getEmail())
                    ->subject('Mise à jour du prix d\'un produit dans votre panier')
                    ->text('Le prix du produit ' . $product->getName() . ' dans votre panier a été mis à jour. Veuillez vérifier votre panier pour plus de détails.');

                $this->mailer->send($email);
            }
        }
    }
}
