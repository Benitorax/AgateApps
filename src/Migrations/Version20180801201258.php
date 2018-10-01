<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\Avantages;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180801201258 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on "mysql".');

        $conn = $this->connection;
        $simpleArray = Type::getType(Type::SIMPLE_ARRAY);

        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EC4BE6905');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410ED60BC6EB');
        $this->addSql('ALTER TABLE characters_disciplines DROP FOREIGN KEY FK_5009941115F0EE5');
        $this->addSql('ALTER TABLE characters_domains DROP FOREIGN KEY FK_C4F7C6C6115F0EE5');
        $this->addSql('ALTER TABLE disciplines_domains DROP FOREIGN KEY FK_FE41FAE8115F0EE5');
        $this->addSql('ALTER TABLE geo_environments DROP FOREIGN KEY FK_18F4720A115F0EE5');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5B05C1029');
        $this->addSql('ALTER TABLE jobs_domains DROP FOREIGN KEY FK_FBB18A2C3700F4DC');
        $this->addSql('ALTER TABLE social_classes_domains DROP FOREIGN KEY FK_B915B07A3700F4DC');
        $this->addSql('DROP INDEX IDX_18F4720A115F0EE5 ON geo_environments');
        $this->addSql('DROP INDEX IDX_A8936DC5B05C1029 ON jobs');
        $this->addSql('DROP INDEX IDX_3A29410EC4BE6905 ON characters');
        $this->addSql('DROP INDEX IDX_3A29410ED60BC6EB ON characters');
        $this->addSql('DROP INDEX IDX_5009941115F0EE5 ON characters_disciplines');

        $this->addSql('ALTER TABLE characters_disciplines DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE characters_disciplines ADD PRIMARY KEY (character_id, discipline_id)');

        $this->addSql('ALTER TABLE characters_avantages ADD indication VARCHAR(255) NOT NULL');

        $this->addSql('
            ALTER TABLE avantages
            ADD bonuses_for LONGTEXT DEFAULT NULL COMMENT "(DC2Type:simple_array)",
            ADD requires_indication VARCHAR(255) DEFAULT NULL,
            ADD indication_type VARCHAR(20) NOT NULL DEFAULT "'.Avantages::INDICATION_TYPE_SINGLE_VALUE.'",
            CHANGE augmentation augmentation_count SMALLINT(6) NOT NULL DEFAULT \'0\',
            CHANGE avtg_group avtg_group VARCHAR(255) DEFAULT NULL
        ');
        $this->addSql('ALTER TABLE disciplines ADD domains LONGTEXT COMMENT "(DC2Type:simple_array)"');
        $this->addSql('ALTER TABLE characters_disciplines ADD domain VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE geo_environments ADD domain VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE jobs ADD primary_domain VARCHAR(100) NOT NULL, ADD secondary_domains LONGTEXT COMMENT "(DC2Type:simple_array)"');
        $this->addSql('ALTER TABLE social_class ADD domains LONGTEXT NOT NULL COMMENT "(DC2Type:simple_array)"');

        $this->addSql('CREATE TABLE setbacks_advantages (setback_id INT NOT NULL, advantage_id INT NOT NULL, INDEX IDX_A85197DFB42EEDE2 (setback_id), INDEX IDX_A85197DF3864498A (advantage_id), PRIMARY KEY(setback_id, advantage_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE setbacks_advantages ADD CONSTRAINT FK_A85197DFB42EEDE2 FOREIGN KEY (setback_id) REFERENCES setbacks (id)');
        $this->addSql('ALTER TABLE setbacks_advantages ADD CONSTRAINT FK_A85197DF3864498A FOREIGN KEY (advantage_id) REFERENCES avantages (id)');

        $this->addSql('
            ALTER TABLE characters 
            ADD social_class_domain1 VARCHAR(100) NOT NULL, 
            ADD social_class_domain2 VARCHAR(100) NOT NULL, 
            ADD way_combativeness INT NOT NULL, 
            ADD way_creativity INT NOT NULL, 
            ADD way_empathy INT NOT NULL, 
            ADD way_reason INT NOT NULL, 
            ADD way_conviction INT NOT NULL, 
            ADD domain_craft SMALLINT NOT NULL, 
            ADD domain_craft_bonus SMALLINT NOT NULL, 
            ADD domain_craft_malus SMALLINT NOT NULL, 
            ADD domain_close_combat SMALLINT NOT NULL, 
            ADD domain_close_combat_bonus SMALLINT NOT NULL, 
            ADD domain_close_combat_malus SMALLINT NOT NULL, 
            ADD domain_stealth SMALLINT NOT NULL, 
            ADD domain_stealth_bonus SMALLINT NOT NULL, 
            ADD domain_stealth_malus SMALLINT NOT NULL, 
            ADD domain_magience SMALLINT NOT NULL, 
            ADD domain_magience_bonus SMALLINT NOT NULL, 
            ADD domain_magience_malus SMALLINT NOT NULL, 
            ADD domain_natural_environment SMALLINT NOT NULL, 
            ADD domain_natural_environment_bonus SMALLINT NOT NULL, 
            ADD domain_natural_environment_malus SMALLINT NOT NULL, 
            ADD domain_demorthen_mysteries SMALLINT NOT NULL, 
            ADD domain_demorthen_mysteries_bonus SMALLINT NOT NULL, 
            ADD domain_demorthen_mysteries_malus SMALLINT NOT NULL, 
            ADD domain_occultism SMALLINT NOT NULL, 
            ADD domain_occultism_bonus SMALLINT NOT NULL, 
            ADD domain_occultism_malus SMALLINT NOT NULL, 
            ADD domain_perception SMALLINT NOT NULL, 
            ADD domain_perception_bonus SMALLINT NOT NULL, 
            ADD domain_perception_malus SMALLINT NOT NULL, 
            ADD domain_prayer SMALLINT NOT NULL, 
            ADD domain_prayer_bonus SMALLINT NOT NULL, 
            ADD domain_prayer_malus SMALLINT NOT NULL, 
            ADD domain_feats SMALLINT NOT NULL, 
            ADD domain_feats_bonus SMALLINT NOT NULL, 
            ADD domain_feats_malus SMALLINT NOT NULL, 
            ADD domain_relation SMALLINT NOT NULL, 
            ADD domain_relation_bonus SMALLINT NOT NULL, 
            ADD domain_relation_malus SMALLINT NOT NULL, 
            ADD domain_performance SMALLINT NOT NULL, 
            ADD domain_performance_bonus SMALLINT NOT NULL, 
            ADD domain_performance_malus SMALLINT NOT NULL, 
            ADD domain_science SMALLINT NOT NULL, 
            ADD domain_science_bonus SMALLINT NOT NULL, 
            ADD domain_science_malus SMALLINT NOT NULL, 
            ADD domain_shooting_and_throwing SMALLINT NOT NULL, 
            ADD domain_shooting_and_throwing_bonus SMALLINT NOT NULL, 
            ADD domain_shooting_and_throwing_malus SMALLINT NOT NULL, 
            ADD domain_travel SMALLINT NOT NULL, 
            ADD domain_travel_bonus SMALLINT NOT NULL, 
            ADD domain_travel_malus SMALLINT NOT NULL, 
            ADD domain_erudition SMALLINT NOT NULL, 
            ADD domain_erudition_bonus SMALLINT NOT NULL, 
            ADD domain_erudition_malus SMALLINT NOT NULL, 
            DROP social_class_domain1_id, 
            DROP social_class_domain2_id, 
            DROP combativeness, 
            DROP creativity, 
            DROP empathy, 
            DROP reason, 
            DROP conviction 
        ');

        $bonusesIdsReplacers = [
            1 => DomainsData::CRAFT['title'],
            2 => DomainsData::CLOSE_COMBAT['title'],
            3 => DomainsData::STEALTH['title'],
            4 => DomainsData::MAGIENCE['title'],
            5 => DomainsData::NATURAL_ENVIRONMENT['title'],
            6 => DomainsData::DEMORTHEN_MYSTERIES['title'],
            7 => DomainsData::OCCULTISM['title'],
            8 => DomainsData::PERCEPTION['title'],
            9 => DomainsData::PRAYER['title'],
            10 => DomainsData::FEATS['title'],
            11 => DomainsData::RELATION['title'],
            12 => DomainsData::PERFORMANCE['title'],
            13 => DomainsData::SCIENCE['title'],
            14 => DomainsData::SHOOTING_AND_THROWING['title'],
            15 => DomainsData::TRAVEL['title'],
            16 => DomainsData::ERUDITION['title'],
            'rap' => 'speed',
            'resm' => 'mental_resistance',
            'bless' => 'health',
            'vig' => 'stamina',
            'trau' => 'trauma',
            'sur' => 'survival',
            '100g' => 'money_frost_100',
            '50g' => 'money_frost_50',
            '20g' => 'money_frost_20',
            '10g' => 'money_frost_10',
            '50a' => 'money_azure_50',
            '20a' => 'money_azure_20',
        ];

        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[1], 'id' => 1]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[2], 'id' => 2]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[3], 'id' => 3]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[4], 'id' => 4]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[5], 'id' => 5]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[6], 'id' => 6]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[7], 'id' => 7]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[8], 'id' => 8]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[9], 'id' => 9]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[10], 'id' => 10]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[11], 'id' => 11]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[12], 'id' => 12]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[13], 'id' => 13]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[14], 'id' => 14]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[15], 'id' => 15]);
        $this->addSql('UPDATE characters_disciplines SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[16], 'id' => 16]);

        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[1], 'id' => 1]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[2], 'id' => 2]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[3], 'id' => 3]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[4], 'id' => 4]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[5], 'id' => 5]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[6], 'id' => 6]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[7], 'id' => 7]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[8], 'id' => 8]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[9], 'id' => 9]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[10], 'id' => 10]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[11], 'id' => 11]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[12], 'id' => 12]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[13], 'id' => 13]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[14], 'id' => 14]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[15], 'id' => 15]);
        $this->addSql('UPDATE geo_environments SET domain = :domain WHERE domain_id = :id', [':domain' => $bonusesIdsReplacers[16], 'id' => 16]);

        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[1], 'id' => 1]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[2], 'id' => 2]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[3], 'id' => 3]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[4], 'id' => 4]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[5], 'id' => 5]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[6], 'id' => 6]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[7], 'id' => 7]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[8], 'id' => 8]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[9], 'id' => 9]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[10], 'id' => 10]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[11], 'id' => 11]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[12], 'id' => 12]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[13], 'id' => 13]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[14], 'id' => 14]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[15], 'id' => 15]);
        $this->addSql('UPDATE jobs SET primary_domain = :domain WHERE domain_primary_id = :id', [':domain' => $bonusesIdsReplacers[16], 'id' => 16]);

        // "Poverty" setback disables "Financial ease" and "Poor" disadvantages.
        if (1 === $conn->query('select id from setbacks where id = 9')->rowCount()) {
            $this->addSql('INSERT INTO setbacks_advantages SET setback_id = 9, advantage_id = 4;');
            $this->addSql('INSERT INTO setbacks_advantages SET setback_id = 9, advantage_id = 5;');
            $this->addSql('INSERT INTO setbacks_advantages SET setback_id = 9, advantage_id = 6;');
            $this->addSql('INSERT INTO setbacks_advantages SET setback_id = 9, advantage_id = 7;');
            $this->addSql('INSERT INTO setbacks_advantages SET setback_id = 9, advantage_id = 8;');
            $this->addSql('INSERT INTO setbacks_advantages SET setback_id = 9, advantage_id = 47;');
        }

        $newDisciplinesDomains = [];
        $disciplinesDomains = $conn->query('select discipline_id, domain_id from disciplines_domains')->fetchAll();
        foreach ($disciplinesDomains as $line) {
            $newDisciplinesDomains[$line['discipline_id']][] = $bonusesIdsReplacers[$line['domain_id']];
        }
        foreach ($newDisciplinesDomains as $id => $domains) {
            $convertedDomains = $simpleArray->convertToDatabaseValue($domains, $conn->getDatabasePlatform());
            $this->addSql('UPDATE disciplines SET domains = :domains WHERE id = :id', [':domains' => $convertedDomains, ':id' => $id]);
        }

        $newSocialClassesSecondaryDomains = [];
        $socialClassesSecondaryDomains = $conn->query('select social_classes_id, domains_id from social_classes_domains')->fetchAll();
        foreach ($socialClassesSecondaryDomains as $line) {
            $newSocialClassesSecondaryDomains[$line['social_classes_id']][] = $bonusesIdsReplacers[$line['domains_id']];
        }
        foreach ($newSocialClassesSecondaryDomains as $id => $domains) {
            $convertedDomains = $simpleArray->convertToDatabaseValue($domains, $conn->getDatabasePlatform());
            $this->addSql('UPDATE social_class SET domains = :domains WHERE id = :id', [':domains' => $convertedDomains, ':id' => $id]);
        }

        $newJobsSecondaryDomains = [];
        $jobsSecondaryDomains = $conn->query('select jobs_id, domains_id from jobs_domains')->fetchAll();
        foreach ($jobsSecondaryDomains as $line) {
            $newJobsSecondaryDomains[$line['jobs_id']][] = $bonusesIdsReplacers[$line['domains_id']];
        }
        foreach ($newJobsSecondaryDomains as $id => $domains) {
            $convertedDomains = $simpleArray->convertToDatabaseValue($domains, $conn->getDatabasePlatform());
            $this->addSql('UPDATE jobs SET secondary_domains = :domains WHERE id = :id', [':domains' => $convertedDomains, ':id' => $id]);
        }

        $newAdvantagesBonuses = [];
        foreach ($conn->query('select id, bonusdisc from avantages')->fetchAll() as $line) {
            if (!\trim($line['bonusdisc'])) {
                continue;
            }
            $bonuses = \explode(',', $line['bonusdisc']);
            foreach ($bonuses as $k => $bonus) {
                if (!isset($bonusesIdsReplacers[$bonus])) {
                    continue;
                }
                $bonuses[$k] = $bonusesIdsReplacers[$bonus];
            }
            if ($bonuses) {
                $newAdvantagesBonuses[$line['id']] = $bonuses;
            }
        }
        foreach ($newAdvantagesBonuses as $id => $bonuses) {
            $convertedBonuses = $simpleArray->convertToDatabaseValue($bonuses, $conn->getDatabasePlatform());
            $this->addSql('UPDATE avantages SET bonuses_for = :bonuses WHERE id = :id', [':bonuses' => $convertedBonuses, ':id' => $id]);
        }

        foreach ($bonusesIdsReplacers as $id => $replacer) {
            $this->addSql('UPDATE setbacks SET malus = :replacer WHERE malus = :value', [':value' => $id, ':replacer' => $replacer]);
        }

        $this->addSql('ALTER TABLE characters_disciplines DROP domain_id');
        $this->addSql('ALTER TABLE geo_environments DROP domain_id');
        $this->addSql('ALTER TABLE avantages DROP bonusdisc');
        $this->addSql('ALTER TABLE jobs DROP domain_primary_id');

        $this->addSql('DROP TABLE characters_domains');
        $this->addSql('DROP TABLE disciplines_domains');
        $this->addSql('DROP TABLE domains');
        $this->addSql('DROP TABLE jobs_domains');
        $this->addSql('DROP TABLE social_classes_domains');

        // Prod data updates

        // Advantages that need indications from the end-user
        $this->addSql('UPDATE avantages SET requires_indication = :value WHERE id = :id', [':value' => 'advantages.indication.ally_isolated', ':id' => 1]);
        $this->addSql('UPDATE avantages SET requires_indication = :value WHERE id = :id', [':value' => 'advantages.indication.ally_mentor', ':id' => 2]);
        $this->addSql('UPDATE avantages SET requires_indication = :value WHERE id = :id', [':value' => 'advantages.indication.ally_influent', ':id' => 3]);
        $this->addSql('UPDATE avantages SET requires_indication = :value WHERE id = :id', [':value' => 'advantages.indication.dependence', ':id' => 32]);
        $this->addSql('UPDATE avantages SET requires_indication = :value WHERE id = :id', [':value' => 'advantages.indication.enemy', ':id' => 34]);
        $this->addSql('UPDATE avantages SET requires_indication = :value WHERE id = :id', [':value' => 'advantages.indication.phobia', ':id' => 48]);
        $this->addSql('UPDATE avantages SET bonuses_for = :value WHERE id = :id', [':value' => 'luck', ':id' => 21]);
        $this->addSql('UPDATE avantages SET bonuses_for = :value WHERE id = :id', [':value' => 'luck', ':id' => 43]);
        $this->addSql('UPDATE avantages SET augmentation_count = :value WHERE id = :id', [':value' => 2, ':id' => 50]);

        $ids = [1, 2, 3];
        $placeholders = \implode(', ', \array_fill(0, \count($ids), '?'));
        \array_unshift($ids, 'advantages.group.ally');
        $this->addSql(
            "UPDATE avantages SET avtg_group = ? WHERE id IN ($placeholders)",
            $ids
        );

        $ids = [4, 5, 6, 7, 8];
        $placeholders = \implode(', ', \array_fill(0, \count($ids), '?'));
        \array_unshift($ids, 'advantages.group.financial_ease');
        $this->addSql(
            "UPDATE avantages SET avtg_group = ? WHERE id IN ($placeholders)",
            $ids
        );

        // Scholar special case (which is the inspiration for the indication system, basically)
        $this->addSql('
            UPDATE avantages SET
            requires_indication = :indication,
            indication_type = :indication_type,
            bonuses_for = :bonuses_for
            WHERE id = :id',
            [
                ':indication' => 'advantages.indication.scholar',
                ':indication_type' => Avantages::INDICATION_TYPE_SINGLE_CHOICE,
                ':bonuses_for' => $simpleArray->convertToDatabaseValue([
                    DomainsData::ERUDITION['title'],
                    DomainsData::SCIENCE['title'],
                    DomainsData::MAGIENCE['title'],
                    DomainsData::OCCULTISM['title'],
                ], $conn->getDatabasePlatform()),
                ':id' => 23,
            ]
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on "mysql".');

        $this->addSql('CREATE TABLE characters_domains (character_id INT NOT NULL, domain_id INT NOT NULL, score SMALLINT NOT NULL, bonus SMALLINT DEFAULT 0 NOT NULL, malus SMALLINT DEFAULT 0 NOT NULL, INDEX IDX_C4F7C6C61136BE75 (character_id), INDEX IDX_C4F7C6C6115F0EE5 (domain_id), PRIMARY KEY(character_id, domain_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disciplines_domains (discipline_id INT NOT NULL, domain_id INT NOT NULL, INDEX IDX_FE41FAE8A5522701 (discipline_id), INDEX IDX_FE41FAE8115F0EE5 (domain_id), PRIMARY KEY(discipline_id, domain_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domains (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(70) NOT NULL COLLATE utf8_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, way VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_8C7BBF9D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs_domains (jobs_id INT NOT NULL, domains_id INT NOT NULL, INDEX IDX_FBB18A2C48704627 (jobs_id), INDEX IDX_FBB18A2C3700F4DC (domains_id), PRIMARY KEY(jobs_id, domains_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_classes_domains (social_classes_id INT NOT NULL, domains_id INT NOT NULL, INDEX IDX_B915B07A5071E9E6 (social_classes_id), INDEX IDX_B915B07A3700F4DC (domains_id), PRIMARY KEY(social_classes_id, domains_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE avantages ADD bonus_disc LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT "(DC2Type:simple_array)", DROP bonuses_for, DROP requires_indication');
        $this->addSql('ALTER TABLE characters_domains ADD CONSTRAINT FK_C4F7C6C61136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_domains ADD CONSTRAINT FK_C4F7C6C6115F0EE5 FOREIGN KEY (domain_id) REFERENCES domains (id)');
        $this->addSql('ALTER TABLE disciplines_domains ADD CONSTRAINT FK_FE41FAE8115F0EE5 FOREIGN KEY (domain_id) REFERENCES domains (id)');
        $this->addSql('ALTER TABLE disciplines_domains ADD CONSTRAINT FK_FE41FAE8A5522701 FOREIGN KEY (discipline_id) REFERENCES disciplines (id)');
        $this->addSql('ALTER TABLE jobs_domains ADD CONSTRAINT FK_FBB18A2C3700F4DC FOREIGN KEY (domains_id) REFERENCES domains (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs_domains ADD CONSTRAINT FK_FBB18A2C48704627 FOREIGN KEY (jobs_id) REFERENCES jobs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE social_classes_domains ADD CONSTRAINT FK_B915B07A3700F4DC FOREIGN KEY (domains_id) REFERENCES domains (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE social_classes_domains ADD CONSTRAINT FK_B915B07A5071E9E6 FOREIGN KEY (social_classes_id) REFERENCES social_class (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE characters ADD social_class_domain1_id INT DEFAULT NULL, ADD social_class_domain2_id INT DEFAULT NULL, ADD combativeness INT NOT NULL, ADD creativity INT NOT NULL, ADD empathy INT NOT NULL, ADD reason INT NOT NULL, ADD conviction INT NOT NULL, DROP social_class_domain1, DROP social_class_domain2, DROP way_combativeness, DROP way_creativity, DROP way_empathy, DROP way_reason, DROP way_conviction, DROP domain_craft, DROP domain_craft_bonus, DROP domain_craft_malus, DROP domain_close_combat, DROP domain_close_combat_bonus, DROP domain_close_combat_malus, DROP domain_stealth, DROP domain_stealth_bonus, DROP domain_stealth_malus, DROP domain_magience, DROP domain_magience_bonus, DROP domain_magience_malus, DROP domain_natural_environment, DROP domain_natural_environment_bonus, DROP domain_natural_environment_malus, DROP domain_demorthen_mysteries, DROP domain_demorthen_mysteries_bonus, DROP domain_demorthen_mysteries_malus, DROP domain_occultism, DROP domain_occultism_bonus, DROP domain_occultism_malus, DROP domain_perception, DROP domain_perception_bonus, DROP domain_perception_malus, DROP domain_prayer, DROP domain_prayer_bonus, DROP domain_prayer_malus, DROP domain_feats, DROP domain_feats_bonus, DROP domain_feats_malus, DROP domain_relation, DROP domain_relation_bonus, DROP domain_relation_malus, DROP domain_performance, DROP domain_performance_bonus, DROP domain_performance_malus, DROP domain_science, DROP domain_science_bonus, DROP domain_science_malus, DROP domain_shooting_and_throwing, DROP domain_shooting_and_throwing_bonus, DROP domain_shooting_and_throwing_malus, DROP domain_travel, DROP domain_travel_bonus, DROP domain_travel_malus, DROP domain_erudition, DROP domain_erudition_bonus, DROP domain_erudition_malus');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EC4BE6905 FOREIGN KEY (social_class_domain1_id) REFERENCES domains (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410ED60BC6EB FOREIGN KEY (social_class_domain2_id) REFERENCES domains (id)');
        $this->addSql('CREATE INDEX IDX_3A29410EC4BE6905 ON characters (social_class_domain1_id)');
        $this->addSql('CREATE INDEX IDX_3A29410ED60BC6EB ON characters (social_class_domain2_id)');
        $this->addSql('ALTER TABLE characters_disciplines DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE characters_disciplines ADD domain_id INT NOT NULL, DROP domain');
        $this->addSql('ALTER TABLE characters_disciplines ADD CONSTRAINT FK_5009941115F0EE5 FOREIGN KEY (domain_id) REFERENCES domains (id)');
        $this->addSql('CREATE INDEX IDX_5009941115F0EE5 ON characters_disciplines (domain_id)');
        $this->addSql('ALTER TABLE characters_disciplines ADD PRIMARY KEY (character_id, discipline_id, domain_id)');
        $this->addSql('ALTER TABLE disciplines DROP domains');
        $this->addSql('ALTER TABLE geo_environments ADD domain_id INT DEFAULT NULL, DROP domain');
        $this->addSql('ALTER TABLE geo_environments ADD CONSTRAINT FK_18F4720A115F0EE5 FOREIGN KEY (domain_id) REFERENCES domains (id)');
        $this->addSql('CREATE INDEX IDX_18F4720A115F0EE5 ON geo_environments (domain_id)');
        $this->addSql('ALTER TABLE jobs ADD domain_primary_id INT DEFAULT NULL, DROP primary_domain, DROP secondary_domains');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5B05C1029 FOREIGN KEY (domain_primary_id) REFERENCES domains (id)');
        $this->addSql('CREATE INDEX IDX_A8936DC5B05C1029 ON jobs (domain_primary_id)');
        $this->addSql('ALTER TABLE social_class DROP domains');
        $this->addSql('DROP TABLE setbacks_advantages');
    }
}
