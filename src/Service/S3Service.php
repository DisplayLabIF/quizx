<?php

namespace App\Service;

use App\Entity\Curso\Aula;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class S3Service
{
    private $s3Client;

    public function __construct()
    {
        $this->s3Client = new S3Client($this->getS3Config());
    }

    public function getS3Config()
    {
        $s3Config = [
            'region' => 'sa-east-1',
            'version' => 'latest',
            'credentials' => [
                'key' => $_ENV['S3_KEY'],
                'secret' => $_ENV['S3_SECRET']
            ],

        ];
        return $s3Config;
    }

    public function getS3Client(): S3Client
    {
        return $this->s3Client;
    }
}
