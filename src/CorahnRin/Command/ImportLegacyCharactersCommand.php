<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Command;

use CorahnRin\Entity\Character;
use CorahnRin\Entity\CharacterProperties\Ways as CharWays;
use CorahnRin\Entity\Game;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use User\Entity\User;
use User\Repository\UserRepository;

class ImportLegacyCharactersCommand extends Command
{
    protected static $defaultName = 'corahnrin:legacy-import:characters';

    private $managerRegistry;
    private $userManager;

    /** @var ObjectManager */
    private $em;

    /** @var Connection */
    private $legacyConnection;

    /** @var SymfonyStyle */
    private $io;

    /** @var InputInterface */
    private $input;

    /** @var \User\Entity\User[] */
    private $users = [];

    /** @var EntityRepository[] */
    private $repositories = [];

    /** @var Game[] */
    private $games = [];

    public function __construct(
        ManagerRegistry $managerRegistry,
        UserRepository $userManager
    ) {
        parent::__construct(static::$defaultName);
        $this->managerRegistry = $managerRegistry;
        $this->userManager = $userManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Create characters from the old database and insert them here.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Defining specific properties for this class
        $this->input = $input;
        $this->io = new SymfonyStyle($input, $output);
        $this->em = $this->managerRegistry->getManager();
        $this->legacyConnection = $this->managerRegistry->getConnection('legacy');

        $sql = <<<'SQL'

    SELECT

    c.char_id,
        c.char_name,
        c.char_job,
        c.char_origin,
        c.char_people,
        c.char_content,
        c.char_date_creation,
        c.char_date_update,
        c.char_status,
        c.char_confirm_invite,
        c.game_id,
        c.user_id,

        j.job_id,
        j.job_name,
        j.job_desc,
        j.job_book,

        r.region_id,
        r.region_name,
        r.region_desc,
        r.region_kingdom,

        g.game_summary,
        g.game_id,
        g.game_name,
        g.game_summary,
        g.game_notes,
        g.game_mj,

        u.user_id,
        u.user_name,
        u.user_password,
        u.user_email,
        u.user_acl,
        u.user_status,
        u.user_confirm,

        u_gm.user_id as gm_user_id,
        u_gm.user_name as gm_user_name,
        u_gm.user_password as gm_user_password,
        u_gm.user_email as gm_user_email,
        u_gm.user_acl as gm_user_acl,
        u_gm.user_status as gm_user_status,
        u_gm.user_confirm as gm_user_confirm

    FROM est_characters c

    LEFT JOIN est_jobs j ON c.char_job = j.job_id

    LEFT JOIN est_regions r ON c.char_origin = r.region_id

    LEFT JOIN est_games g ON c.game_id = g.game_id
        LEFT JOIN est_users u_gm ON u_gm.user_id = g.game_mj 

    LEFT JOIN est_users u ON c.user_id = u.user_id

SQL;

        $characters = $this->legacyConnection->query($sql)->fetchAll();

        foreach ($characters as $arrayCharacter) {
            $character = new Character();
            $jsonCharacter = \json_decode($arrayCharacter['char_content'], true);

            $character
                ->setSex('Femme' === $arrayCharacter['sexe'] ? Character::FEMALE : Character::MALE)
                ->setDescription($arrayCharacter['details']['description'])
                ->setOrientation($arrayCharacter['orientation']['name'])
                ->setAge($arrayCharacter['age'])
                ->setName($arrayCharacter['char_name'])
            ;

            $this
                ->processUser($character, $arrayCharacter)
                ->processGame($character, $arrayCharacter)
                ->processWays($character, $jsonCharacter)
                ->processJob($character, $jsonCharacter)
                ->processDomains($character, $jsonCharacter)
                ->processBirthplace($character, $jsonCharacter)
                ->processTraits($character, $jsonCharacter)
                ->processSetbacks($character, $jsonCharacter)
                ->processAdvantages($character, $jsonCharacter)
                ->processMentalData($character, $jsonCharacter)
            ;

            dump($character);

            if (!$this->io->confirm('Continue?', false)) {
                // FIXME
                break;
            }
        }
    }

