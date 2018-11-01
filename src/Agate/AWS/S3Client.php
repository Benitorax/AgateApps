<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Agate\AWS;

use Aws\S3\S3Client as AwsS3Client;
use Symfony\Component\HttpFoundation\File\File;

class S3Client
{
    private $region;
    private $bucket;
    private $key;
    private $secret;

    /** @var AwsS3Client */
    private $client;

    public function __construct(
        string $awsRegion,
        string $awsBucket,
        string $awsKey,
        string $awsSecret
    ) {
        $this->region = $awsRegion;
        $this->bucket = $awsBucket;
        $this->key = $awsKey;
        $this->secret = $awsSecret;
    }

    public function postFile(File $file)
    {
        $this->getClient()->upload($this->bucket, $this->key, $file);
    }

    private function getClient(): AwsS3Client
    {
        if ($this->client) {
            return $this->client;
        }

        return $this->client = new AwsS3Client([
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret,
            ],
            'region' => $this->region,
            'version' => 'latest',
        ]);
    }
}
