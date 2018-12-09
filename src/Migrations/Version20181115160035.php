<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115160035 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(<<<'SQL'
            CREATE TABLE used_vouchers (
                id          INT auto_increment NOT NULL,
                voucher_id  INT DEFAULT NULL,
                user_id     INT DEFAULT NULL,
                redeemed_at DATE NOT NULL comment '(DC2Type:date_immutable)',
                INDEX idx_592812e728aa1b6f (voucher_id),
                INDEX idx_592812e7a76ed395 (user_id),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8
            COLLATE utf8_unicode_ci
            engine = innodb;
SQL
        );

        $this->addSql(<<<'SQL'
            CREATE TABLE vouchers (
                id                 INT auto_increment NOT NULL,
                unique_code        VARCHAR(255) NOT NULL,
                type               VARCHAR(255) NOT NULL,
                valid_from         DATE NOT NULL comment '(DC2Type:date_immutable)',
                valid_until        DATE DEFAULT NULL comment '(DC2Type:date_immutable)',
                max_number_of_uses INT NOT NULL,
                UNIQUE INDEX uniq_93150748b19d0b94 (unique_code),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8
            COLLATE utf8_unicode_ci
            engine = innodb; 
SQL
        );

        $this->addSql('ALTER TABLE used_vouchers ADD CONSTRAINT fk_592812e728aa1b6f FOREIGN KEY (voucher_id) REFERENCES vouchers (id);');
        $this->addSql('ALTER TABLE used_vouchers ADD CONSTRAINT fk_592812e7a76ed395 FOREIGN KEY (user_id) REFERENCES fos_user_user (id);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE used_vouchers DROP FOREIGN KEY FK_592812E728AA1B6F');
        $this->addSql('ALTER TABLE used_vouchers DROP FOREIGN KEY FK_592812E7A76ED395');
        $this->addSql('DROP TABLE used_vouchers');
        $this->addSql('DROP TABLE vouchers');
    }
}