    private function getUser(?string $email, ?string $username): ?User
    {
        $user = null;

        $userByUsername = $username ? $this->userManager->findOneBy(['username' => $username]) : null;
        $userByEmail = $email ? $this->userManager->findOneBy(['email' => $email]) : null;

        if (
            $userByEmail && $userByUsername
            && $userByEmail->getId() !== $userByUsername->getId()
        ) {
            // The case where a username exists in the database with two different accounts with the old credentials.
            if (!$this->input->isInteractive()) {
                throw new \RuntimeException(
                    "Passed conflicting usernames:\n".
                    $userByEmail->getId().' / '.$userByEmail->getUsername().' / '.$userByEmail->getEmail()."\n".
                    $userByUsername->getId().' / '.$userByUsername->getUsername().' / '.$userByUsername->getEmail()
                );
            }

            $whichUser = $this->io->choice(
                'There are two different users with username & email:'.PHP_EOL.
                $userByEmail->getId().' / '.$userByEmail->getUsername().' / '.$userByEmail->getEmail().PHP_EOL.
                $userByUsername->getId().' / '.$userByUsername->getUsername().' / '.$userByUsername->getEmail(),
                [
                    $userByEmail->getId(),
                    $userByUsername->getId(),
                ]
            );

            return $whichUser;
        }

        if ($userByEmail || $userByUsername) {
            $user = $userByEmail ?: $userByUsername;
        } elseif ($username && $email) {
            $user = new User();

            $user
                ->setUsername($username)
                ->setEmail($email)
                ->setPlainPassword(\uniqid($username.$email, true))
            ;

            $this->em->persist($user);
        }

        return $user;
    }

    private function getRepository(string $repoName): ObjectRepository
    {
        if (\array_key_exists($repoName, $this->repositories)) {
            return $this->repositories[$repoName];
        }

        return $this->repositories[$repoName] = $this->em->getRepository($repoName);
    }

    private function processUser(Character $character, array $arrayCharacter): self
    {
        $user = null;
        $legacyUserEmail = $arrayCharacter['user_email'];

        if (\array_key_exists($legacyUserEmail, $this->users)) {
            $user = $this->users[$legacyUserEmail];
        }

        if (null === $user && $arrayCharacter['user_id']) {
            $user = $this->getUser($arrayCharacter['user_email'], $arrayCharacter['user_name']);
        }

        if ($user) {
            $character->setUser($user);
            $this->users[$user->getEmail()] = $user;
        }

        return $this;
    }

    /**
     * @param array[] $arrayCharacter
     *
     * @return $this
     */
    private function processGame(Character $character, array $arrayCharacter): self
    {
        $game = null;
        $legacyGameId = $arrayCharacter['game_id'];

        if (\array_key_exists($legacyGameId, $this->games)) {
            $game = $this->games[$legacyGameId];
        }

        if (null === $game && $arrayCharacter['game_id']) {
            $game = $this->getRepository(Game::class)->find($arrayCharacter['game_id']);

            if (!$game) {
                $game = new Game();

                $user = $this->getUser($arrayCharacter['gm_user_email'], $arrayCharacter['gm_user_name']);

                if (!$user) {
                    throw new \RuntimeException('Error when importing game "'.$arrayCharacter['game_name'].'", because no user was found.');
                }

                $game
                    ->setName($arrayCharacter['game_name'])
                    ->setGmNotes($arrayCharacter['game_notes'])
                    ->setSummary($arrayCharacter['game_summary'])
                    ->setGameMaster($user)
                ;

                $this->em->persist($game);
            }
        }

        if ($game) {
            $character->setGame($game);
        }

        return $this;
    }

    private function processWays(Character $character, array $jsonCharacter): self
    {
        $waysObject = new CharWays(
            $jsonCharacter['voies'][1]['val'],
            $jsonCharacter['voies'][2]['val'],
            $jsonCharacter['voies'][3]['val'],
            $jsonCharacter['voies'][4]['val'],
            $jsonCharacter['voies'][5]['val']
        );

        $character->setWay($waysObject);

        return $this;
    }

    private function processJob(Character $character, array $jsonCharacter): self
    {
        // TODO
        $this->io->block('To do '.__METHOD__, null, 'info');

        return $this;
    }

    private function processDomains(Character $character, array $jsonCharacter): self
    {
        // TODO
        $this->io->block('To do '.__METHOD__, null, 'info');

        return $this;
    }

    private function processBirthplace(Character $character, array $jsonCharacter): self
    {
        // TODO
        $this->io->block('To do '.__METHOD__, null, 'info');

        return $this;
    }

    private function processTraits(Character $character, array $jsonCharacter): self
    {
        // TODO
        $this->io->block('To do '.__METHOD__, null, 'info');

        return $this;
    }

    private function processSetbacks(Character $character, array $jsonCharacter): self
    {
        // TODO
        $this->io->block('To do '.__METHOD__, null, 'info');

        return $this;
    }

    private function processAdvantages(Character $character, array $jsonCharacter): self
    {
        // TODO
        $this->io->block('To do '.__METHOD__, null, 'info');

        return $this;
    }

    private function processMentalData(Character $character, array $jsonCharacter): self
    {
        // TODO
        $this->io->block('To do '.__METHOD__, null, 'info');

        return $this;
    }
}
