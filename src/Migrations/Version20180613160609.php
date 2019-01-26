<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * DEV/PROD DATABASE.
 */
final class Version20180613160609 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on "mysql".');

        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');

        $this->addSql('
        CREATE TABLE `armors` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `protection` smallint(6) NOT NULL,
          `price` smallint(6) NOT NULL,
          `availability` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_AFBA56C25E237E06` (`name`),
          KEY `IDX_AFBA56C216A2B381` (`book_id`),
          CONSTRAINT `FK_AFBA56C216A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `artifacts` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `flux_id` int(11) NOT NULL,
          `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `price` smallint(6) NOT NULL,
          `consumption` smallint(6) NOT NULL,
          `consumption_interval` smallint(6) NOT NULL,
          `tank` smallint(6) DEFAULT NULL,
          `resistance` smallint(6) NOT NULL,
          `vulnerability` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `handling` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
          `damage` smallint(6) DEFAULT NULL,
          `created` datetime NOT NULL,
          `updated` datetime NOT NULL,
          `deleted` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_299E46885E237E06` (`name`),
          KEY `IDX_299E4688C85926E` (`flux_id`),
          CONSTRAINT `FK_299E4688C85926E` FOREIGN KEY (`flux_id`) REFERENCES `flux` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `avantages` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `name_female` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `xp` smallint(6) NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `augmentation` smallint(6) NOT NULL,
          `bonusdisc` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
          `is_desv` tinyint(1) NOT NULL,
          `is_combat_art` tinyint(1) NOT NULL,
          `avtg_group` smallint(6) DEFAULT '0',
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_CBC7848D5E237E06` (`name`),
          KEY `IDX_CBC7848D16A2B381` (`book_id`),
          CONSTRAINT `FK_CBC7848D16A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `books` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_4A1B2A925E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `characters` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `people_id` int(11) DEFAULT NULL,
          `social_class_domain1_id` int(11) DEFAULT NULL,
          `social_class_domain2_id` int(11) DEFAULT NULL,
          `job_id` int(11) DEFAULT NULL,
          `trait_flaw_id` int(11) DEFAULT NULL,
          `trait_quality_id` int(11) DEFAULT NULL,
          `user_id` int(11) DEFAULT NULL,
          `game_id` int(11) DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `name_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `player_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `status` smallint(6) NOT NULL DEFAULT '0',
          `sex` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci NOT NULL,
          `story` longtext COLLATE utf8_unicode_ci NOT NULL,
          `facts` longtext COLLATE utf8_unicode_ci NOT NULL,
          `inventory` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:simple_array)',
          `orientation` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
          `trauma` smallint(6) NOT NULL DEFAULT '0',
          `trauma_permanent` smallint(6) NOT NULL DEFAULT '0',
          `hardening` smallint(6) NOT NULL DEFAULT '0',
          `age` smallint(6) NOT NULL,
          `mental_resist` smallint(6) NOT NULL,
          `stamina` smallint(6) NOT NULL,
          `survival` smallint(6) NOT NULL,
          `speed` smallint(6) NOT NULL,
          `defense` smallint(6) NOT NULL,
          `rindath` smallint(6) NOT NULL,
          `exaltation` smallint(6) NOT NULL,
          `experience_actual` smallint(6) NOT NULL,
          `experience_spent` smallint(6) NOT NULL,
          `created` datetime NOT NULL,
          `updated` datetime NOT NULL,
          `deleted` datetime DEFAULT NULL,
          `daol_ember` int(11) NOT NULL,
          `daol_azure` int(11) NOT NULL,
          `daol_frost` int(11) NOT NULL,
          `geo_living_id` int(11) DEFAULT NULL,
          `social_class_id` int(11) DEFAULT NULL,
          `mental_disorder_id` int(11) DEFAULT NULL,
          `birth_place_id` int(11) DEFAULT NULL,
          `mental_resist_bonus` smallint(6) NOT NULL,
          `stamina_bonus` smallint(6) NOT NULL,
          `speed_bonus` smallint(6) NOT NULL,
          `defense_bonus` smallint(6) NOT NULL,
          `rindathMax` smallint(6) NOT NULL,
          `exaltation_max` smallint(6) NOT NULL,
          `health_good` smallint(6) NOT NULL,
          `health_okay` smallint(6) NOT NULL,
          `health_bad` smallint(6) NOT NULL,
          `health_critical` smallint(6) NOT NULL,
          `health_agony` smallint(6) NOT NULL,
          `max_health_good` smallint(6) NOT NULL,
          `max_health_okay` smallint(6) NOT NULL,
          `max_health_bad` smallint(6) NOT NULL,
          `max_health_critical` smallint(6) NOT NULL,
          `max_health_agony` smallint(6) NOT NULL,
          `treasures` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:simple_array)',
          PRIMARY KEY (`id`),
          UNIQUE KEY `idcUnique` (`name_slug`,`user_id`),
          KEY `IDX_3A29410E3147C936` (`people_id`),
          KEY `IDX_3A29410EC4BE6905` (`social_class_domain1_id`),
          KEY `IDX_3A29410ED60BC6EB` (`social_class_domain2_id`),
          KEY `IDX_3A29410EBE04EA9` (`job_id`),
          KEY `IDX_3A29410E7C43360E` (`trait_flaw_id`),
          KEY `IDX_3A29410E42FEF757` (`trait_quality_id`),
          KEY `IDX_3A29410EA76ED395` (`user_id`),
          KEY `IDX_3A29410EE48FD905` (`game_id`),
          KEY `IDX_3A29410E8B7556B0` (`geo_living_id`),
          KEY `IDX_3A29410E64319F3C` (`social_class_id`),
          KEY `IDX_3A29410E46CBF851` (`mental_disorder_id`),
          KEY `IDX_3A29410EB4BB6BBC` (`birth_place_id`),
          CONSTRAINT `FK_3A29410E3147C936` FOREIGN KEY (`people_id`) REFERENCES `peoples` (`id`),
          CONSTRAINT `FK_3A29410E42FEF757` FOREIGN KEY (`trait_quality_id`) REFERENCES `traits` (`id`),
          CONSTRAINT `FK_3A29410E46CBF851` FOREIGN KEY (`mental_disorder_id`) REFERENCES `disorders` (`id`),
          CONSTRAINT `FK_3A29410E64319F3C` FOREIGN KEY (`social_class_id`) REFERENCES `social_class` (`id`),
          CONSTRAINT `FK_3A29410E7C43360E` FOREIGN KEY (`trait_flaw_id`) REFERENCES `traits` (`id`),
          CONSTRAINT `FK_3A29410E8B7556B0` FOREIGN KEY (`geo_living_id`) REFERENCES `geo_environments` (`id`),
          CONSTRAINT `FK_3A29410EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_user` (`id`),
          CONSTRAINT `FK_3A29410EB4BB6BBC` FOREIGN KEY (`birth_place_id`) REFERENCES `maps_zones` (`id`),
          CONSTRAINT `FK_3A29410EBE04EA9` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
          CONSTRAINT `FK_3A29410EC4BE6905` FOREIGN KEY (`social_class_domain1_id`) REFERENCES `domains` (`id`),
          CONSTRAINT `FK_3A29410ED60BC6EB` FOREIGN KEY (`social_class_domain2_id`) REFERENCES `domains` (`id`),
          CONSTRAINT `FK_3A29410EE48FD905` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `characters_armors` (
          `characters_id` int(11) NOT NULL,
          `armors_id` int(11) NOT NULL,
          PRIMARY KEY (`characters_id`,`armors_id`),
          KEY `IDX_91F54665C70F0E28` (`characters_id`),
          KEY `IDX_91F54665333F27F1` (`armors_id`),
          CONSTRAINT `FK_91F54665333F27F1` FOREIGN KEY (`armors_id`) REFERENCES `armors` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_91F54665C70F0E28` FOREIGN KEY (`characters_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_artifacts` (
          `characters_id` int(11) NOT NULL,
          `artifacts_id` int(11) NOT NULL,
          PRIMARY KEY (`characters_id`,`artifacts_id`),
          KEY `IDX_59084303C70F0E28` (`characters_id`),
          KEY `IDX_5908430338F3D9E1` (`artifacts_id`),
          CONSTRAINT `FK_5908430338F3D9E1` FOREIGN KEY (`artifacts_id`) REFERENCES `artifacts` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_59084303C70F0E28` FOREIGN KEY (`characters_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_avantages` (
          `character_id` int(11) NOT NULL,
          `advantage_id` int(11) NOT NULL,
          `score` int(11) NOT NULL,
          PRIMARY KEY (`character_id`,`advantage_id`),
          KEY `IDX_BB5181061136BE75` (`character_id`),
          KEY `IDX_BB5181063864498A` (`advantage_id`),
          CONSTRAINT `FK_BB5181061136BE75` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`),
          CONSTRAINT `FK_BB5181063864498A` FOREIGN KEY (`advantage_id`) REFERENCES `avantages` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_combat_arts` (
          `characters_id` int(11) NOT NULL,
          `combat_arts_id` int(11) NOT NULL,
          PRIMARY KEY (`characters_id`,`combat_arts_id`),
          KEY `IDX_4423FA34C70F0E28` (`characters_id`),
          KEY `IDX_4423FA342E5E3C78` (`combat_arts_id`),
          CONSTRAINT `FK_4423FA342E5E3C78` FOREIGN KEY (`combat_arts_id`) REFERENCES `combat_arts` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_4423FA34C70F0E28` FOREIGN KEY (`characters_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_disciplines` (
          `character_id` int(11) NOT NULL,
          `discipline_id` int(11) NOT NULL,
          `domain_id` int(11) NOT NULL,
          `score` int(11) NOT NULL,
          PRIMARY KEY (`character_id`,`discipline_id`,`domain_id`),
          KEY `IDX_50099411136BE75` (`character_id`),
          KEY `IDX_5009941A5522701` (`discipline_id`),
          KEY `IDX_5009941115F0EE5` (`domain_id`),
          CONSTRAINT `FK_50099411136BE75` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`),
          CONSTRAINT `FK_5009941115F0EE5` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`),
          CONSTRAINT `FK_5009941A5522701` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `characters_domains` (
          `character_id` int(11) NOT NULL,
          `domain_id` int(11) NOT NULL,
          `score` smallint(6) NOT NULL,
          `bonus` smallint(6) NOT NULL DEFAULT '0',
          `malus` smallint(6) NOT NULL DEFAULT '0',
          PRIMARY KEY (`character_id`,`domain_id`),
          KEY `IDX_C4F7C6C61136BE75` (`character_id`),
          KEY `IDX_C4F7C6C6115F0EE5` (`domain_id`),
          CONSTRAINT `FK_C4F7C6C61136BE75` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`),
          CONSTRAINT `FK_C4F7C6C6115F0EE5` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `characters_flux` (
          `character_id` int(11) NOT NULL,
          `flux` int(11) NOT NULL,
          `quantity` smallint(6) NOT NULL,
          PRIMARY KEY (`character_id`,`flux`),
          KEY `IDX_A1DA630E1136BE75` (`character_id`),
          CONSTRAINT `FK_A1DA630E1136BE75` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_miracles` (
          `characters_id` int(11) NOT NULL,
          `miracles_id` int(11) NOT NULL,
          PRIMARY KEY (`characters_id`,`miracles_id`),
          KEY `IDX_977340CAC70F0E28` (`characters_id`),
          KEY `IDX_977340CA6B117C2B` (`miracles_id`),
          CONSTRAINT `FK_977340CA6B117C2B` FOREIGN KEY (`miracles_id`) REFERENCES `miracles` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_977340CAC70F0E28` FOREIGN KEY (`characters_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_ogham` (
          `characters_id` int(11) NOT NULL,
          `ogham_id` int(11) NOT NULL,
          PRIMARY KEY (`characters_id`,`ogham_id`),
          KEY `IDX_53F77947C70F0E28` (`characters_id`),
          KEY `IDX_53F779473241FF23` (`ogham_id`),
          CONSTRAINT `FK_53F779473241FF23` FOREIGN KEY (`ogham_id`) REFERENCES `ogham` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_53F77947C70F0E28` FOREIGN KEY (`characters_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_setbacks` (
          `character_id` int(11) NOT NULL,
          `setback_id` int(11) NOT NULL,
          `is_avoided` tinyint(1) NOT NULL,
          PRIMARY KEY (`character_id`,`setback_id`),
          KEY `IDX_97CD32521136BE75` (`character_id`),
          KEY `IDX_97CD3252B42EEDE2` (`setback_id`),
          CONSTRAINT `FK_97CD32521136BE75` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`),
          CONSTRAINT `FK_97CD3252B42EEDE2` FOREIGN KEY (`setback_id`) REFERENCES `setbacks` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_status` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_ways` (
          `character_id` int(11) NOT NULL,
          `way_id` int(11) NOT NULL,
          `score` int(11) NOT NULL,
          PRIMARY KEY (`character_id`,`way_id`),
          KEY `IDX_7AC056231136BE75` (`character_id`),
          KEY `IDX_7AC056238C803113` (`way_id`),
          CONSTRAINT `FK_7AC056231136BE75` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`),
          CONSTRAINT `FK_7AC056238C803113` FOREIGN KEY (`way_id`) REFERENCES `ways` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `characters_weapons` (
          `characters_id` int(11) NOT NULL,
          `weapons_id` int(11) NOT NULL,
          PRIMARY KEY (`characters_id`,`weapons_id`),
          KEY `IDX_1A82C2BAC70F0E28` (`characters_id`),
          KEY `IDX_1A82C2BA2EE82581` (`weapons_id`),
          CONSTRAINT `FK_1A82C2BA2EE82581` FOREIGN KEY (`weapons_id`) REFERENCES `weapons` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_1A82C2BAC70F0E28` FOREIGN KEY (`characters_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `combat_arts` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci NOT NULL,
          `ranged` tinyint(1) NOT NULL,
          `melee` tinyint(1) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `IDX_EC3E3FAD16A2B381` (`book_id`),
          CONSTRAINT `FK_EC3E3FAD16A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `disciplines` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `rank` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_AD1D5CD85E237E06` (`name`),
          KEY `IDX_AD1D5CD816A2B381` (`book_id`),
          CONSTRAINT `FK_AD1D5CD816A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `disciplines_domains` (
          `discipline_id` int(11) NOT NULL,
          `domain_id` int(11) NOT NULL,
          PRIMARY KEY (`discipline_id`,`domain_id`),
          KEY `IDX_FE41FAE8A5522701` (`discipline_id`),
          KEY `IDX_FE41FAE8115F0EE5` (`domain_id`),
          CONSTRAINT `FK_FE41FAE8115F0EE5` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`),
          CONSTRAINT `FK_FE41FAE8A5522701` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `disorders` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_A14FE96D5E237E06` (`name`),
          KEY `IDX_A14FE96D16A2B381` (`book_id`),
          CONSTRAINT `FK_A14FE96D16A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `disorders_ways` (
          `disorder_id` int(11) NOT NULL,
          `way_id` int(11) NOT NULL,
          `major` tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`disorder_id`,`way_id`),
          KEY `IDX_F2628E1787EB36AD` (`disorder_id`),
          KEY `IDX_F2628E178C803113` (`way_id`),
          CONSTRAINT `FK_F2628E1787EB36AD` FOREIGN KEY (`disorder_id`) REFERENCES `disorders` (`id`),
          CONSTRAINT `FK_F2628E178C803113` FOREIGN KEY (`way_id`) REFERENCES `ways` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `domains` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `way_id` int(11) DEFAULT NULL,
          `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_8C7BBF9D5E237E06` (`name`),
          KEY `IDX_8C7BBF9D8C803113` (`way_id`),
          CONSTRAINT `FK_8C7BBF9D8C803113` FOREIGN KEY (`way_id`) REFERENCES `ways` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `percentage` decimal(8,6) NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_5387574A5E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_foes` (
          `events_id` int(11) NOT NULL,
          `foes_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`foes_id`),
          KEY `IDX_C15440C09D6A1065` (`events_id`),
          KEY `IDX_C15440C03DF0F043` (`foes_id`),
          CONSTRAINT `FK_C15440C03DF0F043` FOREIGN KEY (`foes_id`) REFERENCES `maps_foes` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_C15440C09D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_markers` (
          `events_id` int(11) NOT NULL,
          `markers_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`markers_id`),
          KEY `IDX_17E8A62A9D6A1065` (`events_id`),
          KEY `IDX_17E8A62AD0EEC2B5` (`markers_id`),
          CONSTRAINT `FK_17E8A62A9D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_17E8A62AD0EEC2B5` FOREIGN KEY (`markers_id`) REFERENCES `maps_markers` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_markers_types` (
          `events_id` int(11) NOT NULL,
          `markers_types_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`markers_types_id`),
          KEY `IDX_80FAF5E89D6A1065` (`events_id`),
          KEY `IDX_80FAF5E888F4A4BD` (`markers_types_id`),
          CONSTRAINT `FK_80FAF5E888F4A4BD` FOREIGN KEY (`markers_types_id`) REFERENCES `maps_markers_types` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_80FAF5E89D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_npcs` (
          `events_id` int(11) NOT NULL,
          `npcs_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`npcs_id`),
          KEY `IDX_45C02BE49D6A1065` (`events_id`),
          KEY `IDX_45C02BE473DFFBCA` (`npcs_id`),
          CONSTRAINT `FK_45C02BE473DFFBCA` FOREIGN KEY (`npcs_id`) REFERENCES `maps_npcs` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_45C02BE49D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_resources` (
          `events_id` int(11) NOT NULL,
          `resources_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`resources_id`),
          KEY `IDX_76928E8C9D6A1065` (`events_id`),
          KEY `IDX_76928E8CACFC5BFF` (`resources_id`),
          CONSTRAINT `FK_76928E8C9D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_76928E8CACFC5BFF` FOREIGN KEY (`resources_id`) REFERENCES `maps_resources` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_routes` (
          `events_id` int(11) NOT NULL,
          `routes_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`routes_id`),
          KEY `IDX_E068FB869D6A1065` (`events_id`),
          KEY `IDX_E068FB86AE2C16DC` (`routes_id`),
          CONSTRAINT `FK_E068FB869D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_E068FB86AE2C16DC` FOREIGN KEY (`routes_id`) REFERENCES `maps_routes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_routes_types` (
          `events_id` int(11) NOT NULL,
          `routes_types_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`routes_types_id`),
          KEY `IDX_7375FC699D6A1065` (`events_id`),
          KEY `IDX_7375FC6959514061` (`routes_types_id`),
          CONSTRAINT `FK_7375FC6959514061` FOREIGN KEY (`routes_types_id`) REFERENCES `maps_routes_types` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_7375FC699D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_weather` (
          `events_id` int(11) NOT NULL,
          `weather_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`weather_id`),
          KEY `IDX_1AB1AA749D6A1065` (`events_id`),
          KEY `IDX_1AB1AA748CE675E` (`weather_id`),
          CONSTRAINT `FK_1AB1AA748CE675E` FOREIGN KEY (`weather_id`) REFERENCES `maps_weather` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_1AB1AA749D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_zones` (
          `events_id` int(11) NOT NULL,
          `zones_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`zones_id`),
          KEY `IDX_3576794E9D6A1065` (`events_id`),
          KEY `IDX_3576794EA6EAEB7A` (`zones_id`),
          CONSTRAINT `FK_3576794E9D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_3576794EA6EAEB7A` FOREIGN KEY (`zones_id`) REFERENCES `maps_zones` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `events_zones_types` (
          `events_id` int(11) NOT NULL,
          `zones_types_id` int(11) NOT NULL,
          PRIMARY KEY (`events_id`,`zones_types_id`),
          KEY `IDX_7E6C54359D6A1065` (`events_id`),
          KEY `IDX_7E6C54357B85C61C` (`zones_types_id`),
          CONSTRAINT `FK_7E6C54357B85C61C` FOREIGN KEY (`zones_types_id`) REFERENCES `maps_zones_types` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_7E6C54359D6A1065` FOREIGN KEY (`events_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `flux` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_7252313A5E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `fos_user_user` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          `email_confirmed` tinyint(1) NOT NULL DEFAULT '0',
          `ulule_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `ulule_api_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `ulule_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_C560D76192FC23A8` (`username_canonical`),
          UNIQUE KEY `UNIQ_C560D761A0D96FBF` (`email_canonical`),
          UNIQUE KEY `UNIQ_C560D761C05FB297` (`confirmation_token`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `games` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `game_master_id` int(11) NOT NULL,
          `name` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
          `summary` longtext COLLATE utf8_unicode_ci,
          `gm_notes` longtext COLLATE utf8_unicode_ci,
          `created` datetime NOT NULL,
          `updated` datetime NOT NULL,
          `deleted` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `idgUnique` (`name`,`game_master_id`),
          KEY `IDX_FF232B31C1151A13` (`game_master_id`),
          CONSTRAINT `FK_FF232B31C1151A13` FOREIGN KEY (`game_master_id`) REFERENCES `fos_user_user` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `geo_environments` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `domain_id` int(11) DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          KEY `IDX_18F4720A16A2B381` (`book_id`),
          KEY `IDX_18F4720A115F0EE5` (`domain_id`),
          CONSTRAINT `FK_18F4720A115F0EE5` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`),
          CONSTRAINT `FK_18F4720A16A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `jobs` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `domain_primary_id` int(11) DEFAULT NULL,
          `name` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `daily_salary` int(11) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_A8936DC55E237E06` (`name`),
          KEY `IDX_A8936DC516A2B381` (`book_id`),
          KEY `IDX_A8936DC5B05C1029` (`domain_primary_id`),
          CONSTRAINT `FK_A8936DC516A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
          CONSTRAINT `FK_A8936DC5B05C1029` FOREIGN KEY (`domain_primary_id`) REFERENCES `domains` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `jobs_domains` (
          `jobs_id` int(11) NOT NULL,
          `domains_id` int(11) NOT NULL,
          PRIMARY KEY (`jobs_id`,`domains_id`),
          KEY `IDX_FBB18A2C48704627` (`jobs_id`),
          KEY `IDX_FBB18A2C3700F4DC` (`domains_id`),
          CONSTRAINT `FK_FBB18A2C3700F4DC` FOREIGN KEY (`domains_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_FBB18A2C48704627` FOREIGN KEY (`jobs_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `mails` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `content` longtext COLLATE utf8_unicode_ci NOT NULL,
          `subject` longtext COLLATE utf8_unicode_ci NOT NULL,
          `created` datetime NOT NULL,
          `updated` datetime NOT NULL,
          `deleted` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_6358200577153098` (`code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `mails_sent` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `mail_id` int(11) DEFAULT NULL,
          `to_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `to_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `subject` longtext COLLATE utf8_unicode_ci NOT NULL,
          `content` longtext COLLATE utf8_unicode_ci NOT NULL,
          `created` datetime NOT NULL,
          `updated` datetime NOT NULL,
          `deleted` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `IDX_A34C3D90C8776F01` (`mail_id`),
          CONSTRAINT `FK_A34C3D90C8776F01` FOREIGN KEY (`mail_id`) REFERENCES `mails` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `maps` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `name_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `max_zoom` smallint(6) NOT NULL DEFAULT '1',
          `start_zoom` smallint(6) NOT NULL DEFAULT '1',
          `start_x` smallint(6) NOT NULL DEFAULT '1',
          `start_y` smallint(6) NOT NULL DEFAULT '1',
          `bounds` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '[]',
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          `coordinates_ratio` smallint(6) NOT NULL DEFAULT '1',
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_472E08A55E237E06` (`name`),
          UNIQUE KEY `UNIQ_472E08A5DF2B4115` (`name_slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `maps_factions` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_354BB9A75E237E06` (`name`),
          KEY `IDX_354BB9A716A2B381` (`book_id`),
          CONSTRAINT `FK_354BB9A716A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `maps_foes` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_B9BE805E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `maps_markers` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `faction_id` int(11) DEFAULT NULL,
          `map_id` int(11) NOT NULL,
          `marker_type_id` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `altitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
          `latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
          `longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_33F679DD5E237E06` (`name`),
          KEY `IDX_33F679DD4448F8DA` (`faction_id`),
          KEY `IDX_33F679DD53C55F64` (`map_id`),
          KEY `IDX_33F679DDBFC01D99` (`marker_type_id`),
          CONSTRAINT `FK_33F679DD4448F8DA` FOREIGN KEY (`faction_id`) REFERENCES `maps_factions` (`id`),
          CONSTRAINT `FK_33F679DD53C55F64` FOREIGN KEY (`map_id`) REFERENCES `maps` (`id`),
          CONSTRAINT `FK_33F679DDBFC01D99` FOREIGN KEY (`marker_type_id`) REFERENCES `maps_markers_types` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `maps_markers_types` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `icon_width` int(11) NOT NULL,
          `icon_height` int(11) NOT NULL,
          `icon_center_x` int(11) DEFAULT NULL,
          `icon_center_y` int(11) DEFAULT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_C4AFA515E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `maps_npcs` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_842DD5A45E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `maps_resources` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_3B0312AD5E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `maps_routes` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `marker_start_id` int(11) DEFAULT NULL,
          `marker_end_id` int(11) DEFAULT NULL,
          `map_id` int(11) NOT NULL,
          `faction_id` int(11) DEFAULT NULL,
          `route_type_id` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `coordinates` longtext COLLATE utf8_unicode_ci NOT NULL,
          `distance` double NOT NULL DEFAULT '0',
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          `guarded` tinyint(1) NOT NULL,
          `forced_distance` double DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `IDX_4A14AA7582929C14` (`marker_start_id`),
          KEY `IDX_4A14AA75476289B` (`marker_end_id`),
          KEY `IDX_4A14AA7553C55F64` (`map_id`),
          KEY `IDX_4A14AA754448F8DA` (`faction_id`),
          KEY `IDX_4A14AA753D1FD10B` (`route_type_id`),
          CONSTRAINT `FK_4A14AA753D1FD10B` FOREIGN KEY (`route_type_id`) REFERENCES `maps_routes_types` (`id`),
          CONSTRAINT `FK_4A14AA754448F8DA` FOREIGN KEY (`faction_id`) REFERENCES `maps_factions` (`id`),
          CONSTRAINT `FK_4A14AA75476289B` FOREIGN KEY (`marker_end_id`) REFERENCES `maps_markers` (`id`),
          CONSTRAINT `FK_4A14AA7553C55F64` FOREIGN KEY (`map_id`) REFERENCES `maps` (`id`),
          CONSTRAINT `FK_4A14AA7582929C14` FOREIGN KEY (`marker_start_id`) REFERENCES `maps_markers` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql("
        CREATE TABLE `maps_routes_transports` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `route_type_id` int(11) NOT NULL,
          `transport_type_id` int(11) NOT NULL,
          `percentage` decimal(9,6) NOT NULL DEFAULT '100.000000',
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `unique_route_transport` (`route_type_id`,`transport_type_id`),
          KEY `IDX_DC8B306C3D1FD10B` (`route_type_id`),
          KEY `IDX_DC8B306C519B4C62` (`transport_type_id`),
          CONSTRAINT `FK_DC8B306C3D1FD10B` FOREIGN KEY (`route_type_id`) REFERENCES `maps_routes_types` (`id`),
          CONSTRAINT `FK_DC8B306C519B4C62` FOREIGN KEY (`transport_type_id`) REFERENCES `maps_transports_types` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `maps_routes_types` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `color` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_1006B6375E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `maps_transports_types` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `speed` decimal(8,4) NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_937FC7725E237E06` (`name`),
          UNIQUE KEY `UNIQ_937FC772989D9B62` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `maps_weather` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_3EAF75835E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `maps_zones` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `map_id` int(11) NOT NULL,
          `faction_id` int(11) DEFAULT NULL,
          `zone_type_id` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `coordinates` longtext COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_436BD5205E237E06` (`name`),
          KEY `IDX_436BD52053C55F64` (`map_id`),
          KEY `IDX_436BD5204448F8DA` (`faction_id`),
          KEY `IDX_436BD5207B788FAB` (`zone_type_id`),
          CONSTRAINT `FK_436BD5204448F8DA` FOREIGN KEY (`faction_id`) REFERENCES `maps_factions` (`id`),
          CONSTRAINT `FK_436BD52053C55F64` FOREIGN KEY (`map_id`) REFERENCES `maps` (`id`),
          CONSTRAINT `FK_436BD5207B788FAB` FOREIGN KEY (`zone_type_id`) REFERENCES `maps_zones_types` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `maps_zones_types` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `parent_id` int(11) DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `color` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_B4AD3285E237E06` (`name`),
          KEY `IDX_B4AD328727ACA70` (`parent_id`),
          CONSTRAINT `FK_B4AD328727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `maps_zones_types` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `miracles` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `is_major` tinyint(1) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_6B8244CF5E237E06` (`name`),
          KEY `IDX_6B8244CF16A2B381` (`book_id`),
          CONSTRAINT `FK_6B8244CF16A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `ogham` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `ogham_type_id` int(11) DEFAULT NULL,
          `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_729005A05E237E06` (`name`),
          KEY `IDX_729005A016A2B381` (`book_id`),
          KEY `IDX_729005A0D7050029` (`ogham_type_id`),
          CONSTRAINT `FK_729005A016A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
          CONSTRAINT `FK_729005A0D7050029` FOREIGN KEY (`ogham_type_id`) REFERENCES `ogham_types` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `ogham_types` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `orbitale_cms_categories` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `parent_id` int(11) DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `enabled` tinyint(1) NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_A8EF7232989D9B62` (`slug`),
          KEY `IDX_A8EF7232727ACA70` (`parent_id`),
          CONSTRAINT `FK_A8EF7232727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `orbitale_cms_categories` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql("
        CREATE TABLE `orbitale_cms_pages` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `category_id` int(11) DEFAULT NULL,
          `parent_id` int(11) DEFAULT NULL,
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `page_content` longtext COLLATE utf8_unicode_ci,
          `meta_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `meta_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `meta_keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `css` longtext COLLATE utf8_unicode_ci,
          `js` longtext COLLATE utf8_unicode_ci,
          `enabled` tinyint(1) NOT NULL,
          `homepage` tinyint(1) NOT NULL,
          `host` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `created_at` datetime NOT NULL,
          `locale` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
          `show_title` tinyint(1) NOT NULL DEFAULT '1',
          `container_css_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_C0E694ED989D9B62` (`slug`),
          KEY `IDX_C0E694ED12469DE2` (`category_id`),
          KEY `IDX_C0E694ED727ACA70` (`parent_id`),
          CONSTRAINT `FK_C0E694ED12469DE2` FOREIGN KEY (`category_id`) REFERENCES `orbitale_cms_categories` (`id`),
          CONSTRAINT `FK_C0E694ED727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `orbitale_cms_pages` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->addSql('
        CREATE TABLE `peoples` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          KEY `IDX_C92B5C9C16A2B381` (`book_id`),
          CONSTRAINT `FK_C92B5C9C16A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `portal_elements` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `portal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `subtitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `button_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `button_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `portal_and_locale` (`portal`,`locale`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `regions` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `kingdom` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
          `coordinates` longtext COLLATE utf8_unicode_ci NOT NULL,
          `created` datetime NOT NULL,
          `updated` datetime NOT NULL,
          `deleted` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_A26779F35E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `resources_routes` (
          `resources_id` int(11) NOT NULL,
          `routes_id` int(11) NOT NULL,
          PRIMARY KEY (`resources_id`,`routes_id`),
          KEY `IDX_389FB5C1ACFC5BFF` (`resources_id`),
          KEY `IDX_389FB5C1AE2C16DC` (`routes_id`),
          CONSTRAINT `FK_389FB5C1ACFC5BFF` FOREIGN KEY (`resources_id`) REFERENCES `maps_resources` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_389FB5C1AE2C16DC` FOREIGN KEY (`routes_id`) REFERENCES `maps_routes` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `resources_routes_types` (
          `resources_id` int(11) NOT NULL,
          `routes_types_id` int(11) NOT NULL,
          PRIMARY KEY (`resources_id`,`routes_types_id`),
          KEY `IDX_1EC06A03ACFC5BFF` (`resources_id`),
          KEY `IDX_1EC06A0359514061` (`routes_types_id`),
          CONSTRAINT `FK_1EC06A0359514061` FOREIGN KEY (`routes_types_id`) REFERENCES `maps_routes_types` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_1EC06A03ACFC5BFF` FOREIGN KEY (`resources_id`) REFERENCES `maps_resources` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `resources_zones_types` (
          `resources_id` int(11) NOT NULL,
          `zones_types_id` int(11) NOT NULL,
          PRIMARY KEY (`resources_id`,`zones_types_id`),
          KEY `IDX_161ED520ACFC5BFF` (`resources_id`),
          KEY `IDX_161ED5207B85C61C` (`zones_types_id`),
          CONSTRAINT `FK_161ED5207B85C61C` FOREIGN KEY (`zones_types_id`) REFERENCES `maps_zones_types` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_161ED520ACFC5BFF` FOREIGN KEY (`resources_id`) REFERENCES `maps_resources` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `setbacks` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `malus` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_6B3C36575E237E06` (`name`),
          KEY `IDX_6B3C365716A2B381` (`book_id`),
          CONSTRAINT `FK_6B3C365716A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `social_class` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_A7DBAD0D5E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `social_classes_domains` (
          `social_classes_id` int(11) NOT NULL,
          `domains_id` int(11) NOT NULL,
          PRIMARY KEY (`social_classes_id`,`domains_id`),
          KEY `IDX_B915B07A5071E9E6` (`social_classes_id`),
          KEY `IDX_B915B07A3700F4DC` (`domains_id`),
          CONSTRAINT `FK_B915B07A3700F4DC` FOREIGN KEY (`domains_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE,
          CONSTRAINT `FK_B915B07A5071E9E6` FOREIGN KEY (`social_classes_id`) REFERENCES `social_class` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `traits` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `way_id` int(11) DEFAULT NULL,
          `book_id` int(11) DEFAULT NULL,
          `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `name_female` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `is_quality` tinyint(1) NOT NULL,
          `is_major` tinyint(1) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `idxUnique` (`name`,`way_id`),
          KEY `IDX_E4A0A1668C803113` (`way_id`),
          KEY `IDX_E4A0A16616A2B381` (`book_id`),
          CONSTRAINT `FK_E4A0A16616A2B381` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
          CONSTRAINT `FK_E4A0A1668C803113` FOREIGN KEY (`way_id`) REFERENCES `ways` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `ways` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `short_name` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
          `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
          `fault` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_A94804173EE4B093` (`short_name`),
          UNIQUE KEY `UNIQ_A94804175E237E06` (`name`),
          UNIQUE KEY `UNIQ_A94804179FD0DEA3` (`fault`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('
        CREATE TABLE `weapons` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
          `description` longtext COLLATE utf8_unicode_ci,
          `damage` smallint(6) NOT NULL,
          `price` smallint(6) NOT NULL,
          `availability` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
          `melee` tinyint(1) NOT NULL,
          `weapon_range` smallint(6) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `UNIQ_520EBBE15E237E06` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(Schema $schema): void
    {
    }
}
