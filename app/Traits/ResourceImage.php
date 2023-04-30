<?php

namespace App\Traits;

use App\Helpers\StorageHelper;
use Illuminate\Http\Request;

trait ResourceImage
{
    protected $imageSizes = FEATURE_SIZES;
    protected $thumbSizes = THUMB_SIZES;

    public function getValuesToSave(Request $request, $record = null)
    {
        $data = $request->only($this->getResourceModel()::getFillableFields());

        if (!$request->has('image_file')) {
            unset($data['image_url']);
        }
        if (!$request->has('thumb_file')) {
            unset($data['thumb_url']);
        }
        return $data;
    }

    public function getRedirectAfterSave($record, $request, $isEdit = false)
    {
        if ($request->has('image_file') && $request->image_file) {
            $imagePath = StorageHelper::uploadImage($this->getResourceImage(), $record->id, $request->file('image_file'));
            foreach ($this->imageSizes as $width) {
                StorageHelper::generateThumbnailRatio($imagePath, $width);
            }
        }
        if ($request->has('thumb_file') && $request->thumb_file) {
            $thumbPath = StorageHelper::uploadImage($this->getResourceImage(), $record->id, $request->file('thumb_file'));
            foreach ($this->thumbSizes as $width) {
                StorageHelper::generateThumbnail($thumbPath, $width, $height = $width);
            }
        }
        return redirect(route($this->getResourceRoutesAlias() . '.index'));
    }

    public function getResourceImage()
    {
        if (property_exists($this, 'resourceImage') && !empty($this->resourceImage)) {
            return $this->resourceImage;
        } else {
            throw new \InvalidArgumentException('The property "resourceImage" is not defined');
        }
    }
}
