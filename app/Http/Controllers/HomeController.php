<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\File;
use Illuminate\Support\Facades\Storage;


class HomeController extends Controller
{
    public function index(Request $requet){
        return Inertia::render('Home');
    }


    public function getFiles(Request $request){

        $fileData = File::where(function ($query) use ($request){
            if($request->has('id')){
                $query->where('id', $request->id);
            }
        })->get();

        return $fileData;
    }

    public function upload(Request $request){

        $request->validate([
            'file' => 'required|file|max:2048', // Limit the size to 2MB
        ]);

        // Check if the file is present in the request
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Generate a unique filename to avoid conflicts
            $fileName = $file->getClientOriginalName();

            // Store the file in the public directory
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            // Save file details to the database
            $savedFile = File::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'file_link' => Storage::url($filePath),
            ]);

            return response()->json(['message' => 'Arquivo enviado com sucesso!', 'file' => $savedFile], 201);
        }

        return response()->json(['message' => 'Nenhum arquivo encontrado.'], 400);
    }
}
