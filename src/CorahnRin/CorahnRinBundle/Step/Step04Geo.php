<?php

namespace CorahnRin\CorahnRinBundle\Step;

class Step04Geo extends AbstractStepAction
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $geoEnvironments = $this->em->getRepository('CorahnRinBundle:GeoEnvironments')->findAll(true);

        if ($this->request->isMethod('POST')) {
            $geoEnvironmentId = (int) $this->request->request->get('gen-div-choice');
            if (isset($geoEnvironments[$geoEnvironmentId])) {
                $this->updateCharacterStep($geoEnvironmentId);

                return $this->nextStep();
            } else {
                $this->flashMessage('Veuillez indiquer un lieu de vie géographique correct.');
            }
        }

        return $this->renderCurrentStep([
            'geoEnvironments'      => $geoEnvironments,
            'geoEnvironment_value' => $this->getCharacterProperty(),
        ]);
    }
}
