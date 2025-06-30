<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\RepairOrder;

class CleanupRepairFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repairs:cleanup-files {--dry-run : Preview what will be deleted without deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned repair order files (photos and audio)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $this->info('Scanning repair files...');

        // الحصول على جميع ملفات الإصلاحات
        $repairFiles = Storage::disk('public')->files('repairs');

        if (empty($repairFiles)) {
            $this->info('No repair files found.');
            return 0;
        }

        // الحصول على جميع الملفات المستخدمة في قاعدة البيانات
        $usedFiles = RepairOrder::whereNotNull('photo')
            ->orWhereNotNull('audio')
            ->get(['photo', 'audio'])
            ->flatMap(function ($repair) {
                return array_filter([$repair->photo, $repair->audio]);
            })
            ->toArray();

        $orphanedFiles = [];
        $totalSize = 0;

        foreach ($repairFiles as $file) {
            if (!in_array($file, $usedFiles)) {
                $orphanedFiles[] = $file;
                $totalSize += Storage::disk('public')->size($file);
            }
        }

        if (empty($orphanedFiles)) {
            $this->info('✓ No orphaned files found. All repair files are in use.');
            return 0;
        }

        $this->warn("Found " . count($orphanedFiles) . " orphaned files (" . $this->formatBytes($totalSize) . ")");

        foreach ($orphanedFiles as $file) {
            $size = Storage::disk('public')->size($file);

            if ($dryRun) {
                $this->line("Would delete: {$file} ({$this->formatBytes($size)})");
            } else {
                if (Storage::disk('public')->delete($file)) {
                    $this->info("✓ Deleted: {$file} ({$this->formatBytes($size)})");
                } else {
                    $this->error("✗ Failed to delete: {$file}");
                }
            }
        }

        if ($dryRun) {
            $this->info("\nDry run completed. Use without --dry-run to actually delete the files.");
        } else {
            $this->info("\nCleanup completed! Freed up " . $this->formatBytes($totalSize) . " of storage.");
        }

        return 0;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
