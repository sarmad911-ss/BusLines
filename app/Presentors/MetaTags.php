<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 8/13/2018
 * Time: 2:48 PM
 */

namespace App\Presentors;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PageMeta
 * @package App\Classes
 *
 * @property string title
 * @property string description
 * @property string keywords
 * @property string|File image
 * @property string url
 * @property string og_title
 * @property string og_description
 * @property string|File og_image
 * @property string og_url
 * @property string og_site_name
 * @property string twitter_card
 */
class MetaTags extends Model
{
    protected $fillable = ['title', "description", "keywords", "image", "url",
        "og_title", "og_description", "og_image", "og_url", "og_site_name",
        "twitter_card",
    ];

    private $tags = [
        "description" => "description",
        "keywords" => "keywords",
        "image" => "image",
        "url" => "url",
        "og:title" => "og_title",
        "og:description" => "og_description",
        "og:image" => "og_image",
        "og:url" => "og_url",
        "og:site_name" => "og_site_name",
        "twitter:card" => "twitter_card"
    ];

    public function getOgTitleAttribute()
    {
        return $this->attributes['og_title'] ?? ($this->attributes['title'] ?? "");
    }

    public function getOgDescriptionAttribute()
    {
        return $this->attributes['og_description'] ?? ($this->attributes['description'] ?? "");
    }

    public function getOgImageAttribute()
    {
        if (empty($this->attributes['og_image']))
            return $this->getImageAttribute();
        if (!empty($this->attributes['og_image']))
            if ($this->attributes['og_image'] instanceof File)
                return $this->attributes['og_image']->url;
            elseif (is_string($this->attributes))
                return $this->attributes['og_image'];
    }

    public function getImageAttribute()
    {
        if (!empty($this->attributes['image']))
            if ($this->attributes['image'] instanceof File)
                return $this->attributes['image']->url;
            elseif (is_string($this->attributes))
                return $this->attributes['image'];
    }

    public function getOgSiteNameAttribute()
    {
        return $this->attributes['og_site_name'] ?? env("APP_NAME");
    }

    public function getOgUrlAttribute()
    {
        return $this->attributes['og_url'] ?? $this->getUrlAttribute();
    }

    public function getUrlAttribute()
    {
        return $this->attributes['url'] ?? url()->current();
    }

    /**
     * @return string
     */
    public function generate()
    {
        $meta = [];
        if (!empty($this->title))
            $meta[] = "<title>" . $this->title . "</title>";
        foreach ($this->tags as $name => $content) {
            if (!empty($this->{$content}))
                $meta[] = '<meta property="' . $name . '" content="' . $this->{$content} . '">';
        }
        return implode("\n", $meta);
    }
}