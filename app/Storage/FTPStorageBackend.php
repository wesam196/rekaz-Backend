<?php

namespace App\Storage;

use Illuminate\Support\Facades\Storage;

class FTPStorageBackend
{
    protected $disk;
    protected $basePath;

    public function __construct(array $config = [])
    {
        $this->disk = Storage::disk('ftp');
        $this->basePath = $config['base_path'] ?? '';
    }

    /**
     * 
     *
     * @param string $id
     * @param string $data
     * @param int $size
     * @return bool|string
     */
    public function save($id, $data, $size)
    {
        $filePath = $this->getFilePath($id);

        if ($this->disk->exists($filePath)) {
            return false; 
        }

        $payload = [
            'data' => $data,
            'size' => $size,
            'created_at' => now()->toDateTimeString(),
        ];

        
        return $this->disk->put($filePath, json_encode($payload));
    }

    /**
     * 
     *
     * @param string $id
     * @return array|null
     */
    public function retrieve($id)
    {
        $filePath = $this->getFilePath($id);

        if (!$this->disk->exists($filePath)) {
            return null;
        }

        $payload = json_decode($this->disk->get($filePath), true);

        return $payload; 
    }

    /**
     * 
     *
     * @param string $id
     * @return bool
     */
    public function delete($id)
    {
        $filePath = $this->getFilePath($id);

        if ($this->disk->exists($filePath)) {
            return $this->disk->delete($filePath);
        }

        return false;
    }

    /**
     * 
     *
     * @param string $id
     * @return string
     */
    protected function getFilePath($id)
    {
        return trim($this->basePath, '/') . '/' . $id . '.json';
    }
}
