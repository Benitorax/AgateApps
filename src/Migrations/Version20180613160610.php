<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180613160610 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE portal_elements (id INTEGER NOT NULL, portal VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, button_text VARCHAR(255) NOT NULL, button_link VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX portal_and_locale ON portal_elements (portal, locale)');
        $this->addSql('CREATE TABLE fos_user_user (id INTEGER NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, email_confirmed BOOLEAN DEFAULT \'0\' NOT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , ulule_id VARCHAR(255) DEFAULT NULL, ulule_username VARCHAR(255) DEFAULT NULL, ulule_api_token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C560D76192FC23A8 ON fos_user_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C560D761A0D96FBF ON fos_user_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C560D761C05FB297 ON fos_user_user (confirmation_token)');
        $this->addSql('CREATE TABLE armors (id INTEGER NOT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL, protection SMALLINT NOT NULL, price SMALLINT NOT NULL, availability VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AFBA56C25E237E06 ON armors (name)');
        $this->addSql('CREATE INDEX IDX_AFBA56C216A2B381 ON armors (book_id)');
        $this->addSql('CREATE TABLE artifacts (id INTEGER NOT NULL, flux_id INTEGER NOT NULL, name VARCHAR(70) NOT NULL, description CLOB DEFAULT NULL, price SMALLINT NOT NULL, consumption SMALLINT NOT NULL, consumption_interval SMALLINT NOT NULL, tank SMALLINT DEFAULT NULL, resistance SMALLINT NOT NULL, vulnerability VARCHAR(255) DEFAULT NULL, handling VARCHAR(20) DEFAULT NULL, damage SMALLINT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_299E46885E237E06 ON artifacts (name)');
        $this->addSql('CREATE INDEX IDX_299E4688C85926E ON artifacts (flux_id)');
        $this->addSql('CREATE TABLE avantages (id INTEGER NOT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, name_female VARCHAR(50) NOT NULL, xp SMALLINT NOT NULL, description CLOB DEFAULT NULL, augmentation SMALLINT NOT NULL, bonusdisc VARCHAR(10) NOT NULL, is_desv BOOLEAN NOT NULL, is_combat_art BOOLEAN NOT NULL, avtg_group SMALLINT DEFAULT 0, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CBC7848D5E237E06 ON avantages (name)');
        $this->addSql('CREATE INDEX IDX_CBC7848D16A2B381 ON avantages (book_id)');
        $this->addSql('CREATE TABLE books (id INTEGER NOT NULL, name VARCHAR(80) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A1B2A925E237E06 ON books (name)');
        $this->addSql('CREATE TABLE characters_avantages (character_id INTEGER NOT NULL, advantage_id INTEGER NOT NULL, score INTEGER NOT NULL, PRIMARY KEY(character_id, advantage_id))');
        $this->addSql('CREATE INDEX IDX_BB5181061136BE75 ON characters_avantages (character_id)');
        $this->addSql('CREATE INDEX IDX_BB5181063864498A ON characters_avantages (advantage_id)');
        $this->addSql('CREATE TABLE characters_disciplines (character_id INTEGER NOT NULL, discipline_id INTEGER NOT NULL, domain_id INTEGER NOT NULL, score INTEGER NOT NULL, PRIMARY KEY(character_id, discipline_id, domain_id))');
        $this->addSql('CREATE INDEX IDX_50099411136BE75 ON characters_disciplines (character_id)');
        $this->addSql('CREATE INDEX IDX_5009941A5522701 ON characters_disciplines (discipline_id)');
        $this->addSql('CREATE INDEX IDX_5009941115F0EE5 ON characters_disciplines (domain_id)');
        $this->addSql('CREATE TABLE characters_domains (character_id INTEGER NOT NULL, domain_id INTEGER NOT NULL, score SMALLINT NOT NULL, bonus SMALLINT DEFAULT 0 NOT NULL, malus SMALLINT DEFAULT 0 NOT NULL, PRIMARY KEY(character_id, domain_id))');
        $this->addSql('CREATE INDEX IDX_C4F7C6C61136BE75 ON characters_domains (character_id)');
        $this->addSql('CREATE INDEX IDX_C4F7C6C6115F0EE5 ON characters_domains (domain_id)');
        $this->addSql('CREATE TABLE characters_flux (flux INTEGER NOT NULL, character_id INTEGER NOT NULL, quantity SMALLINT NOT NULL, PRIMARY KEY(character_id, flux))');
        $this->addSql('CREATE INDEX IDX_A1DA630E1136BE75 ON characters_flux (character_id)');
        $this->addSql('CREATE TABLE characters_setbacks (character_id INTEGER NOT NULL, setback_id INTEGER NOT NULL, is_avoided BOOLEAN NOT NULL, PRIMARY KEY(character_id, setback_id))');
        $this->addSql('CREATE INDEX IDX_97CD32521136BE75 ON characters_setbacks (character_id)');
        $this->addSql('CREATE INDEX IDX_97CD3252B42EEDE2 ON characters_setbacks (setback_id)');
        $this->addSql('CREATE TABLE characters_ways (character_id INTEGER NOT NULL, way_id INTEGER NOT NULL, score INTEGER NOT NULL, PRIMARY KEY(character_id, way_id))');
        $this->addSql('CREATE INDEX IDX_7AC056231136BE75 ON characters_ways (character_id)');
        $this->addSql('CREATE INDEX IDX_7AC056238C803113 ON characters_ways (way_id)');
        $this->addSql('CREATE TABLE characters (id INTEGER NOT NULL, geo_living_id INTEGER DEFAULT NULL, people_id INTEGER DEFAULT NULL, social_class_id INTEGER DEFAULT NULL, social_class_domain1_id INTEGER DEFAULT NULL, social_class_domain2_id INTEGER DEFAULT NULL, mental_disorder_id INTEGER DEFAULT NULL, job_id INTEGER DEFAULT NULL, birth_place_id INTEGER DEFAULT NULL, trait_flaw_id INTEGER DEFAULT NULL, trait_quality_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, game_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, player_name VARCHAR(255) NOT NULL, status SMALLINT DEFAULT 0 NOT NULL, sex VARCHAR(1) NOT NULL, description CLOB NOT NULL, story CLOB NOT NULL, facts CLOB NOT NULL, inventory CLOB NOT NULL --(DC2Type:simple_array)
        , treasures CLOB NOT NULL --(DC2Type:simple_array)
        , orientation VARCHAR(30) NOT NULL, trauma SMALLINT DEFAULT 0 NOT NULL, trauma_permanent SMALLINT DEFAULT 0 NOT NULL, hardening SMALLINT DEFAULT 0 NOT NULL, age SMALLINT NOT NULL, mental_resist SMALLINT NOT NULL, mental_resist_bonus SMALLINT NOT NULL, stamina SMALLINT NOT NULL, stamina_bonus SMALLINT NOT NULL, survival SMALLINT NOT NULL, speed SMALLINT NOT NULL, speed_bonus SMALLINT NOT NULL, defense SMALLINT NOT NULL, defense_bonus SMALLINT NOT NULL, rindath SMALLINT NOT NULL, rindathMax SMALLINT NOT NULL, exaltation SMALLINT NOT NULL, exaltation_max SMALLINT NOT NULL, experience_actual SMALLINT NOT NULL, experience_spent SMALLINT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, daol_ember INTEGER NOT NULL, daol_azure INTEGER NOT NULL, daol_frost INTEGER NOT NULL, health_good SMALLINT NOT NULL, health_okay SMALLINT NOT NULL, health_bad SMALLINT NOT NULL, health_critical SMALLINT NOT NULL, health_agony SMALLINT NOT NULL, max_health_good SMALLINT NOT NULL, max_health_okay SMALLINT NOT NULL, max_health_bad SMALLINT NOT NULL, max_health_critical SMALLINT NOT NULL, max_health_agony SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3A29410E8B7556B0 ON characters (geo_living_id)');
        $this->addSql('CREATE INDEX IDX_3A29410E3147C936 ON characters (people_id)');
        $this->addSql('CREATE INDEX IDX_3A29410E64319F3C ON characters (social_class_id)');
        $this->addSql('CREATE INDEX IDX_3A29410EC4BE6905 ON characters (social_class_domain1_id)');
        $this->addSql('CREATE INDEX IDX_3A29410ED60BC6EB ON characters (social_class_domain2_id)');
        $this->addSql('CREATE INDEX IDX_3A29410E46CBF851 ON characters (mental_disorder_id)');
        $this->addSql('CREATE INDEX IDX_3A29410EBE04EA9 ON characters (job_id)');
        $this->addSql('CREATE INDEX IDX_3A29410EB4BB6BBC ON characters (birth_place_id)');
        $this->addSql('CREATE INDEX IDX_3A29410E7C43360E ON characters (trait_flaw_id)');
        $this->addSql('CREATE INDEX IDX_3A29410E42FEF757 ON characters (trait_quality_id)');
        $this->addSql('CREATE INDEX IDX_3A29410EA76ED395 ON characters (user_id)');
        $this->addSql('CREATE INDEX IDX_3A29410EE48FD905 ON characters (game_id)');
        $this->addSql('CREATE UNIQUE INDEX idcUnique ON characters (name_slug, user_id)');
        $this->addSql('CREATE TABLE characters_armors (characters_id INTEGER NOT NULL, armors_id INTEGER NOT NULL, PRIMARY KEY(characters_id, armors_id))');
        $this->addSql('CREATE INDEX IDX_91F54665C70F0E28 ON characters_armors (characters_id)');
        $this->addSql('CREATE INDEX IDX_91F54665333F27F1 ON characters_armors (armors_id)');
        $this->addSql('CREATE TABLE characters_artifacts (characters_id INTEGER NOT NULL, artifacts_id INTEGER NOT NULL, PRIMARY KEY(characters_id, artifacts_id))');
        $this->addSql('CREATE INDEX IDX_59084303C70F0E28 ON characters_artifacts (characters_id)');
        $this->addSql('CREATE INDEX IDX_5908430338F3D9E1 ON characters_artifacts (artifacts_id)');
        $this->addSql('CREATE TABLE characters_miracles (characters_id INTEGER NOT NULL, miracles_id INTEGER NOT NULL, PRIMARY KEY(characters_id, miracles_id))');
        $this->addSql('CREATE INDEX IDX_977340CAC70F0E28 ON characters_miracles (characters_id)');
        $this->addSql('CREATE INDEX IDX_977340CA6B117C2B ON characters_miracles (miracles_id)');
        $this->addSql('CREATE TABLE characters_ogham (characters_id INTEGER NOT NULL, ogham_id INTEGER NOT NULL, PRIMARY KEY(characters_id, ogham_id))');
        $this->addSql('CREATE INDEX IDX_53F77947C70F0E28 ON characters_ogham (characters_id)');
        $this->addSql('CREATE INDEX IDX_53F779473241FF23 ON characters_ogham (ogham_id)');
        $this->addSql('CREATE TABLE characters_weapons (characters_id INTEGER NOT NULL, weapons_id INTEGER NOT NULL, PRIMARY KEY(characters_id, weapons_id))');
        $this->addSql('CREATE INDEX IDX_1A82C2BAC70F0E28 ON characters_weapons (characters_id)');
        $this->addSql('CREATE INDEX IDX_1A82C2BA2EE82581 ON characters_weapons (weapons_id)');
        $this->addSql('CREATE TABLE characters_combat_arts (characters_id INTEGER NOT NULL, combat_arts_id INTEGER NOT NULL, PRIMARY KEY(characters_id, combat_arts_id))');
        $this->addSql('CREATE INDEX IDX_4423FA34C70F0E28 ON characters_combat_arts (characters_id)');
        $this->addSql('CREATE INDEX IDX_4423FA342E5E3C78 ON characters_combat_arts (combat_arts_id)');
        $this->addSql('CREATE TABLE characters_status (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE combat_arts (id INTEGER NOT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL, ranged BOOLEAN NOT NULL, melee BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EC3E3FAD16A2B381 ON combat_arts (book_id)');
        $this->addSql('CREATE TABLE disciplines (id INTEGER NOT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL, rank VARCHAR(40) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD1D5CD85E237E06 ON disciplines (name)');
        $this->addSql('CREATE INDEX IDX_AD1D5CD816A2B381 ON disciplines (book_id)');
        $this->addSql('CREATE TABLE disciplines_domains (discipline_id INTEGER NOT NULL, domain_id INTEGER NOT NULL, PRIMARY KEY(discipline_id, domain_id))');
        $this->addSql('CREATE INDEX IDX_FE41FAE8A5522701 ON disciplines_domains (discipline_id)');
        $this->addSql('CREATE INDEX IDX_FE41FAE8115F0EE5 ON disciplines_domains (domain_id)');
        $this->addSql('CREATE TABLE disorders (id INTEGER NOT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(100) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A14FE96D5E237E06 ON disorders (name)');
        $this->addSql('CREATE INDEX IDX_A14FE96D16A2B381 ON disorders (book_id)');
        $this->addSql('CREATE TABLE disorders_ways (disorder_id INTEGER NOT NULL, way_id INTEGER NOT NULL, major BOOLEAN DEFAULT \'0\' NOT NULL, PRIMARY KEY(disorder_id, way_id))');
        $this->addSql('CREATE INDEX IDX_F2628E1787EB36AD ON disorders_ways (disorder_id)');
        $this->addSql('CREATE INDEX IDX_F2628E178C803113 ON disorders_ways (way_id)');
        $this->addSql('CREATE TABLE domains (id INTEGER NOT NULL, way_id INTEGER DEFAULT NULL, name VARCHAR(70) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C7BBF9D5E237E06 ON domains (name)');
        $this->addSql('CREATE INDEX IDX_8C7BBF9D8C803113 ON domains (way_id)');
        $this->addSql('CREATE TABLE flux (id INTEGER NOT NULL, name VARCHAR(70) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7252313A5E237E06 ON flux (name)');
        $this->addSql('CREATE TABLE games (id INTEGER NOT NULL, game_master_id INTEGER NOT NULL, name VARCHAR(140) NOT NULL, summary CLOB DEFAULT NULL, gm_notes CLOB DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, deleted DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FF232B31C1151A13 ON games (game_master_id)');
        $this->addSql('CREATE UNIQUE INDEX idgUnique ON games (name, game_master_id)');
        $this->addSql('CREATE TABLE geo_environments (id INTEGER NOT NULL, domain_id INTEGER DEFAULT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_18F4720A115F0EE5 ON geo_environments (domain_id)');
        $this->addSql('CREATE INDEX IDX_18F4720A16A2B381 ON geo_environments (book_id)');
        $this->addSql('CREATE TABLE jobs (id INTEGER NOT NULL, domain_primary_id INTEGER DEFAULT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(140) NOT NULL, description CLOB DEFAULT NULL, daily_salary INTEGER DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A8936DC55E237E06 ON jobs (name)');
        $this->addSql('CREATE INDEX IDX_A8936DC5B05C1029 ON jobs (domain_primary_id)');
        $this->addSql('CREATE INDEX IDX_A8936DC516A2B381 ON jobs (book_id)');
        $this->addSql('CREATE TABLE jobs_domains (jobs_id INTEGER NOT NULL, domains_id INTEGER NOT NULL, PRIMARY KEY(jobs_id, domains_id))');
        $this->addSql('CREATE INDEX IDX_FBB18A2C48704627 ON jobs_domains (jobs_id)');
        $this->addSql('CREATE INDEX IDX_FBB18A2C3700F4DC ON jobs_domains (domains_id)');
        $this->addSql('CREATE TABLE miracles (id INTEGER NOT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(70) NOT NULL, description CLOB DEFAULT NULL, is_major BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B8244CF5E237E06 ON miracles (name)');
        $this->addSql('CREATE INDEX IDX_6B8244CF16A2B381 ON miracles (book_id)');
        $this->addSql('CREATE TABLE ogham (id INTEGER NOT NULL, ogham_type_id INTEGER DEFAULT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(70) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_729005A05E237E06 ON ogham (name)');
        $this->addSql('CREATE INDEX IDX_729005A0D7050029 ON ogham (ogham_type_id)');
        $this->addSql('CREATE INDEX IDX_729005A016A2B381 ON ogham (book_id)');
        $this->addSql('CREATE TABLE ogham_types (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE peoples (id INTEGER NOT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C92B5C9C16A2B381 ON peoples (book_id)');
        $this->addSql('CREATE TABLE setbacks (id INTEGER NOT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL, malus VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B3C36575E237E06 ON setbacks (name)');
        $this->addSql('CREATE INDEX IDX_6B3C365716A2B381 ON setbacks (book_id)');
        $this->addSql('CREATE TABLE social_class (id INTEGER NOT NULL, name VARCHAR(25) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A7DBAD0D5E237E06 ON social_class (name)');
        $this->addSql('CREATE TABLE social_classes_domains (social_classes_id INTEGER NOT NULL, domains_id INTEGER NOT NULL, PRIMARY KEY(social_classes_id, domains_id))');
        $this->addSql('CREATE INDEX IDX_B915B07A5071E9E6 ON social_classes_domains (social_classes_id)');
        $this->addSql('CREATE INDEX IDX_B915B07A3700F4DC ON social_classes_domains (domains_id)');
        $this->addSql('CREATE TABLE traits (id INTEGER NOT NULL, way_id INTEGER DEFAULT NULL, book_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, name_female VARCHAR(50) NOT NULL, is_quality BOOLEAN NOT NULL, is_major BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E4A0A1668C803113 ON traits (way_id)');
        $this->addSql('CREATE INDEX IDX_E4A0A16616A2B381 ON traits (book_id)');
        $this->addSql('CREATE UNIQUE INDEX idxUnique ON traits (name, way_id)');
        $this->addSql('CREATE TABLE ways (id INTEGER NOT NULL, short_name VARCHAR(3) NOT NULL, name VARCHAR(40) NOT NULL, fault VARCHAR(40) NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A94804173EE4B093 ON ways (short_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A94804175E237E06 ON ways (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A94804179FD0DEA3 ON ways (fault)');
        $this->addSql('CREATE TABLE weapons (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, description CLOB DEFAULT NULL, damage SMALLINT NOT NULL, price SMALLINT NOT NULL, availability VARCHAR(5) NOT NULL, melee BOOLEAN NOT NULL, weapon_range SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_520EBBE15E237E06 ON weapons (name)');
        $this->addSql('CREATE TABLE events (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, percentage NUMERIC(8, 6) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5387574A5E237E06 ON events (name)');
        $this->addSql('CREATE TABLE events_foes (events_id INTEGER NOT NULL, foes_id INTEGER NOT NULL, PRIMARY KEY(events_id, foes_id))');
        $this->addSql('CREATE INDEX IDX_C15440C09D6A1065 ON events_foes (events_id)');
        $this->addSql('CREATE INDEX IDX_C15440C03DF0F043 ON events_foes (foes_id)');
        $this->addSql('CREATE TABLE events_npcs (events_id INTEGER NOT NULL, npcs_id INTEGER NOT NULL, PRIMARY KEY(events_id, npcs_id))');
        $this->addSql('CREATE INDEX IDX_45C02BE49D6A1065 ON events_npcs (events_id)');
        $this->addSql('CREATE INDEX IDX_45C02BE473DFFBCA ON events_npcs (npcs_id)');
        $this->addSql('CREATE TABLE events_weather (events_id INTEGER NOT NULL, weather_id INTEGER NOT NULL, PRIMARY KEY(events_id, weather_id))');
        $this->addSql('CREATE INDEX IDX_1AB1AA749D6A1065 ON events_weather (events_id)');
        $this->addSql('CREATE INDEX IDX_1AB1AA748CE675E ON events_weather (weather_id)');
        $this->addSql('CREATE TABLE events_markers (events_id INTEGER NOT NULL, markers_id INTEGER NOT NULL, PRIMARY KEY(events_id, markers_id))');
        $this->addSql('CREATE INDEX IDX_17E8A62A9D6A1065 ON events_markers (events_id)');
        $this->addSql('CREATE INDEX IDX_17E8A62AD0EEC2B5 ON events_markers (markers_id)');
        $this->addSql('CREATE TABLE events_markers_types (events_id INTEGER NOT NULL, markers_types_id INTEGER NOT NULL, PRIMARY KEY(events_id, markers_types_id))');
        $this->addSql('CREATE INDEX IDX_80FAF5E89D6A1065 ON events_markers_types (events_id)');
        $this->addSql('CREATE INDEX IDX_80FAF5E888F4A4BD ON events_markers_types (markers_types_id)');
        $this->addSql('CREATE TABLE events_resources (events_id INTEGER NOT NULL, resources_id INTEGER NOT NULL, PRIMARY KEY(events_id, resources_id))');
        $this->addSql('CREATE INDEX IDX_76928E8C9D6A1065 ON events_resources (events_id)');
        $this->addSql('CREATE INDEX IDX_76928E8CACFC5BFF ON events_resources (resources_id)');
        $this->addSql('CREATE TABLE events_routes (events_id INTEGER NOT NULL, routes_id INTEGER NOT NULL, PRIMARY KEY(events_id, routes_id))');
        $this->addSql('CREATE INDEX IDX_E068FB869D6A1065 ON events_routes (events_id)');
        $this->addSql('CREATE INDEX IDX_E068FB86AE2C16DC ON events_routes (routes_id)');
        $this->addSql('CREATE TABLE events_routes_types (events_id INTEGER NOT NULL, routes_types_id INTEGER NOT NULL, PRIMARY KEY(events_id, routes_types_id))');
        $this->addSql('CREATE INDEX IDX_7375FC699D6A1065 ON events_routes_types (events_id)');
        $this->addSql('CREATE INDEX IDX_7375FC6959514061 ON events_routes_types (routes_types_id)');
        $this->addSql('CREATE TABLE events_zones_types (events_id INTEGER NOT NULL, zones_types_id INTEGER NOT NULL, PRIMARY KEY(events_id, zones_types_id))');
        $this->addSql('CREATE INDEX IDX_7E6C54359D6A1065 ON events_zones_types (events_id)');
        $this->addSql('CREATE INDEX IDX_7E6C54357B85C61C ON events_zones_types (zones_types_id)');
        $this->addSql('CREATE TABLE events_zones (events_id INTEGER NOT NULL, zones_id INTEGER NOT NULL, PRIMARY KEY(events_id, zones_id))');
        $this->addSql('CREATE INDEX IDX_3576794E9D6A1065 ON events_zones (events_id)');
        $this->addSql('CREATE INDEX IDX_3576794EA6EAEB7A ON events_zones (zones_id)');
        $this->addSql('CREATE TABLE maps_factions (id INTEGER NOT NULL, book_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_354BB9A75E237E06 ON maps_factions (name)');
        $this->addSql('CREATE INDEX IDX_354BB9A716A2B381 ON maps_factions (book_id)');
        $this->addSql('CREATE TABLE maps_foes (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B9BE805E237E06 ON maps_foes (name)');
        $this->addSql('CREATE TABLE maps (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, max_zoom SMALLINT DEFAULT 1 NOT NULL, start_zoom SMALLINT DEFAULT 1 NOT NULL, start_x SMALLINT DEFAULT 1 NOT NULL, start_y SMALLINT DEFAULT 1 NOT NULL, bounds VARCHAR(255) DEFAULT \'[]\' NOT NULL, coordinates_ratio SMALLINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_472E08A55E237E06 ON maps (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_472E08A5DF2B4115 ON maps (name_slug)');
        $this->addSql('CREATE TABLE maps_markers (id INTEGER NOT NULL, faction_id INTEGER DEFAULT NULL, map_id INTEGER NOT NULL, marker_type_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, altitude VARCHAR(255) DEFAULT \'0\' NOT NULL, latitude VARCHAR(255) DEFAULT \'0\' NOT NULL, longitude VARCHAR(255) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33F679DD5E237E06 ON maps_markers (name)');
        $this->addSql('CREATE INDEX IDX_33F679DD4448F8DA ON maps_markers (faction_id)');
        $this->addSql('CREATE INDEX IDX_33F679DD53C55F64 ON maps_markers (map_id)');
        $this->addSql('CREATE INDEX IDX_33F679DDBFC01D99 ON maps_markers (marker_type_id)');
        $this->addSql('CREATE TABLE maps_markers_types (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, icon VARCHAR(255) NOT NULL, icon_width INTEGER NOT NULL, icon_height INTEGER NOT NULL, icon_center_x INTEGER DEFAULT NULL, icon_center_y INTEGER DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4AFA515E237E06 ON maps_markers_types (name)');
        $this->addSql('CREATE TABLE maps_npcs (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_842DD5A45E237E06 ON maps_npcs (name)');
        $this->addSql('CREATE TABLE maps_resources (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B0312AD5E237E06 ON maps_resources (name)');
        $this->addSql('CREATE TABLE resources_routes (resources_id INTEGER NOT NULL, routes_id INTEGER NOT NULL, PRIMARY KEY(resources_id, routes_id))');
        $this->addSql('CREATE INDEX IDX_389FB5C1ACFC5BFF ON resources_routes (resources_id)');
        $this->addSql('CREATE INDEX IDX_389FB5C1AE2C16DC ON resources_routes (routes_id)');
        $this->addSql('CREATE TABLE resources_routes_types (resources_id INTEGER NOT NULL, routes_types_id INTEGER NOT NULL, PRIMARY KEY(resources_id, routes_types_id))');
        $this->addSql('CREATE INDEX IDX_1EC06A03ACFC5BFF ON resources_routes_types (resources_id)');
        $this->addSql('CREATE INDEX IDX_1EC06A0359514061 ON resources_routes_types (routes_types_id)');
        $this->addSql('CREATE TABLE resources_zones_types (resources_id INTEGER NOT NULL, zones_types_id INTEGER NOT NULL, PRIMARY KEY(resources_id, zones_types_id))');
        $this->addSql('CREATE INDEX IDX_161ED520ACFC5BFF ON resources_zones_types (resources_id)');
        $this->addSql('CREATE INDEX IDX_161ED5207B85C61C ON resources_zones_types (zones_types_id)');
        $this->addSql('CREATE TABLE maps_routes (id INTEGER NOT NULL, marker_start_id INTEGER DEFAULT NULL, marker_end_id INTEGER DEFAULT NULL, map_id INTEGER NOT NULL, faction_id INTEGER DEFAULT NULL, route_type_id INTEGER NOT NULL, name VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, coordinates CLOB NOT NULL, distance DOUBLE PRECISION DEFAULT \'0\' NOT NULL, forced_distance DOUBLE PRECISION DEFAULT NULL, guarded BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4A14AA7582929C14 ON maps_routes (marker_start_id)');
        $this->addSql('CREATE INDEX IDX_4A14AA75476289B ON maps_routes (marker_end_id)');
        $this->addSql('CREATE INDEX IDX_4A14AA7553C55F64 ON maps_routes (map_id)');
        $this->addSql('CREATE INDEX IDX_4A14AA754448F8DA ON maps_routes (faction_id)');
        $this->addSql('CREATE INDEX IDX_4A14AA753D1FD10B ON maps_routes (route_type_id)');
        $this->addSql('CREATE TABLE maps_routes_types (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, color VARCHAR(75) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1006B6375E237E06 ON maps_routes_types (name)');
        $this->addSql('CREATE TABLE maps_routes_transports (id INTEGER NOT NULL, route_type_id INTEGER NOT NULL, transport_type_id INTEGER NOT NULL, percentage NUMERIC(9, 6) DEFAULT \'100\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DC8B306C3D1FD10B ON maps_routes_transports (route_type_id)');
        $this->addSql('CREATE INDEX IDX_DC8B306C519B4C62 ON maps_routes_transports (transport_type_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_route_transport ON maps_routes_transports (route_type_id, transport_type_id)');
        $this->addSql('CREATE TABLE maps_transports_types (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, speed NUMERIC(8, 4) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937FC7725E237E06 ON maps_transports_types (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_937FC772989D9B62 ON maps_transports_types (slug)');
        $this->addSql('CREATE TABLE maps_weather (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EAF75835E237E06 ON maps_weather (name)');
        $this->addSql('CREATE TABLE maps_zones (id INTEGER NOT NULL, map_id INTEGER NOT NULL, faction_id INTEGER DEFAULT NULL, zone_type_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, coordinates CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_436BD5205E237E06 ON maps_zones (name)');
        $this->addSql('CREATE INDEX IDX_436BD52053C55F64 ON maps_zones (map_id)');
        $this->addSql('CREATE INDEX IDX_436BD5204448F8DA ON maps_zones (faction_id)');
        $this->addSql('CREATE INDEX IDX_436BD5207B788FAB ON maps_zones (zone_type_id)');
        $this->addSql('CREATE TABLE maps_zones_types (id INTEGER NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, color VARCHAR(75) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4AD3285E237E06 ON maps_zones_types (name)');
        $this->addSql('CREATE INDEX IDX_B4AD328727ACA70 ON maps_zones_types (parent_id)');
    }

    public function down(Schema $schema) : void
    {
    }
}
