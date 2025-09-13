<?php
namespace App\Storage;

use Illuminate\Support\Facades\DB;

class DBStorageBackend {
    protected $dataTable;
    protected $metaTable;

    public function __construct(array $config)
    {
        $this->dataTable = $config['table'];
        $this->metaTable = $config['table'];
    }

    public function save($id, $data, $size)
    {
        DB::table($this->dataTable)->insert([
            'id' => $id,
            'data' => $data,
            'size' =>  $size,
            'created_at' => now()
        ]);

        
    }

   public function retrieve($id)
{
    $record = DB::table($this->dataTable)
        ->where('id', $id)
        ->first(); 

    if (!$record) return null;

    return [
        'data' => $record->data,
        'size' => $record->size ?? strlen($record->data),
        'created_at' => $record->created_at ?? null
    ];
}

    public function delete($id)
    {
        DB::table($this->dataTable)->where('id', $id)->delete();
        DB::table($this->metaTable)->where('id', $id)->delete();
    }
}
