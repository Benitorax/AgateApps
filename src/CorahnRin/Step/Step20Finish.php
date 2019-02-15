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

namespace CorahnRin\Step;

use CorahnRin\Exception\CharacterException;
use CorahnRin\GeneratorTools\SessionToCharacter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use User\Entity\User;

class Step20Finish extends AbstractStepAction
{
    private $sessionToCharacter;
    private $tokenStorage;

    public function __construct(
        SessionToCharacter $sessionToCharacter,
        TokenStorageInterface $tokenStorage
    ) {
        $this->sessionToCharacter = $sessionToCharacter;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $this->updateCharacterStep(true);

        $character = null;

        try {
            $character = $this->sessionToCharacter->createCharacterFromGeneratorValues($this->getCurrentCharacter());
        } catch (CharacterException $e) {
            $character = null;
        }

        if (!$character) {
            $this->flashMessage('errors.character_not_complete');

            return $this->goToStep(1);
        }

        $token = $this->tokenStorage->getToken();

        if ($token) {
            $user = $token->getUser();
            if ($user instanceof User) {
                $character->setUser($user);
            }
        }

        if ($this->request->isMethod('POST')) {
            $this->em->persist($character);
            $this->em->flush();

            return new RedirectResponse($this->router->generate('corahnrin_characters_view', ['id' => $character->getId()]));
        }

        return $this->renderCurrentStep([
            'character' => $character,
        ], 'corahn_rin/Steps/20_finish.html.twig');
    }
}
