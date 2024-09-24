<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ApiBundle\CommandHandler\Checkout;

use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\ApiBundle\Command\Checkout\SendShipmentConfirmationEmail;
use Sylius\Bundle\ApiBundle\Command\Checkout\ShipShipment;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Repository\ShipmentRepositoryInterface;
use Sylius\Component\Shipping\ShipmentTransitions;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Webmozart\Assert\Assert;

#[AsMessageHandler]
final readonly class ShipShipmentHandler
{
    public function __construct(
        private ShipmentRepositoryInterface $shipmentRepository,
        private StateMachineInterface $stateMachine,
        private MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(ShipShipment $shipShipment): ShipmentInterface
    {
        /** @var ShipmentInterface|null $shipment */
        $shipment = $this->shipmentRepository->find($shipShipment->shipmentId);

        Assert::notNull($shipment, 'Shipment has not been found.');

        if ($shipShipment->trackingCode !== null) {
            $shipment->setTracking($shipShipment->trackingCode);
        }

        Assert::true(
            $this->stateMachine->can($shipment, ShipmentTransitions::GRAPH, ShipmentTransitions::TRANSITION_SHIP),
            'This shipment cannot be completed.',
        );

        $this->stateMachine->apply($shipment, ShipmentTransitions::GRAPH, ShipmentTransitions::TRANSITION_SHIP);

        $this->eventBus->dispatch(new SendShipmentConfirmationEmail($shipShipment->shipmentId), [new DispatchAfterCurrentBusStamp()]);

        return $shipment;
    }
}
