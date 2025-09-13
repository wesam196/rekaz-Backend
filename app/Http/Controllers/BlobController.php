<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlobStorageService;

class BlobController extends Controller
{
    protected $storage;

    public function __construct(BlobStorageService $storage)
    {
        $this->storage = $storage;
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'data' => 'required|string'
        ]);

        $decodedData = base64_decode($request->input('data'), true);
        if ($decodedData === false) {
            return response()->json(['error' => 'Invalid Base64 data'], 400);
        }
        
        $size = strlen($decodedData);
        $this->storage->save($request->input('id'), $decodedData, $size);

        return response()->json(['message' => 'Blob stored successfully']);
    }

   public function show($id)
{
    $data = $this->storage->retrieve($id);

    if (!$data) {
        return response()->json(['error' => 'Blob not found'], 404);
    }

    return response()->json([
        'id' => $id,
        'data' => base64_encode($data['data']),
        'size' => strlen($data['data']),
        'created_at' => $data['created_at'] ?? null
    ]);
}
    public function delete($id)
    {
        $this->storage->delete($id);
        return response()->json(['message' => 'Blob deleted successfully']);
    }
}
