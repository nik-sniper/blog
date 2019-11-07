<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use Sluggable;

    protected $fillable = ["title", "content", "date", "description"];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            "post_tags",
            "post_id",
            "tag_id"
        );
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = Auth::user()->id;
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    public function uploadImage($image)
    {
        if ($image === null) return;

        $this->removeImage();
        $filename = mt_rand(0, 10000).".".$image->extension();
        $image->storeAS("uploads", $filename);
        $this->image = $filename;
        $this->save();
    }

    public function removeImage()
    {
        if ($this->image != null) {
            Storage::delete("uploads/".$this->image);
        }
    }

    public function getImages()
    {
        if ($this->image === null) {
            return "/img/no-image.png";
        } else {
            return "/uploads/".$this->image;
        }
    }

    public function setCategory($id)
    {
        if ($id === null) return;

        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if ($ids === null) return;

        $this->tags()->sync($ids);
    }

    public function setPublic()
    {
        $this->status = 0;
        $this->save();
    }

    public function setDraft()
    {
        $this->status = 1;
        $this->save();
    }

    public function toggleStatus($value)
    {
        if ($value == null) {
            $this->setPublic();
        } else {
            $this->setDraft();
        }
    }

    public function setFeatured()
    {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_featured = 0;
        $this->save();
    }

    public function toggleFeatured($value)
    {
        if ($value == null) {
            $this->setStandart();
        } else {
            $this->setFeatured();
        }
    }

    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format("Y-m-d");

        $this->attributes["date"] = $date;
    }

    public function getDateAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format("d/m/y");

        return $date;
    }

    public function getCategoryTitle()
    {
        if ($this->category != null) {
            return $this->category->title;
        }

        return 'нет категории';
    }

    public function getTagsTitle()
    {
        if ($this->tags != null) {
            return implode(", ", $this->tags->pluck("title")->all());
        }

        return 'нет категории';
    }

    public function getCategoryId()
    {
        return ($this->category != null) ? $this->category->id : null;
    }

    public function getDate()
    {
        return Carbon::createFromFormat("d/m/y", $this->date)->format(" F m Y");
    }

    public function hasPrevious()
    {
        return self::where("id", "<", $this->id)->max("id");
    }

    public function hasNext()
    {
        return self::where("id", ">", $this->id)->min("id");
    }

    public function getPrevious()
    {
        return self::find($this->hasPrevious());
    }

    public function getNext()
    {
        return self::find($this->hasNext());
    }

    public function related()
    {
        return self::all()->except($this->id);
    }

    public function hasCategory()
    {
        return $this->category != null ? true : false;
    }
}