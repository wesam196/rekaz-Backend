<?php
namespace App\Services;

use App\Storage\LocalStorageBackend;
use App\Storage\DBStorageBackend;
use App\Storage\S3StorageBackend;
use App\Storage\FTPStorageBackend;

class BlobStorageService
{
    protected $backend;

    public function __construct()
    {
        $backendName = config('storage.default');
        $backendConfig = config("storage.backends.$backendName");

        switch ($backendName) {
            case 'local':
                $this->backend = new LocalStorageBackend($backendConfig);
                break;
            case 'db':
                $this->backend = new DBStorageBackend($backendConfig);
                break;
            case 's3':
                $this->backend = new S3StorageBackend($backendConfig);
                break;
            case 'ftp':
                $this->backend = new FTPStorageBackend($backendConfig);
                break;
            default:
                throw new \Exception("Invalid storage backend");
        }
    }

    public function save(string $id, string $data, int $size)
    {
        return $this->backend->save($id, $data,$size);
    }

    public function retrieve(string $id)
    {
        return $this->backend->retrieve($id);
    }

    public function delete(string $id)
    {
        return $this->backend->delete($id);
    }
}
