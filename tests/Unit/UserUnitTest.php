<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Storage\LocalStorageBackend;
use App\Storage\FTPStorageBackend;
use App\Storage\S3StorageBackend;
use App\Storage\DBStorageBackend;

class UserUnitTest extends TestCase
{
    protected string $id;
    protected string $data;

    protected function setUp(): void
{
    parent::setUp();

    $this->id = \Illuminate\Support\Str::uuid()->toString();
    $this->data = "SGVsbG8gU2ltcGxlIFN0b3JhZ2UgV29ybGQh";

    if (!\Illuminate\Support\Facades\Schema::hasTable('blobs_data')) {
        \Illuminate\Support\Facades\Schema::create('blobs_data', function ($table) {
            $table->string('id')->primary();
            $table->text('data');
            $table->integer('size');
            $table->timestamp('created_at')->nullable();
        });
    }
}

    public function test_db_storage_backend()
    {
        $storage = new DBStorageBackend(['table' => 'blobs_data']);

        $payload = $storage->save($this->id, $this->data, strlen($this->data));
        $this->assertArrayHasKey('created_at', $payload);

        $retrieved = $storage->retrieve($this->id);
        $this->assertEquals($this->data, $retrieved['data']);

        $storage->delete($this->id);
        $this->assertNull($storage->retrieve($this->id));
    }

    public function test_local_storage_backend()
    {
        $storage = new LocalStorageBackend(['path' => storage_path('app/test')]);

        $payload = $storage->save($this->id, $this->data, strlen($this->data));
        $this->assertArrayHasKey('created_at', $payload);

        $retrieved = $storage->retrieve($this->id);
        $this->assertEquals($this->data, $retrieved['data']);

        $storage->delete($this->id);
        $this->assertNull($storage->retrieve($this->id));
    }

    public function test_s3_storage_backend()
    {
        $storage = new S3StorageBackend([
            'bucket' => env('AWS_BUCKET', 'my-bucket'),
            'endpoint' => env('AWS_ENDPOINT', 'http://127.0.0.1:9000'),
            'base_path' => 'test',
        ]);

        $payload = $storage->save($this->id, $this->data, strlen($this->data));
        $this->assertArrayHasKey('created_at', $payload);

        $retrieved = $storage->retrieve($this->id);
        $this->assertEquals($this->data, $retrieved['data']);

        $storage->delete($this->id);
        $this->assertNull($storage->retrieve($this->id));
    }

    
    public function test_ftp_storage_backend()
    {
        $storage = new FTPStorageBackend([
            'host' => env('FTP_HOST'),
            'port' => env('FTP_PORT', 21),
            'username' => env('FTP_USER'),
            'password' => env('FTP_PASS'),
            'root' => env('FTP_ROOT'),
        ]);

        $payload = $storage->save($this->id, $this->data, strlen($this->data));
            $this->assertIsArray($payload);
            $this->assertArrayHasKey('created_at', $payload);

        $retrieved = $storage->retrieve($this->id);
        $this->assertEquals($this->data, $retrieved['data']);

        $storage->delete($this->id);
        $this->assertNull($storage->retrieve($this->id));
    }
        
}
