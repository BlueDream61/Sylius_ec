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

namespace Sylius\Bundle\PayumBundle\DependencyInjection;

use Payum\Paypal\ExpressCheckout\Nvp\PaypalExpressCheckoutGatewayFactory;
use Payum\Stripe\StripeCheckoutGatewayFactory;
use Sylius\Bundle\PaymentBundle\Attribute\AsGatewayConfigurationType;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SyliusPayumExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $this->registerResources('sylius', $config['driver'], $config['resources'], $container);

        $loader->load('services.xml');

        if (class_exists(PaypalExpressCheckoutGatewayFactory::class)) {
            $loader->load('integrations/payum/paypal.xml');
        }

        if (class_exists(StripeCheckoutGatewayFactory::class)) {
            $loader->load('integrations/payum/stripe.xml');
        }

        $container->setParameter('payum.template.layout', $config['template']['layout']);
        $container->setParameter('payum.template.obtain_credit_card', $config['template']['obtain_credit_card']);
        $container->setParameter('sylius.payum.gateway_config.validation_groups', $config['gateway_config']['validation_groups']);

        $this->registerAutoconfiguration($container);
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependSyliusPayment($container);
    }

    private function registerAutoconfiguration(ContainerBuilder $container): void
    {
        $container->registerAttributeForAutoconfiguration(
            AsGatewayConfigurationType::class,
            static function (ChildDefinition $definition, AsGatewayConfigurationType $attribute): void {
                $definition->addTag(AsGatewayConfigurationType::SERVICE_TAG, [
                    'type' => $attribute->getType(),
                    'label' => $attribute->getLabel(),
                    'priority' => $attribute->getPriority(),
                ]);
            },
        );
    }

    private function prependSyliusPayment(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('sylius_payment')) {
            return;
        }

        $gateways = [];
        $configs = $container->getExtensionConfig('payum');
        foreach ($configs as $config) {
            if (!isset($config['gateways'])) {
                continue;
            }

            /** @var string $gatewayKey */
            foreach (array_keys($config['gateways']) as $gatewayKey) {
                $gateways[$gatewayKey] = 'sylius.payum_gateway.' . $gatewayKey;
            }
        }

        $container->prependExtensionConfig('sylius_payment', ['gateways' => $gateways]);
    }
}
