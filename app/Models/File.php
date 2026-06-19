<?php

namespace App\Models;

use App\Exceptions\ErrorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $name
 * @property string $path
 * @property string $slug
 * @property string $ext
 * @property string $file
 * @property string $domain
 * @property int $size
 * @property int $user_id
 * @property string $description
 * @property int $sort
 * @property-read string $src
 * @property-read array $thumbnails
 */
class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'slug',
        'ext',
        'file',
        'domain',
        'size',
        'user_id',
        'description',
        'sort',
    ];

    protected $hidden = [
        'description',
        'sort',
        'deleted_at',
        'user_id',
        'updated_at',
        'path',
        'laravel_through_key',
    ];

    protected $appends = [
        'thumbnails',
        'src',
    ];

    public function isImage(): bool
    {
        return in_array($this->ext, config('filemanager.images_ext', []));
    }

    public function getImageExtensionsAttribute(): array
    {
        return config('filemanager.images_ext', []);
    }

    public function getDist(): string
    {
        return base_path("static/{$this->path}/{$this->file}");
    }

    public function getSrcAttribute(): string
    {
        return "{$this->domain}/{$this->path}/{$this->file}";
    }

    public function getThumbnailsAttribute(): array
    {
        $thumbs     = config('filemanager.thumbs', []);
        $thumbnails = [];

        foreach ($thumbs as $thumb) {
            $slug = $thumb['slug'];
            $path = base_path("static/{$this->path}") . $this->slug . '_' . $slug . '.' . $this->ext;

            $src = file_exists($path)
                ? "{$this->domain}/{$this->path}{$this->slug}_{$slug}.{$this->ext}"
                : $this->getSrcAttribute();

            $thumbnails[$slug] = $src;
        }

        return $thumbnails;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($file) {
            if (! in_array(Str::lower($file->ext), config('filemanager.allowed_ext', []))) {
                $path = $file->path . '/' . $file->file;
                \Illuminate\Support\Facades\File::delete($path);
                throw new ErrorException('Unknown extension');
            }
        });
    }
}
