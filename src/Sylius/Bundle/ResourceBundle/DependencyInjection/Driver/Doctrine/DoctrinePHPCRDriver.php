<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Doctrine;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Repository\TranslatableResourceRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Arnaud Langlade <aRn0D.dev@gmail.com>
 */
class DoctrinePHPCRDriver extends AbstractDoctrineDriver
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM;
    }

    /**
     * {@inheritdoc}
     */
    protected function addRepository(ContainerBuilder $container, MetadataInterface $metadata)
    {
        $repositoryClass = new Parameter('sylius.phpcr_odm.repository.class');

        if ($metadata->hasClass('repository')) {
            $repositoryClass = $metadata->getClass('repository');
        }

        $repositoryReflection = new \ReflectionClass($repositoryClass);

        $definition = new Definition($repositoryClass);
        $definition->setArguments([
            new Reference($metadata->getServiceId('manager')),
            $this->getClassMetadataDefinition($metadata),
        ]);
        $definition->setLazy(!$repositoryReflection->isFinal());

        if ($metadata->hasParameter('translation')) {
            $translatableRepositoryInterface = TranslatableResourceRepositoryInterface::class;
            $translationConfig = $metadata->getParameter('translation');

            if (interface_exists($translatableRepositoryInterface) && $repositoryReflection->implementsInterface($translatableRepositoryInterface)) {
                if (isset($translationConfig['fields'])) {
                    $definition->addMethodCall('setTranslatableFields', [$translationConfig['fields']]);
                }
            }
        }

        $container->setDefinition($metadata->getServiceId('repository'), $definition);
    }

    /**
     * {@inheritdoc}
     */
    protected function addDefaultForm(ContainerBuilder $container, MetadataInterface $metadata)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function getManagerServiceId(MetadataInterface $metadata)
    {
        return sprintf('doctrine_phpcr.odm.%s_document_manager', $this->getObjectManagerName($metadata));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassMetadataClassname()
    {
        return 'Doctrine\\ODM\\PHPCR\\Mapping\\ClassMetadata';
    }
}
