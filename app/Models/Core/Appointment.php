<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Core;

use App\Traits\TraitUpload;
use Bjerke\ApiQueryBuilder\QueryBuilderModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Appointment
 * 
 * @property int $id
 * @property int|null $user_id
 * @property int|null $listing_id
 * @property int|null $worker_id
 * @property int|null $customer_id
 * @property Carbon|null $day
 * @property string|null $hour
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Customer|null $customer
 * @property Listing|null $listing
 * @property User|null $user
 * @property Worker|null $worker
 *
 * @package App\Models\Core
 */
class Appointment extends Model
{
	use SoftDeletes;
	use QueryBuilderModelTrait;
	use TraitUpload;
	const ID = 'id';
	const USER_ID = 'user_id';
	const LISTING_ID = 'listing_id';
	const WORKER_ID = 'worker_id';
	const CUSTOMER_ID = 'customer_id';
	const DAY = 'day';
	const HOUR = 'hour';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	protected $table = 'appointment';

	protected $columns = [
		'id',
		'user_id',
		'listing_id',
		'worker_id',
		'customer_id',
		'day',
		'hour',
		'created_at',
		'updated_at',
		'deleted_at'
	];

	protected $casts = [
		self::ID => 'int',
		self::USER_ID => 'int',
		self::LISTING_ID => 'int',
		self::WORKER_ID => 'int',
		self::CUSTOMER_ID => 'int',
		self::DAY => 'datetime',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::USER_ID,
		self::LISTING_ID,
		self::WORKER_ID,
		self::CUSTOMER_ID,
		self::DAY,
		self::HOUR
	];

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function listing(): BelongsTo
	{
		return $this->belongsTo(Listing::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function worker(): BelongsTo
	{
		return $this->belongsTo(Worker::class);
	}
}
