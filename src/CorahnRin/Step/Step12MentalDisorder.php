<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Step;

use CorahnRin\Repository\MentalDisorderRepository;
use Symfony\Component\HttpFoundation\Response;

class Step12MentalDisorder extends AbstractStepAction
{
    private $disordersRepository;

    public function __construct(MentalDisorderRepository $disordersRepository)
    {
        $this->disordersRepository = $disordersRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $disorderValue = $this->getCharacterProperty();

        /** @var int[] $ways */
        $ways = $this->getCharacterProperty('08_ways');

        // They MUST be indexed by ids by the repository.
        $disorders = $this->disordersRepository->findWithWays();

        $definedDisorders = [];

        // Fetch "minor" and "major" ways to check for compatible disorders.
        $majorWays = $minorWays = [];
        foreach ($ways as $id => $value) {
            if ($value < 3) {
                $minorWays[$id] = 1;
            } elseif ($value > 3) {
                $majorWays[$id] = 1;
            }
        }

        // Test all disorders with current ways major and minor values.
        foreach ($disorders as $disorder) {
            foreach ($disorder->getWays() as $disorderWay) {
                if (
                    ($disorderWay->isMajor() && \array_key_exists($disorderWay->getWay(), $majorWays))
                    || (!$disorderWay->isMajor() && \array_key_exists($disorderWay->getWay(), $minorWays))
                ) {
                    $definedDisorders[$disorder->getId()] = $disorder;
                }
            }
        }

        // Validate form.
        if ($this->request->isMethod('POST')) {
            $disorderValue = (int) $this->request->request->get('gen-div-choice');

            // Success!
            if (\array_key_exists($disorderValue, $disorders)) {
                $this->updateCharacterStep((int) $disorderValue);

                return $this->nextStep();
            }

            if (0 === $disorderValue) {
                $this->flashMessage('Veuillez choisir un désordre mental.');
            } else {
                $this->flashMessage('Le désordre mental choisi n\'existe pas.');
            }
        }

        return $this->renderCurrentStep([
            'disorder_value' => $disorderValue,
            'disorders' => $definedDisorders,
        ], 'corahn_rin/Steps/12_mental_disorder.html.twig');
    }
}
