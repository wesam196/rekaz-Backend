<?php
namespace App\Storage;

class LocalStorageBackend {
    protected $path;

    public function __construct(array $config)
    {
        $this->path = $config['path'];
        if (!file_exists($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

public function save($id, $data, $size)
{
    $filePath = $this->path . '/' . $id;
    $payload = [
        'data' => ($data),
        'size' => $size,
        'created_at' => now()->toDateTimeString(),
    ];
    file_put_contents($filePath, json_encode($payload));
}

    public function retrieve($id)
{
    $filePath = $this->path . '/' . $id;
    if (!file_exists($filePath)) return null;

    $payload = json_decode(file_get_contents($filePath), true);
    return $payload; 
}


    public function delete($id)
    {
        $filePath = $this->path . '/' . $id;
        if (file_exists($filePath)) unlink($filePath);
    }
}
