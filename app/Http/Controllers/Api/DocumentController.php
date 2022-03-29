<?php

namespace App\Http\Controllers\Api;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __invoke(Document $document)
    {
        $fileLocation = "/uploads/documents/{$document->filename}";

        if (Storage::has($fileLocation)) {
            $file = Storage::get($fileLocation);
            return response($file)->header('Content-Type', 'application/pdf');
        }

        return abort(404);
    }

    public function store(DocumentRequest $request)
    {
        $filename = "DOCUMENT_test_" . strtotime(now());
        $hashed_filename = md5($filename) . ".pdf";

        $stored = Storage::putFileAs(
            "/uploads/documents/{$request->type}",
            $request->file('document'),
            $hashed_filename
        );

        if ($stored) {
            $saved = Document::create([
                'filename' => $hashed_filename
            ]);
        }

        return [
            'status' => $stored && $saved
        ];
    }
}
