<?php declare(strict_types=1);
namespace Framework;

use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;

abstract class Upload
{
    protected string $path;

    /**
     * @var mixed[]
     */
    protected array $formats = [];

    public function __construct(string $path = null)
    {
        if ($path) {
            $this->path = $path;
        }
    }

    /**
     * upload a file with its formats
     *
     * @return string filename
     */
    public function upload(UploadedFileInterface $file, string $filename): ?string
    {
        if ($file->getError() === UPLOAD_ERR_OK) {
            $clientFilename = $file->getClientFilename();
            if (!is_string($clientFilename)) {
                return null;
            }
            $extension = '.' . mb_strtolower(pathinfo($clientFilename, PATHINFO_EXTENSION));
            $targetPath = $this->addCopySuffix($this->path . DIRECTORY_SEPARATOR . $filename . $extension);
            $dirname = pathinfo($targetPath, PATHINFO_DIRNAME);
            if ($dirname) {
                if (!file_exists($dirname)) {
                    mkdir($dirname, 777, true);
                }
                $file->moveTo($targetPath);
                $this->generateFormat($targetPath);
                return pathinfo($targetPath)['basename'];
            }
        }
        return null;
    }

    /**
     * add a copy suffix if the file to upload exist
     *
     */
    private function addCopySuffix(string $targetPath): string
    {
        if (file_exists($targetPath)) {
            $pathInfo = $this->isTherePathInfo(pathinfo($targetPath));
            if ($pathInfo) {
                $targetPath = $pathInfo['dirname'] .
                    DIRECTORY_SEPARATOR .
                    $pathInfo['filename'] .
                    '_copy.' .
                    $pathInfo['extension'];
                return $this->addCopySuffix($targetPath);
            }
        }
        return $targetPath;
    }

    /**
     * add a suffix to a path
     *
     */
    private function getPathSuffix(string $path, string $suffix) : ?string
    {
        $pathInfo = $this->isTherePathInfo(pathinfo($path));
        if ($pathInfo) {
            return  $pathInfo['dirname'] . DIRECTORY_SEPARATOR .
            $pathInfo['filename'] . '_' . $suffix . '.' . $pathInfo['extension'];
        }
        return null;
    }

    /**
     * generate the different format of the file
     *
     */
    private function generateFormat(string $targetPath): void
    {
        foreach ($this->formats as $format => $size) {
            $manager = new ImageManager(['driver', 'gd']);
            $destination = $this->getPathSuffix($targetPath, $format);
            if ($destination) {
                [$width, $height] = $size;
                $manager->make($targetPath)->fit($width, $height)->save($destination);
            }
        }
    }

    /**
     * delete a file
     * @param string|null $filename
     *
     */
    public function delete(?string $filename = null): void
    {
        if ($filename) {
            $file = $this->path . DIRECTORY_SEPARATOR . $filename;
            if (file_exists($file)) {
                unlink($file);
            }
            foreach ($this->formats as $format => $_) {
                $fileWithFormat = $this->getPathSuffix($file, $format);
                if (is_string($fileWithFormat)) {
                    if (file_exists($fileWithFormat)) {
                        unlink($fileWithFormat);
                    }
                }
            }
        }
    }

    /**
     * check if there is an extension and dirname in pathinfo()
     * @param string[] $pathInfo
     *
     * @return string[]|null
     */
    private function isTherePathInfo(array $pathInfo): ?array
    {
        $extension = $pathInfo['extension'] ?? null;
        $dirname = $pathInfo['dirname'] ?? null;
        if ($extension && $dirname) {
            return $pathInfo;
        }
        return null;
    }
}
