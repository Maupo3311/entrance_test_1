<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    /**
     * @param UploadedFile $file Download file.
     * @return string
     */
    public function upload(UploadedFile $file): string;

    /**
     * @param string $filename Filename.
     */
    public function remove(string $filename): void;

    /**
     * @return string
     */
    public function getTargetDirectory(): string;
}
