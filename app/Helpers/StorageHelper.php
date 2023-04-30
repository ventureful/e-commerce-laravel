<?php

/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 05/03/2019
 * Time: 1:42 CH
 */

namespace App\Helpers;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class StorageHelper
{
    const TYPES = [
        'apps' => 0,
        'blog' => 1,
        'careers' => 2,
        'files' => 3,
    ];

    const TYPE_NAMES = [
        0 => 'apps',
        1 => 'blog',
        2 => 'careers',
        3 => 'files',
    ];

    //<editor-fold desc="Base methods">

    /**
     * @param $sourcePath
     * @param $targetPath
     *
     * @return bool
     */
    public static function copy($sourcePath, $targetPath)
    {
        return self::disk()->copy($sourcePath, $targetPath);
    }

    /**
     * @return Filesystem
     */
    private static function disk($diskName = 'local')
    {
        return Storage::disk($diskName);
    }

    /**
     * @param $sourcePath
     * @param $targetPath
     *
     * @return bool
     */
    public static function move($sourcePath, $targetPath)
    {
        return self::disk()->move($sourcePath, $targetPath);
    }

    /**
     * @param $path
     *
     * @return string
     * @throws FileNotFoundException
     */
    public static function get($path)
    {
        return self::disk()->get($path);
    }

    public static function locatePath($path)
    {
        return self::disk()->path($path);
    }

    public static function imagePath($path)
    {
        return self::image_path($path);
    }

    public static function urlPath($path)
    {
        return self::disk()->url($path);
    }

    public static function download($path)
    {
        return self::disk()->download($path);
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    public static function mimeType($path)
    {
        return self::disk()->mimeType($path);
    }

    /**
     * @param $id
     * @param $file
     * @param string $folderType
     * @param bool $withTimestamp
     * @return string
     * @throws Exception
     */
    public static function saveAppPath($id, $file, $folderType = 'app', $withTimestamp = false)
    {
        $fileName = $file->getClientOriginalName();
        if ($withTimestamp) $fileName = date('Ym/d/') . $fileName;
        $filePath = self::getImagePath($folderType, $id);
        self::save($file, $filePath, $fileName);
        return $filePath . $fileName;
    }

    /**
     * @param string $filePath
     * @param string $fileName
     * @param bool $fullPath
     * @return string
     */
    public static function getThumbPath($filePath = '', $fileName = '', $fullPath = true)
    {
        $imagePath = self::imagePath($filePath);
        if (file_exists($imagePath)) {
            $name = pathinfo($fileName, PATHINFO_FILENAME);
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $thumbPath = $filePath . '/' . $name . THUMBNAIL_SUFFIX . '.' . $extension;
            $imageThumbPath = self::imagePath($thumbPath);
            if (!file_exists($imageThumbPath)) {
                self::generateThumbnail($filePath);
            }

            return self::getFullPath($thumbPath, $fullPath);

        }
        return self::getFullPath($filePath, $fullPath);
    }

    public static function generateThumbnailRatio($imagePath, $width = THUMBNAIL_WIDTH)
    {
        $img = Image::make(self::imagePath($imagePath));
        $suffix = '-' . $width;
        $height = (int)($width * $img->getHeight() / $img->width());

        self::resizeAndSave($img, $suffix, $width, $height);
    }

    public static function generateThumbnail($imagePath, $width = THUMBNAIL_WIDTH, $height = THUMBNAIL_HEIGHT)
    {
        $img = Image::make(self::imagePath($imagePath));
        $suffix = '-' . $width;
        self::resizeAndSave($img, $suffix, $width, $height);
    }

    private static function resizeAndSave($img, $name, $width = THUMBNAIL_WIDTH, $height = THUMBNAIL_HEIGHT)
    {
        $img->fit($width, $height);
        // finally we save the image as a new file
        $savePath = $img->dirname . '/' . $img->filename . $name . '.' . $img->extension;

        $img->save($savePath);
    }
    //</editor-fold>

    //<editor-fold desc="App">

    /**
     * @param string $bookId
     *
     * @param string $fileName
     *
     * @param bool $fullPath
     *
     * @return string
     * @throws Exception
     */
    public static function getImagePath($folderType = 'app', $id = '', $fileName = '', $fullPath = true)
    {
        $folderPath = self::getTypeFolder(self::TYPES[$folderType]);
        return self::getFullPath($folderPath . $id . '/' . $fileName, $fullPath);
    }

    /**
     * @param $type
     *
     * @return string
     * @throws Exception
     */
    private static function getTypeFolder($type)
    {
        if (!isset(self::TYPE_NAMES[$type]))
            throw new Exception('type is undefined');
        return self::TYPE_NAMES[$type] . '/';
    }

    public static function getFullPath($path, $fullPath)
    {
        return ($fullPath ? (url('images') . '/') : '') . $path;
    }

    /**
     * @param        $file
     * @param        $path
     * @param string $fileName
     * @param null $diskName
     */
    public static function save($file, $path, $fileName = '', $diskName = null)
    {
        if (!$fileName) {
            $fileName = $file->getClientOriginalName();
        }
        self::disk($diskName)->putFileAs($path, $file, $fileName);

        return self::disk($diskName)->path($path . $fileName);
    }

    /**
     * @param      $id
     * @param      $file
     *
     * @return string
     * @throws Exception
     */
    public static function uploadImage($folderType, $id, $file)
    {
        $fileName = $file->getClientOriginalName();

        $filePath = self::getImagePath($folderType, $id, '', false);

        self::saveImage($file, $filePath, $fileName);
        return $filePath . $fileName;
    }

    /**
     * @param        $file
     * @param        $path
     * @param string $fileName
     */
    public static function saveImage($file, $path, $fileName = '')
    {
        if (!$fileName) {
            $fileName = $file->getClientOriginalName();
        }
        self::image_disk()->putFileAs($path, $file, $fileName);
    }

    /**
     * @return Filesystem
     */
    private static function image_disk()
    {
        return Storage::disk('image');
    }

    /**
     * @return Filesystem
     */
    private static function image_path($path)
    {
        return Storage::disk('image')->path($path);
    }

    //</editor-fold>

    //<editor-fold desc="Private methods">

    /**
     * @param $bookId
     * @param $fileName
     *
     * @return string
     * @throws FileNotFoundException
     * @internal param $bookPath
     */
    public static function getApp($bookId, $fileName)
    {
        return self::disk()->get(self::getImagePath($bookId) . $fileName);
    }

    //</editor-fold>

}
