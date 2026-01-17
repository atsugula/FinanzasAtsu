<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TransactionAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionAttachmentController extends Controller
{
    public function storeTmp(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $file = $data['file'];

        // Guardamos en tmp por usuario (public)
        $path = $file->store("transactions/tmp/{$user->id}", 'public');

        $att = TransactionAttachment::create([
            'user_id' => $user->id,
            'transaction_id' => null,
            'path' => $path,
            'is_temp' => true,
        ]);

        return response()->json([
            'id' => $att->id,
            'url' => asset('storage/' . $att->path),
            'name' => $file->getClientOriginalName(),
        ]);
    }

    public function destroyTmp(Request $request, int $id)
    {
        $user = $request->user();

        $att = TransactionAttachment::query()
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->where('is_temp', true)
            ->whereNull('transaction_id')
            ->firstOrFail();

        Storage::disk('public')->delete($att->path);
        $att->delete();

        return response()->json(['ok' => true]);
    }
}
