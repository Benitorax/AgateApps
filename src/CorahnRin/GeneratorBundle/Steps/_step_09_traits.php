<?php
/**
 * class @StepLoader
 * Métier
 */

$ways = $this->getStepValue(8);

$traits = $this->em->getRepository('CorahnRinCharactersBundle:Traits')->findAllDependingOnWays($ways);

$quality = isset($this->character[$this->stepFullName()]['quality']) ? (int) $this->character[$this->stepFullName()]['quality'] : null;
$flaw = isset($this->character[$this->stepFullName()]['flaw']) ? (int) $this->character[$this->stepFullName()]['flaw'] : null;

$datas = array(
    'quality' => $quality,
    'flaw' => $flaw,
    'traits_list' => $traits,
);

if ($this->request->isMethod('POST')) {
    $this->resetSteps();
    $quality = (int) $this->request->request->get('quality');
    $flaw = (int) $this->request->request->get('flaw');

    $quality_exists = array_key_exists($quality, $traits['qualities']);
	$flaw_exists = array_key_exists($flaw, $traits['flaws']);

    if ($quality_exists && $flaw_exists) {
        $this->characterSet(array(
            'quality' => $quality,
            'flaw' => $flaw,
        ));
        return $this->nextStep();
    } else {
        $this->flashMessage('Les traits de caractère choisis sont incorrects.', 'error.steps');
    }

}
return $datas;