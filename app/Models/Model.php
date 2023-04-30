<?php

/**
 * Created by PhpStorm.
 * User: trinhnv
 * Date: 28/12/2017
 * Time: 15:29
 */

namespace App\Models;

use App\Helpers\StorageHelper;
use App\Traits\Models\FillableFields;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    use FillableFields;

    public $autoCreator = false;
    public $autoUpdater = false;
    public $autoRank = false;

    /**
     * @return string
     */
    public static function table()
    {
        return with(new static)->table;
    }

    /**
     * Insert each item as a row. Does not generate events.
     *
     * @param array $items
     *
     * @return bool
     */
    public static function insertAll(array $items)
    {
        $now = Carbon::now();

        $items = collect($items)->map(function (array $data) use ($now) {
            if (with(new static)->autoCreator && !isset($data['creator_id']) && AuthAdmin::check()) {
                $data['creator_id'] = AuthAdmin::id();
            }
            if (with(new static)->autoRank && !isset($data['rank'])) {
                $data['rank'] = with(new static)->max('rank') + 1;
            }
            return with(new static)->timestamps ? array_merge([
                with(new static)::CREATED_AT => $now,
                with(new static)::UPDATED_AT => $now,
            ], $data) : $data;
        })->all();

        return DB::table(static::table())->insert($items);
    }

    /**
     * Insert each item as a row. Does not generate events.
     *
     * @param array $conditions
     * @param bool $isPermanently
     *
     * @return bool
     */
    public static function deleteAll($conditions, $isPermanently = false)
    {
        $model = new static;

        $models = $model::where($conditions);
        return $isPermanently ? $models->forceDelete() : $models->delete();
    }

    public function scopeSearch($query, $searchTerm = '')
    {
        return $query;
    }

    public function scopeHomePage($query, $limit = null)
    {
        $query = $query->active()->lang()->newest();
        return $limit ? $query->take($limit) : $query;
    }

    public function scopeNewest($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function scopeLang($query, $lang = null)
    {
        return $query->where('lang', $lang ?? config('app.locale'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', IS_ACTIVE);
    }

    public function scopeCreateBy($query, $userId = '')
    {
        return $query->where('creator_id', $userId);
    }

    public function scopeUpdateBy($query, $userId = '')
    {
        return $query->where('updater_id', $userId);
    }

    public function scopeByUser($query, $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'creator_id', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updater_id', 'id');
    }

    public function getFullImageUrlAttribute($key)
    {
        if ($this->attributes) {
            if (!Str::startsWith($this->attributes['image_url'], 'http')) {
                return StorageHelper::getImagePath($this->storagePath, $this->id, $this->attributes['image_url']);
            }
            return $this->attributes['image_url'];
        }
    }

    public function getFullThumbUrlAttribute($key)
    {
        if ($this->attributes) {
            if (!Str::startsWith($this->attributes['thumb_url'], 'http')) {
                return StorageHelper::getImagePath($this->storagePath, $this->id, $this->attributes['thumb_url']);
            }
            return $this->attributes['thumb_url'];
        }
    }
}
