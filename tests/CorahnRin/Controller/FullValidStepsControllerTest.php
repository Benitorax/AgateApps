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

namespace Tests\CorahnRin\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\WebTestCase as PiersTestCase;

/**
 * The goal here is to create one single character to make sure the whole step process is working correctly.
 * Specific steps tests and validations are made in the "Step" namespace, so check the directory next to this one.
 */
class FullValidStepsControllerTest extends WebTestCase
{
    use PiersTestCase;

    /**
     * @see StepController::indexAction
     */
    public function testIndex(): void
    {
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        $client->request('GET', '/fr/character/generate');

        static::assertSame(302, $client->getResponse()->getStatusCode());

        static::assertTrue(
            $client->getResponse()->isRedirect('/fr/character/generate/01_people'),
            'Could not check that generator index redirects to first step'
        );
    }

    /**
     * @see          StepController::stepAction
     * @dataProvider provideValidSteps
     *
     * @param string $stepName
     * @param string $routeUri
     * @param string $nextStep
     */
    public function testAllSteps($stepName, $routeUri, $nextStep, array $formValues, $expectedSessionValue, array $previousSteps): void
    {
        if ('20_finish' === $stepName) {
            // Finished generation. Session to Character will be tested somewhere else
            static::assertTrue(true);

            return;
        }

        if (!$formValues && !$expectedSessionValue) {
            static::markTestIncomplete('Missing form values for step '.$stepName);
        }
        $client = $this->getClient('corahnrin.esteren.docker', [], ['ROLE_ADMIN']);

        // We need a simple session to be sure it's updated when submitting form.
        $session = $client->getContainer()->get('session');

        // Set previous steps value so we can "detach" each request to be standalone.
        $character = $session->get('character.corahn_rin', []);
        foreach ($previousSteps as $step) {
            $character[$step['step']] = $step['session_value'];
        }
        $session->set('character.corahn_rin', $character);
        $session->save();

        // Make the request.
        $crawler = $client->request('GET', '/fr/character/generate/'.$routeUri);

        // If it's not 200, it certainly session is invalid.
        $statusCode = $client->getResponse()->getStatusCode();
        $errorBlock = $crawler->filter('title');

        $msg = 'Could not execute step request...';
        $msg .= $errorBlock->count() ? ("\n".$errorBlock->text()) : '';
        static::assertSame(200, $statusCode, $msg);

        // Prepare form values.
        $form = $crawler->filter('#generator_form')->form();

        // Sometimes, form can't be submitted properly,
        // like with "orientation" step.
        if ($formValues) {
            try {
                $form->setValues($formValues);
            } catch (\Exception $e) {
                $this->fail($e->getMessage()."\nWith values:\n".\str_replace("\n", '', \var_export($formValues, true)));
            }
        }

        // Here, if the redirection is made for the next step, it means everything's valid.
        $crawler = $client->submit($form);

        // Parse better message to show in phpunit's output if there is an error in the submitted form.
        $msg = 'Request does not redirect to next step "'.$nextStep.'".';
        if ($crawler->filter('#flash-messages')->count()) {
            $msg .= \trim($crawler->filter('#flash-messages')->text());
        }

        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/'.$nextStep), $msg);

        // We also make sure that the session has been correctly updated.
        $character = $session->get('character.corahn_rin');
        static::assertSame($expectedSessionValue, $character[$stepName], 'Character values are not equal to session ones in step "'.$stepName.'"...');
    }

    public static function provideValidSteps()
    {
        /** @var array[] $steps */
        $steps = require __DIR__.'/../Resources/valid_consecutive_steps.php';

        $previous = [];

        // Force data format to fit testAllSteps' signature.
        // Also, set all previous steps to update session and not automatically follow redirects (allow steps reordering, in case of).
        foreach ($steps as $stepName => $step) {
            if (!\array_key_exists('route_uri', $step)) {
                $step['route_uri'] = $stepName;
            }
            $step['step'] = $stepName;

            $data = [
                $stepName,
                $step['route_uri'],
                $step['next_step'],
                $step['form_values'] ?: [],
                $step['session_value'],
                $previous,
            ];

            $previous[] = $step;

            yield $stepName => $data;
        }
    }
}
