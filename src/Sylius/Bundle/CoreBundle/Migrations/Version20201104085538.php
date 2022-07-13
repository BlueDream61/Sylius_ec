<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMySqlMigration;

final class Version20201104085538 extends AbstractMySqlMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_product_attribute ADD translatable TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE sylius_product_attribute_value CHANGE locale_code locale_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_product_attribute DROP translatable');
        $this->addSql('ALTER TABLE sylius_product_attribute_value CHANGE locale_code locale_code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
