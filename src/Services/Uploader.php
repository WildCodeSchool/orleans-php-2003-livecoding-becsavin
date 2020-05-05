<?php

namespace App\Services;

class Uploader
{
    const DEFAULT_MIME_TYPES = ['image/jpeg', 'image/png'];
    const DEFAULT_FILESIZE = 1000000;
    const DEFAULT_UPLOAD_DIR = '../public/uploads/';

    private $errors;
    private $file;
    private $mimeTypes;
    private $filesize;
    private $uploadDir;

    public function __construct(array $file)
    {
        $this->file = $file;
        $this->mimeTypes = self::DEFAULT_MIME_TYPES;
        $this->filesize = self::DEFAULT_FILESIZE;
        $this->uploadDir = self::DEFAULT_UPLOAD_DIR;
    }

    /**
     * @return string
     */
    public function getUploadDir(): string
    {
        return $this->uploadDir;
    }

    /**
     * @param string $uploadDir
     * @return Uploader
     */
    public function setUploadDir(string $uploadDir): Uploader
    {
        $this->uploadDir = $uploadDir;

        return $this;
    }

    public function validate()
    {
        // type mime
        $mime = mime_content_type($this->getFile()['tmp_name']);
        if (!in_array($mime, $this->getMimeTypes())) {
            $this->errors[] = 'Les type mimes autorisés sont uniquement ' .
                implode(',', $this->getMimeTypes());
        }

        // taille fichier
        if ($this->getFile()['size'] > $this->getFilesize()) {
            $this->errors[] = 'Le fichier doit faire moins de ' . ($this->getFilesize() / 1000000) . ' Mo';
        }
    }

    /**
     * @return array
     */
    public function getFile(): array
    {
        return $this->file;
    }

    /**
     * @param array $file
     * @return Uploader
     */
    public function setFile(array $file): Uploader
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getMimeTypes(): array
    {
        return $this->mimeTypes;
    }

    /**
     * @param string[] $mimeTypes
     * @return Uploader
     */
    public function setMimeTypes(array $mimeTypes): Uploader
    {
        $this->mimeTypes = $mimeTypes;

        return $this;
    }

    /**
     * @return int
     */
    public function getFilesize(): int
    {
        return $this->filesize;
    }

    /**
     * @param int $filesize
     * @return Uploader
     */
    public function setFilesize(int $filesize): Uploader
    {
        $this->filesize = $filesize;

        return $this;
    }

    public function upload(): string
    {
        $extension = pathinfo($this->getFile()['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $extension;
        $destination = $this->uploadDir . $fileName;
        move_uploaded_file($this->getFile()['tmp_name'], $destination);

        return $fileName;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors ?? [];
    }
}
