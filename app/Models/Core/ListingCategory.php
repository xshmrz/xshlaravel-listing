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
 * Class ListingCategory
 * 
 * @property int $id
 * @property string|null $name
 * @property string $status
 * @property array|null $upload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Listing[] $listings
 *
 * @package App\Models\Core
 */
class ListingCategory extends Model
{
	use SoftDeletes;
	use QueryBuilderModelTrait;
	use TraitUpload;
	const ID = 'id';
	const NAME = 'name';
	const STATUS = 'status';
	const UPLOAD = 'upload';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	protected $table = 'listing_category';

	protected $columns = [
		'id',
		'name',
		'status',
		'upload',
		'created_at',
		'updated_at',
		'deleted_at'
	];

	protected $casts = [
		self::ID => 'int',
		self::UPLOAD => 'json',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::NAME,
		self::STATUS,
		self::UPLOAD
	];

	public function listings(): HasMany
	{
		return $this->hasMany(Listing::class);
	}
}
