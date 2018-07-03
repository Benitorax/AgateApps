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

use CorahnRin\Repository\JobsRepository;
use Symfony\Component\HttpFoundation\Response;

class Step02Job extends AbstractStepAction
{
    private $jobsRepository;

    public function __construct(JobsRepository $jobsRepository)
    {
        $this->jobsRepository = $jobsRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $jobs = $this->jobsRepository->findAllPerBook();

        if ($this->request->isMethod('POST')) {
            $jobValue  = (int) $this->request->request->get('job_value');
            $jobExists = false;

            foreach ($jobs as $id => $jobs_list) {
                if (isset($jobs_list[$jobValue])) {
                    $jobExists = true;
                }
            }

            if ($jobExists) {
                $this->updateCharacterStep($jobValue);

                return $this->nextStep();
            }
            $this->flashMessage('Veuillez entrer un métier correct.');
        }

        return $this->renderCurrentStep([
            'jobs_list' => $jobs,
            'job_value' => $this->getCharacterProperty(),
        ]);
    }
}
