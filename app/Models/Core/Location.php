<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Core;

use App\Traits\TraitUpload;
use Bjerke\ApiQueryBuilder\QueryBuilderModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Location
 * 
 * @property int $id
 * @property string|null $name
 * @property float|null $lat
 * @property float|null $lng
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $parent_id
 * @property string|null $parent_name
 * 
 * @property Collection|Listing[] $listings
 * @property Collection|User[] $users
 *
 * @package App\Models\Core
 */
class Location extends Model
{
	use SoftDeletes;
	use QueryBuilderModelTrait;
	use TraitUpload;
	const ID = 'id';
	const NAME = 'name';
	const LAT = 'lat';
	const LNG = 'lng';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	const PARENT_ID = 'parent_id';
	const PARENT_NAME = 'parent_name';
	protected $table = 'location';

	protected $columns = [
		'id',
		'name',
		'lat',
		'lng',
		'created_at',
		'updated_at',
		'deleted_at',
		'parent_id',
		'parent_name'
	];

	protected $casts = [
		self::ID => 'int',
		self::LAT => 'float',
		self::LNG => 'float',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::NAME,
		self::LAT,
		self::LNG,
		self::PARENT_ID,
		self::PARENT_NAME
	];

	public function listings(): HasMany
	{
		return $this->hasMany(Listing::class);
	}

	public function users(): HasMany
	{
		return $this->hasMany(User::class);
	}
}
