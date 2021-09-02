<?php

namespace App\EventListener;

use App\Entity\Book;
use App\Service\FileUploaderInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BookImageUploadListener
{
    /** @var FileUploaderInterface */
    private $uploader;

    /**
     * @param FileUploaderInterface $uploader A FileUploader instance.
     */
    public function __construct(FileUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args A LifecycleEventArgs instance.
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->uploadFile($args->getEntity());
    }

    /**
     * @param PreUpdateEventArgs $args A PreUpdateEventArgs instance.
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->uploadFile($args->getEntity());
    }

    /**
     * @param mixed $entity
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof Book) {
            return;
        }

        $file = $entity->getImage();
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
        } elseif ($file instanceof File) {
            // prevents the full file path being saved on updates
            // as the path is set on the postLoad listener
            $entity->setImage($file->getFilename());
        }

        if ($entity->getTempImage() !== null) {
            $this->uploader->remove($entity->getTempImage());
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
                $entity->setImage(new File($this->uploader->getTargetDirectory() . '/' . $fileName));
            }
        } catch (FileNotFoundException $e) {
            $entity->setImage(null);
        }
    }
}
