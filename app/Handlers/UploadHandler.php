<?php

namespace App\Handlers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class UploadHandler
{
    public string|false $path;

    public function __construct(protected UploadedFile $file, protected string $saveDir, protected string $rule,
                                protected bool $dateDir = true, protected array $messages = [])
    {
        $this->upload();
    }

    private function upload(): void
    {
        try {
            validator(['file' => $this->file], ['file' => $this->rule], $this->messages)->validate();
        } catch (ValidationException $e) {
            throw new \RuntimeException($e->getMessage());
        }
        $savePath = $this->saveDir;
        if ($this->dateDir) {
            $savePath .= DIRECTORY_SEPARATOR . date('Ymd');
        }
        $this->path = '/uploads/' . $this->file->store($savePath, ['disk' => 'uploads']);
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
