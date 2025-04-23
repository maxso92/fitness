<?php

namespace App\Imports;


use App\Models\Ad;
use App\Models\City;
use App\Models\Category;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;

class AdsImport implements ToModel, WithHeadingRow, WithEvents
{
    use RegistersEventListeners;

    public static $importCount = 0;
    protected $tempFolder;

    public function __construct($tempFolder)
    {
        $this->tempFolder = $tempFolder;
    }

    public function model(array $row)
    {
        $city = City::where('name', $row['gorod'])->first();
        $category = Category::where('name', $row['sfera_deiatelnosti'])->first();

        $ad = new Ad([
            'city_id' => $city ? $city->id : null,
            'category' => $category ? $category->id : 1, // change 'category' to 'category_id'
            'name' => $row['nazvanie_vakansii'],
            'price' => $row['zarplata'],
            'work' => $row['zaniatost'],
            'work_schedule' => $row['grafik_raboty'],
            'work_experience' => $row['opyt_raboty'],
            'requirements' => $row['trebovaniia'],
            'responsibilities' => $row['obiazannosti'],
            'description' => $row['opisanie'],
            'location' =>  $row['mestonaxozdenie'],
            'whatsapp' => $row['whatsapp'],
            'telegram' =>  $row['telegram'],
            'viber' => $row['viber'],
            'moderate' => 1,
            'status' => 0,
            'user_id' =>  auth()->id()
        ]);


        $ad->save();

        $ad->slug = $ad->id . '-' . Str::slug($ad->name, '-');
        $ad->save();

/*
        $imagesPath = storage_path("app/{$this->tempFolder}/images/{$row['id']}");
        if (is_dir($imagesPath)) {

            $advertisementFolder = public_path('storage/images/' . $ad->id);
            if (!file_exists($advertisementFolder)) {
                mkdir($advertisementFolder, 0755, true);
            }

            $images = [];

            foreach (glob($imagesPath.'/*.{jpg,jpeg,png,gif}', GLOB_BRACE) as $file) {
                $filename = basename($file);
                copy($file, "{$advertisementFolder}/{$filename}");
                $images[] = "images/{$ad->id}/{$filename}";
            }

            $ad->images = $images;
            $ad->save();
        }
 */


        $imagesPath = storage_path("app/{$this->tempFolder}/images/{$row['id']}");
        if (is_dir($imagesPath)) {
            $advertisementFolder = public_path('storage/images/' . $ad->id);
            if (!file_exists($advertisementFolder)) {
                mkdir($advertisementFolder, 0755, true);
            }

            $imagePaths = [];

            foreach (glob($imagesPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE) as $form_file) {
                $filename = time() . '.' . 'webp'; // Generate a unique filename with webp extension
                $location = $advertisementFolder . '/' . $filename;

                $original_image = imagecreatefromstring(file_get_contents($form_file));
                imagewebp($original_image, $location);

                $imagePaths[] = 'images/' . $ad->id . '/' . $filename;

                // Creating and saving thumbnail images with different sizes
                $imageName = pathinfo($filename, PATHINFO_FILENAME);

                $thumbnail1 = $imageName . '_165x165.webp';
                if (!$this->createThumbnail($location, 165, 165, $advertisementFolder, $thumbnail1)) {
                    throw new \Exception('Failed to create thumbnail 165x165');
                }

                $thumbnail2 = $imageName . '_330x250.webp';
                if (!$this->createThumbnail($location, 330, 250, $advertisementFolder, $thumbnail2)) {
                    throw new \Exception('Failed to create thumbnail 330x250');
                }

                $thumbnail3 = $imageName . '_336x257.webp';
                if (!$this->createScaledImage($location, 336, 257, $advertisementFolder, $thumbnail3)) {
                    throw new \Exception('Failed to create scaled image 336x257');
                }
            }

            // Save image paths in the 'images' column of the ad then update the data
            $ad->images = $imagePaths;
            $ad->save();
        }


        self::$importCount++;
        Event::dispatch('adImported', $ad);

        return $ad;
    }

    public static function afterImport(AfterImport $event)
    {
        session()->flash('importedCount', self::$importCount);
    }


    private function createThumbnail(string $src, int $desired_width, int $desired_height, string $destination, string $filename)
    {
        // Read the source image
        $source_image = imagecreatefromwebp($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);

        // Calculate the aspect ratio
        $src_ratio = $width / $height;
        $dest_ratio = $desired_width / $desired_height;

        if ($src_ratio > $dest_ratio) {
            $new_height = $desired_height;
            $new_width = $width / ($height / $desired_height);
        } else {
            $new_width = $desired_width;
            $new_height = $height / ($width / $desired_width);
        }

        // Create a new, virtual image
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        // Preserve transparency
        imagealphablending($virtual_image, false);
        imagesavealpha($virtual_image, true);

        // Fill the background with transparent color
        $transparent = imagecolorallocatealpha($virtual_image, 0, 0, 0, 127);
        imagefill($virtual_image, 0, 0, $transparent);

        // Copy and resize old image into new image
        imagecopyresampled($virtual_image, $source_image, 0 - ($new_width - $desired_width) / 2, 0 - ($new_height - $desired_height) / 2, 0, 0, $new_width, $new_height, $width, $height);

        // Save the new image
        return imagewebp($virtual_image, $destination . '/' . $filename);
    }


    private function createScaledImage(string $src, int $desired_width, int $desired_height, string $destination, string $filename)
    {
        // Read the source image
        $source_image = imagecreatefromwebp($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);

        // Calculate the scaling ratio
        $width_ratio = $desired_width / $width;
        $height_ratio = $desired_height / $height;
        $scale_ratio = min($width_ratio, $height_ratio);

        // Calculate the new dimensions
        $new_width = (int) ($width * $scale_ratio);
        $new_height = (int) ($height * $scale_ratio);

        // Create a new, virtual image
        $virtual_image = imagecreatetruecolor($new_width, $new_height);

        // Preserve transparency
        imagealphablending($virtual_image, false);
        imagesavealpha($virtual_image, true);

        // Fill the background with transparent color
        $transparent = imagecolorallocatealpha($virtual_image, 0, 0, 0, 127);
        imagefill($virtual_image, 0, 0, $transparent);

        // Copy and resize old image into new image
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Save the new image
        return imagewebp($virtual_image, $destination . '/' . $filename);
    }


}
