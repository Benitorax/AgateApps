<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Exception;

class CharacterException extends \RuntimeException
{
    public function __construct($message = '', $code = 0, $previous = null)
    {
        $message = 'Character error: '.$message;
        parent::__construct($message, $code, $previous);
    }
}
