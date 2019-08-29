<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190825181351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE public.group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE public.user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE public."group" (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE group_users (group_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(group_id, user_id))');
        $this->addSql('CREATE INDEX IDX_44AF8E8EFE54D947 ON group_users (group_id)');
        $this->addSql('CREATE INDEX IDX_44AF8E8EA76ED395 ON group_users (user_id)');
        $this->addSql('CREATE TABLE public."user" (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE group_users ADD CONSTRAINT FK_44AF8E8EFE54D947 FOREIGN KEY (group_id) REFERENCES public."group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_users ADD CONSTRAINT FK_44AF8E8EA76ED395 FOREIGN KEY (user_id) REFERENCES public."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE group_users DROP CONSTRAINT FK_44AF8E8EFE54D947');
        $this->addSql('ALTER TABLE group_users DROP CONSTRAINT FK_44AF8E8EA76ED395');
        $this->addSql('DROP SEQUENCE public.group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE public.user_id_seq CASCADE');
        $this->addSql('DROP TABLE public."group"');
        $this->addSql('DROP TABLE group_users');
        $this->addSql('DROP TABLE public."user"');
    }
}
