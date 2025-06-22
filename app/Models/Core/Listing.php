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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Listing
 * 
 * @property int $id
 * @property int|null $location_id
 * @property int|null $user_id
 * @property int|null $listing_category_id
 * @property string|null $name
 * @property string|null $gsm
 * @property string|null $address
 * @property float|null $lat
 * @property float|null $lng
 * @property string $status
 * @property array|null $upload
 * @property array|null $working_day
 * @property array|null $working_hour
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property ListingCategory|null $listing_category
 * @property Location|null $location
 * @property User|null $user
 * @property Collection|Appointment[] $appointments
 * @property Collection|Customer[] $customers
 * @property Collection|IncomeExpense[] $income_expenses
 * @property Collection|Worker[] $workers
 *
 * @package App\Models\Core
 */
class Listing extends Model
{
	use SoftDeletes;
	use QueryBuilderModelTrait;
	use TraitUpload;
	const ID = 'id';
	const LOCATION_ID = 'location_id';
	const USER_ID = 'user_id';
	const LISTING_CATEGORY_ID = 'listing_category_id';
	const NAME = 'name';
	const GSM = 'gsm';
	const ADDRESS = 'address';
	const LAT = 'lat';
	const LNG = 'lng';
	const STATUS = 'status';
	const UPLOAD = 'upload';
	const WORKING_DAY = 'working_day';
	const WORKING_HOUR = 'working_hour';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	protected $table = 'listing';

	protected $columns = [
		'id',
		'location_id',
		'user_id',
		'listing_category_id',
		'name',
		'gsm',
		'address',
		'lat',
		'lng',
		'status',
		'upload',
		'working_day',
		'working_hour',
		'created_at',
		'updated_at',
		'deleted_at'
	];

	protected $casts = [
		self::ID => 'int',
		self::LOCATION_ID => 'int',
		self::USER_ID => 'int',
		self::LISTING_CATEGORY_ID => 'int',
		self::LAT => 'float',
		self::LNG => 'float',
		self::UPLOAD => 'json',
		self::WORKING_DAY => 'json',
		self::WORKING_HOUR => 'json',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::LOCATION_ID,
		self::USER_ID,
		self::LISTING_CATEGORY_ID,
		self::NAME,
		self::GSM,
		self::ADDRESS,
		self::LAT,
		self::LNG,
		self::STATUS,
		self::UPLOAD,
		self::WORKING_DAY,
		self::WORKING_HOUR
	];

	public function listing_category(): BelongsTo
	{
		return $this->belongsTo(ListingCategory::class);
	}

	public function location(): BelongsTo
	{
		return $this->belongsTo(Location::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function appointments(): HasMany
	{
		return $this->hasMany(Appointment::class);
	}

	public function customers(): HasMany
	{
		return $this->hasMany(Customer::class);
	}

	public function income_expenses(): HasMany
	{
		return $this->hasMany(IncomeExpense::class);
	}

	public function workers(): HasMany
	{
		return $this->hasMany(Worker::class);
	}
}
