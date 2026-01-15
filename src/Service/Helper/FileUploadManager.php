<?php

namespace App\Service\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileUploadManager
{
    public function __construct(
        private string $uploadsBaseDir,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file, string $subDir, ?string $baseName = null): string
    {
        $safeBaseName = $baseName ? $this->slugger->slug($baseName)->lower()->toString() : 'file';
        $ext = $file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'bin';

        $filename = sprintf('%s_%s.%s', $safeBaseName, bin2hex(random_bytes(6)), $ext);

        $targetDir = rtrim($this->uploadsBaseDir, '/').'/'.trim($subDir, '/');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $file->move($targetDir, $filename);

        // Return relative path stored in DB
        return trim($subDir, '/').'/'.$filename;
    }

    public function delete(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        $fullPath = rtrim($this->uploadsBaseDir, '/').'/'.ltrim($relativePath, '/');
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
