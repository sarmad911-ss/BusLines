<?php

namespace App\Models\core;

use App\Models\Activity;
use Carbon\Carbon;
use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use IvanLemeshev\Laravel5CyrillicSlug\Slug;

/**
 * Class AcsimaModel
 * @package App\Models\core
 *
 * @property Activity last_update
 */
class AcsimaModel extends Model
{
    use EloquentJoin;

    const DATE_PICKER_FORMAT = 'Y-m-d';

    protected $slugFromField = "name";

    function setSlugAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['slug'] = $value;
            /** generate and check slug from param */
            $this->generateSlug("slug", true);
        }
    }

    private $last_update;
    /**
     * @param null|string $attribute - attribute to generate from
     * @param bool $force - force generate from this attribute
     * @return $this
     */
    protected function generateSlug($attribute = null, $force = false)
    {
        /** @var string $attribute - field to generate from. If nothing - return */
        $attribute = $attribute ?? $this->slugFromField;
        if (empty($attribute)) return $this;
        /** @var string $slug - get current slug */
        $slug = $this->attributes['slug'] ?? "";
        /** if slug empty, or need force generate */
        if (empty($slug) || $force) {
            $slug = (new Slug())->make($this->attributes[$attribute]);
        }
        /** @var Builder $withSameSlug - get rows with same slug but not self */
        $withSameSlug = self::where("slug", 'ilike', $slug);
        if (!empty($this->id))
            $withSameSlug = $withSameSlug->where("id", "!=", $this->id);
        $withSameSlug = $withSameSlug->get();
        /** if exists - add suffix */
        if ($withSameSlug->count()) {
            /** @var Builder $withSameSlug - find all rows with slug like this */
            $withSameSlug = self::where("slug", 'ilike', $slug . "%");
            if (!empty($this->id))
                $withSameSlug = $withSameSlug->where("id", "!=", $this->id);
            $withSameSlug = $withSameSlug->get();
            $n = 1;
            /** if more than one - get max num */
            if ($withSameSlug->count() > 1) {
                $nums = $withSameSlug->map(function ($item) {
                    return intval(mb_substr($item->slug, strrpos($item->slug, "-") + 1));
                });
                $n = max($nums->toArray());
            }
            /** append num to slug */
            $slug .= "-" . (++$n);
        }
        $this->attributes['slug'] = $slug;
        return $this;
    }

	public function save(array $options = [])
	{
		$saved = false;
		if($this->isDirty()) {
			$saved = parent::save($options); // TODO: Change the autogenerated stub
			$activity = new Activity([
				'table' => $this->getTable(),
				'row_id' => $this->id,
				'time' => time(),
				'user_id' => session()->has("user") ? session()->get("user")->id : null,
			]);
			$activity->save();
		}
		return $saved;
	}

	public function getLastUpdateAttribute(){
    	if(empty($this->id))
    		return null;
        return Activity::where(["table"=>$this->getTable(),'row_id'=>$this->id])->orderBy("id")->get()->last();
	}

    public static function toTimestamp($date)
    {
        return Carbon::createFromFormat(self::DATE_PICKER_FORMAT, $date)->timestamp;
    }

}