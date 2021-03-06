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

namespace User\Security\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class EmailNotConfirmedException extends AccountStatusException
{
    private const EXCEPTION_MESSAGE = 'security.email_not_confirmed';

    public function __construct()
    {
        parent::__construct(static::EXCEPTION_MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return static::EXCEPTION_MESSAGE;
    }
}
