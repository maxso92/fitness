<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Exception;

class ImagesCompress extends Command
{
    const ACTION_RF = 'rf';
    const ACTION_CD = 'cd';
    const ACTION_CF = 'cf';

    const IMAGE_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'webp'
    ];

    /**
     * @var array
     */
    private $count;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:compress {--action=} {--height=?} {--width=?} {--path=?} {--quality=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'compress images';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $type = $this->option('action');

        if ($type == self::ACTION_RF) {
            $this->resizeFile();
        }
        if ($type == self::ACTION_CD) {
            $this->compressRec();
        }
        else if ($type == self::ACTION_CF) {
            $this->compressFile();
        }
    }

    private function resizeFile() {
        $width = $this->option('width');
        $height = $this->option('height');
        $path = public_path() . '/' . $this->option('path');

        if (!File::isFile($path)) {
            $this->error($path . ' is not a file');
            return;
        }

        $extension = File::extension($path);
        if (!in_array($extension, self::IMAGE_EXTENSIONS)) {
            $this->error($extension . ' is not allowed extension to compress');
        }

        $image = Image::make($path)->resize($width, $height);
        $image->save($path, 100);
    }

    private function compressRec() {
        $this->count = [];
        $this->info('Image compressing:');

        $path = public_path() . '/' . $this->option('path');
        $files = File::allFiles($path);

        foreach ($files as $file) {
            $extension = $file->getExtension();

            if (in_array($extension, self::IMAGE_EXTENSIONS)) {
                try {
                    $image = Image::make($file->getRealPath());
                    $image->save($file->getRealPath(), 90);

                    $this->incrementCount($extension);
                    $this->line('compressing ' . $file->getRealPath());
                }
                catch (Exception $e) {
                    $this->error('ERROR: ' . $file->getRealPath() . ' - with message ' . $e->getMessage());
                }
            }
        }

        $this->newLine();
        $this->info('The compressing was completed:');
        foreach ($this->count as $extension => $count) {
            $this->line($extension . ' - ' . $count);
        }
    }

    private function compressFile() {
        $quality = $this->option('quality');

        if ($quality == '?') {
            $quality = 100;
        }

        $path = public_path() . '/' . $this->option('path');

        if (!File::isFile($path)) {
            $this->error($path . ' is not a file');
            return;
        }

        $extension = File::extension($path);
        if (!in_array($extension, self::IMAGE_EXTENSIONS)) {
            $this->error($extension . ' is not allowed extension to compress');
        }


        try {
            $image = Image::make($path);
            $image->save($path, $quality);

            $this->line('compressing ' . $path);
        }
        catch (Exception $e) {
            $this->error('ERROR: ' . $path . ' - with message ' . $e->getMessage());
        }

        $this->info('The compressing was completed');
    }

    private function incrementCount(string $extension = null) {
        if ($extension == null) {
            return;
        }

        if (!array_key_exists($extension, $this->count)) {
            $this->count[$extension] = 0;
        }

        $this->count[$extension]++;
    }
}
