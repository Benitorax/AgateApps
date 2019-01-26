<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Controller\Admin\Api;

use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ApiValidationTrait
{
    /** @var ValidatorInterface */
    protected $validator;

    /**
     * @required
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    private function validate($dataToValidate)
    {
        if (!$this->validator) {
            throw new \RuntimeException('Validator is mandatory to validate incoming data.');
        }

        $violations = $this->validator->validate($dataToValidate);

        if ($violations->count() > 0) {
            $messages = [];

            foreach ($violations as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $messages;
        }

        return [];
    }
}
