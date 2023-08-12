<?php declare(strict_types=1);
namespace App\User;

use Framework\Database\Table\ArticleTable;
use Framework\Database\Table\CategorieTable;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface;

final class ArticleValidator extends Validator
{
    private const MIME_TYPES = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
    ];

    /**
     * add rules about article to the validator
     *
     * @param int|null $id=null
     */
    public function __construct(
        readonly ServerRequestInterface $request,
        readonly ArticleTable $articleTable,
        readonly CategorieTable $categorieTable,
        readonly ?int $id = null
    ) {
        $dataString = $request->getParsedBody();
        $dataFile = $request->getUploadedFiles();
        is_array($dataString) ? parent::__construct($dataString + $dataFile) : parent::__construct($dataFile);
        $this
            ->rule('required', ['name', 'slug', 'price'])
            ->rule('lengthBetween', ['name', 'slug'], 2, 100)
            ->rule(
                fn($field, $value) => !$articleTable->exists($field, $value, $id),
                ['name', 'slug'],
                'Cette valeur est déjà utilisé'
            )
            ->rule('regex', 'slug', '/^[a-z0-9\-]+$/')
            ->rule('numeric', 'price')
            ->rule('min', 'price', 0.01)
            ->rule(function ($field, $value) {
                $isFloat = strrchr($value, ".");
                if ($isFloat !== false) {
                    return strlen(substr($isFloat, 1)) <= 2;
                }
                return true;
            }, 'price', 'Trop de chiffres après la \',\'')
            ->rule(
                fn($field, $value) => $this->extension($value, ['jpg', 'jpeg', 'png', 'gif']),
                'images',
                'Une des images n\'est pas au format valide (jpg, jpeg, png, gif)'
            )
            ->rule(
                fn($field, $value) => (is_countable($value) ? count($value) : 0) <= 10,
                'images',
                'Maximum 10 images'
            )
            ->rule(function ($field, $value) use ($categorieTable) {
                if ($value !== 'null') {
                    return $categorieTable->exists('id', $value);
                }
                return true;
            }, 'categorie_id', 'La catégorie n\'existe pas');
        if ($request->getAttribute('id') === null) {
            $this->rule(
                fn($field, $value) => $this->uploaded($value),
                'images',
                'Au moins une image est requise'
            );
        }
        $this->setPrependLabels(false);
    }

    /**
     * verify if all files are correctly upload
     * @param (\Psr\Http\Message\UploadedFileInterface|null)[] $files
     *
     */
    private function uploaded(array $files = []): bool
    {
        foreach ($files as $file) {
            if (!$file instanceof \Psr\Http\Message\UploadedFileInterface || $file->getError() !== UPLOAD_ERR_OK) {
                return false;
            }
        }
        return true;
    }

    /**
     * verify if all files have a right extension
     * @param (\Psr\Http\Message\UploadedFileInterface|null)[] $files
     * @param string[] $extensions
     *
     */
    private function extension(array $files = [], array $extensions = []): bool
    {
        foreach ($files as $file) {
            if ($file instanceof \Psr\Http\Message\UploadedFileInterface &&
                $file->getError() === UPLOAD_ERR_OK &&
                $file->getClientFilename() !== ''
            ) {
                $type = $file->getClientMediaType();
                $clientFilename = $file->getClientFilename();
                if (!is_string($clientFilename)) {
                    return false;
                }
                $extension = mb_strtolower(pathinfo($clientFilename, PATHINFO_EXTENSION));
                $expectedType = self::MIME_TYPES[$extension] ?? null;
                if (!in_array($extension, $extensions) || $expectedType !== $type) {
                    return false;
                }
            }
        }
        return true;
    }
}
