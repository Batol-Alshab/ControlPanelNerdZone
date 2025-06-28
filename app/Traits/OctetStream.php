<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


trait OctetStream
{

    // public function uploadFile($request, $data, $name, $inputName = 'files')
    // {
    //     $requestFile = $request->file($inputName);
    //     try {
    //         $dir = 'public/files/' . $name;
    //         $fixName = $data->id . '-' . $name . '.' . $requestFile->extension();

    //         if ($requestFile) {
    //             Storage::putFileAs($dir, $requestFile, $fixName);
    //             $relativePath = 'files/' . $name . '/' . $fixName;

    //             $data->update([$inputName => $relativePath]);

    //             $absolutePath = storage_path("app/public/{$relativePath}");

    //             return response()->stream(function () use ($absolutePath) {
    //                 $stream = fopen($absolutePath, 'rb');
    //                 fpassthru($stream);
    //                 fclose($stream);
    //             }, 200, [
    //                 'Content-Type' => 'application/octet-stream',
    //                 'Content-Disposition' => 'attachment; filename="' . $fixName . '"',
    //                 'Content-Length' => filesize($absolutePath),
    //             ]);
    //         }

    //         return response()->json(['message' => 'No file found'], 422);
    //     } catch (\Throwable $th) {
    //         report($th);
    //         return response()->json(['error' => $th->getMessage()], 500);
    //     }
    // }

    public function storeToStorage(Request $request, $inputName = 'file', $subfolder = 'courses')
    {
        $file = $request->file($inputName);
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs($subfolder, $filename, 'local'); // stored in storage/app/courses

        return [
            'filename' => $filename,
            'path' => $path,
            // 'full_path' => storage_path("app\{$path}"),
            'full_path' => storage_path("app/{$path}"),
        ];
    }

    public function returnFromStorageAsOctet($relativePath, $downloadName = null)
    {
        $absolutePath = realpath(storage_path("app/{$relativePath}"));
        // $absolutePath = realpath(storage_path("app/{$relativePath}"));

        // $absolutePath = realpath(storage_path("app/{$relativePath}"));

        if (!$absolutePath || !file_exists($absolutePath)) {
            abort(404, 'File not found.');
        }

        return response()->stream(function () use ($absolutePath) {
            $stream = fopen($absolutePath, 'rb');
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . ($downloadName ?? basename($absolutePath)) . '"',
            'Content-Length' => filesize($absolutePath),
        ]);
    }
}
