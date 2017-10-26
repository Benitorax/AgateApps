<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\CorahnRinBundle\Step;

use Symfony\Component\HttpFoundation\Response;
use CorahnRin\CorahnRinBundle\Entity\Characters;

class Step19Description extends AbstractStepAction
{
    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $details = array_merge([
            'name'        => '',
            'player_name' => '',
            'sex'         => Characters::FEMALE,
            'description' => '',
            'story'       => '',
            'facts'       => '',
        ], $this->getCharacterProperty() ?: []);

        if ($this->request->isMethod('POST')) {
            $newDetails = $this->request->get('details', []);

            $errors = false;

            if (!array_key_exists('sex', $newDetails)) {
                $errors = true;
                $this->flashMessage('description.errors.sex');
            }

            $baseKeys   = array_keys($details);
            $postedKeys = array_keys($newDetails);
            sort($baseKeys);
            sort($postedKeys);

            if ($baseKeys !== $postedKeys) {
                $errors = true;
                $this->flashMessage('errors.incorrect_values');
            }

            if (false === $errors) {
                if (!in_array($newDetails['sex'], [
                    Characters::MALE,
                    Characters::FEMALE,
                ], true)) {
                    $errors = true;
                    $this->flashMessage('errors.incorrect_values');
                }
                if (!$newDetails['name']) {
                    $errors = true;
                    $this->flashMessage('description.errors.name');
                }
                if (!$newDetails['player_name']) {
                    $errors = true;
                    $this->flashMessage('description.errors.player_name');
                }
                if (mb_strlen($newDetails['name']) > 100) {
                    $errors = true;
                    $this->flashMessage('description.errors.name_too_long');
                    $newDetails['name'] = mb_substr($newDetails['name'], 0, 100);
                }
                if (mb_strlen($newDetails['description']) > 255) {
                    $errors = true;
                    $this->flashMessage('description.errors.description');
                    $newDetails['description'] = mb_substr($newDetails['description'], 0, 255);
                }
                if (mb_strlen($newDetails['story']) > 65535) {
                    $errors = true;
                    $this->flashMessage('description.errors.story');
                    $newDetails['story'] = mb_substr($newDetails['story'], 0, 65535);
                }
                if (mb_strlen($newDetails['facts']) > 65535) {
                    $errors = true;
                    $this->flashMessage('description.errors.story');
                    $newDetails['facts'] = mb_substr($newDetails['facts'], 0, 65535);
                }
            }

            $details = $newDetails;

            if (false === $errors) {
                $this->updateCharacterStep($details);

                return $this->nextStep();
            }
        }

        return $this->renderCurrentStep([
            'details' => $details,
        ]);
    }
}
