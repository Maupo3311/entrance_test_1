<?php

namespace App\EventListener;

use App\Entity\Book;
use App\Service\FileHandlerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BookImageUploadListener
{
    /** @var FileHandlerInterface */
    private $fileHandler;

    /**
     * @param FileHandlerInterface $fileHandler Service for working with files.
     */
    public function __construct(FileHandlerInterface $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * @param LifecycleEventArgs $args A LifecycleEventArgs instance.
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->uploadFile($args->getEntity())->removeOldFile($args->getEntity());
    }

    /**
     * @param PreUpdateEventArgs $args A PreUpdateEventArgs instance.
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->uploadFile($args->getEntity())->removeOldFile($args->getEntity());
    }

    /**
     * @param mixed $entity Doctrine entity.
     * @return $this
     */
    private function uploadFile($entity): BookImageUploadListener
    {
        if (!$entity instanceof Book) {
            return $this;
        }

        $file = $entity->getImage();
        if ($file instanceof UploadedFile) {
            $fileName = $this->fileHandler->upload($file);
            $entity->setImage($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setImage($file->getFilename());
        }

        return $this;
    }

    /**
     * @param mixed $entity Doctrine entity.
     * @return void
     */
    private function removeOldFile($entity): void
    {
        if (!$entity instanceof Book) {
            return;
        }

        if ($entity->getTempImage() !== null) {
            $this->fileHandler->remove($entity->getTempImage());
        }

    }

    /**
     * @param LifecycleEventArgs $args A LifecycleEventArgs instance.
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Book) {
            return;
        }

        try {
            if ($fileName = $entity->getImage()) {
                $entity->setImage(new File($this->fileHandler->getTargetDirectory() . '/' . $fileName));
            }
        } catch (FileNotFoundException $e) {
            $entity->setImage(null);
        }
    }
}
