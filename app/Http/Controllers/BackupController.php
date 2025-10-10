<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function download()
    {
        Artisan::call('backup:run --only-db');

        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);

        $files = collect($disk->allFiles(''))->filter(function ($file) {
            return str_ends_with($file, '.zip');
        })->sort();

        if ($files->isEmpty()) {
            return back()->with('error', 'لم يتم العثور على أي نسخة احتياطية');
        }

        $latestFile = $files->last();

        // add system log
        SystemLog::create([
            'description' => 'قام المستخدم بتنزيل نسخة احتياطية من قاعدة البيانات',
            'user_id'     => auth()->id(),
        ]);

        return $disk->download($latestFile);
    }
}
