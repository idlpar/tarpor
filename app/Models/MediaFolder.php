<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class MediaFolder extends Model
{
    use SoftDeletes, NodeTrait;

    protected $fillable = ['name', 'path', 'parent_id'];
    protected $dates = ['deleted_at'];

    public function getLftName() { return 'lft'; }
    public function getRgtName() { return 'rgt'; }
    public function getParentIdName() { return 'parent_id'; }
    public function getDepthName() { return 'depth'; } // Add this line

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->setPath();
        });

        static::created(function ($model) {
            Storage::disk('public')->makeDirectory($model->path);

            // Ensure depth is set correctly after creation
            if ($model->parent_id) {
                $model->depth = $model->parent->depth + 1;
            } else {
                $model->depth = 0;
            }
            $model->save();
        });

        static::deleting(function ($model) {
            if ($model->isForceDeleting()) {
                Storage::disk('public')->deleteDirectory($model->path);
            }
        });
    }

    public function setPath()
    {
        $slug = Str::slug($this->name);

        if ($this->parent_id) {
            $parent = self::find($this->parent_id);
            $this->path = $parent ? $parent->path.'/'.$slug : $slug;
        } else {
            $this->path = $slug;
        }
    }

    public static function createWithPath($name, $parentId = null)
    {
        $parent = $parentId ? self::find($parentId) : null;

        $folder = new self([
            'name' => $name,
        ]);

        if ($parent) {
            $folder->path = $parent->path.'/'.Str::slug($name);
            $folder->appendToNode($parent)->save();
        } else {
            $folder->path = Str::slug($name);
            $folder->saveAsRoot();
        }

        // Create physical directory
        Storage::disk('public')->makeDirectory($folder->path);

        return $folder;
    }

    // Relationship to child folders
    public function files()
    {
        return $this->hasMany(Media::class, 'folder_id');
    }

    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    // Relationship to parent folder
    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    // Relationship to media items
    public function media()
    {
        return $this->hasMany(Media::class, 'directory', 'path');
    }

    public function trashedChildren()
    {
        return $this->hasMany(__CLASS__, 'parent_id')->onlyTrashed();
    }

    public function trashedMedia()
    {
        return $this->hasMany(Media::class, 'directory', 'path')->onlyTrashed();
    }
}
