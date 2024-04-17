<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328032337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD63453B6268F');
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F753B6268F');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9353B6268F');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939853B6268F');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495553B6268F');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123F53B6268F');
        $this->addSql('ALTER TABLE `table` DROP FOREIGN KEY FK_F6298F4653B6268F');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64953B6268F');
        $this->addSql('DROP TABLE statistic');
        $this->addSql('DROP INDEX IDX_497DD63453B6268F ON categorie');
        $this->addSql('ALTER TABLE categorie DROP statistic_id');
        $this->addSql('DROP INDEX IDX_D43829F753B6268F ON food');
        $this->addSql('ALTER TABLE food DROP statistic_id');
        $this->addSql('DROP INDEX IDX_7D053A9353B6268F ON menu');
        $this->addSql('ALTER TABLE menu DROP statistic_id');
        $this->addSql('DROP INDEX IDX_F529939853B6268F ON `order`');
        $this->addSql('ALTER TABLE `order` DROP statistic_id');
        $this->addSql('DROP INDEX IDX_42C8495553B6268F ON reservation');
        $this->addSql('ALTER TABLE reservation DROP statistic_id');
        $this->addSql('DROP INDEX IDX_EB95123F53B6268F ON restaurant');
        $this->addSql('ALTER TABLE restaurant DROP statistic_id');
        $this->addSql('DROP INDEX IDX_F6298F4653B6268F ON `table`');
        $this->addSql('ALTER TABLE `table` DROP statistic_id');
        $this->addSql('DROP INDEX IDX_8D93D64953B6268F ON user');
        $this->addSql('ALTER TABLE user DROP statistic_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE statistic (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE categorie ADD statistic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD63453B6268F FOREIGN KEY (statistic_id) REFERENCES statistic (id)');
        $this->addSql('CREATE INDEX IDX_497DD63453B6268F ON categorie (statistic_id)');
        $this->addSql('ALTER TABLE food ADD statistic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F753B6268F FOREIGN KEY (statistic_id) REFERENCES statistic (id)');
        $this->addSql('CREATE INDEX IDX_D43829F753B6268F ON food (statistic_id)');
        $this->addSql('ALTER TABLE menu ADD statistic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9353B6268F FOREIGN KEY (statistic_id) REFERENCES statistic (id)');
        $this->addSql('CREATE INDEX IDX_7D053A9353B6268F ON menu (statistic_id)');
        $this->addSql('ALTER TABLE `order` ADD statistic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939853B6268F FOREIGN KEY (statistic_id) REFERENCES statistic (id)');
        $this->addSql('CREATE INDEX IDX_F529939853B6268F ON `order` (statistic_id)');
        $this->addSql('ALTER TABLE reservation ADD statistic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495553B6268F FOREIGN KEY (statistic_id) REFERENCES statistic (id)');
        $this->addSql('CREATE INDEX IDX_42C8495553B6268F ON reservation (statistic_id)');
        $this->addSql('ALTER TABLE restaurant ADD statistic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123F53B6268F FOREIGN KEY (statistic_id) REFERENCES statistic (id)');
        $this->addSql('CREATE INDEX IDX_EB95123F53B6268F ON restaurant (statistic_id)');
        $this->addSql('ALTER TABLE `table` ADD statistic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F4653B6268F FOREIGN KEY (statistic_id) REFERENCES statistic (id)');
        $this->addSql('CREATE INDEX IDX_F6298F4653B6268F ON `table` (statistic_id)');
        $this->addSql('ALTER TABLE user ADD statistic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64953B6268F FOREIGN KEY (statistic_id) REFERENCES statistic (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64953B6268F ON user (statistic_id)');
    }
}
