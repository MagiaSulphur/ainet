<?php

namespace App\Http\Controllers;

use App\Models\TshirtImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TshirtImageFileController extends Controller
{
    public function show(Request $request, TshirtImage $tshirtImage): BinaryFileResponse
    {
        if ($tshirtImage->customer_id !== null) {
            abort_unless(
                $request->user()?->isAdmin()
                || $request->user()?->isEmployee()
                || $request->user()?->id === $tshirtImage->customer_id,
                403
            );

            $path = storage_path('app/private/tshirt_images_private/'.$tshirtImage->image_url);
        } else {
            $path = Storage::disk('public')->path('tshirt_images/'.$tshirtImage->image_url);
        }

        abort_unless(is_file($path), 404);

        return response()->file($path);
    }
}
