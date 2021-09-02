<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileHandler implements FileHandlerInterface
{
    /** @var string */
    private $targetDirectory;

    /**
     * @param string $targetDirectory File upload directory.
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file Download file.
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = md5(uniqid('', true)) . '.' . $file->guessExtension();
        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    /**
     * @param string $filename Filename.
     */
    public function remove(string $filename): void
    {
        $filePath = $this->getTargetDirectory() . '/' . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
