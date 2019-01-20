<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Step;

use CorahnRin\Exception\CharacterException;
use CorahnRin\GeneratorTools\SessionToCharacter;
use Symfony\Component\HttpFoundation\Response;

class Step20Finish extends AbstractStepAction
{
    private $sessionToCharacter;

    public function __construct(SessionToCharacter $sessionToCharacter)
    {
        $this->sessionToCharacter = $sessionToCharacter;
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
            $this->flashMessage('errors.character_not_complete');
            $this->goToStep(1);
        }

        if (\function_exists('dump')) {
            // Only in dev for now
            dump($character);
        }

        return $this->renderCurrentStep([
            'character' => $character,
        ], 'corahn_rin/Steps/20_finish.html.twig');
    }
}
