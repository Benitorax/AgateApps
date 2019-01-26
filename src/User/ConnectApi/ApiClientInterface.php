<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace User\ConnectApi;

use GuzzleHttp\Client;

interface ApiClientInterface
{
    public function getEndpoint(): string;

    public function getClient(array $options = []): Client;
}
