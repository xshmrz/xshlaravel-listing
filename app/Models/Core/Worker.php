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
 * Class Worker
 * 
 * @property int $id
 * @property int|null $listing_id
 * @property string|null $email
 * @property string|null $password
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $gsm
 * @property Carbon|null $birthday
 * @property string $gender
 * @property string $role
 * @property string $status
 * @property string $can_login_panel
 * @property array|null $upload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Listing|null $listing
 * @property Collection|Appointment[] $appointments
 *
 * @package App\Models\Core
 */
class Worker extends Model
{
	use SoftDeletes;
	use QueryBuilderModelTrait;
	use TraitUpload;
	const ID = 'id';
	const LISTING_ID = 'listing_id';
	const EMAIL = 'email';
	const PASSWORD = 'password';
	const FIRST_NAME = 'first_name';
	const LAST_NAME = 'last_name';
	const GSM = 'gsm';
	const BIRTHDAY = 'birthday';
	const GENDER = 'gender';
	const ROLE = 'role';
	const STATUS = 'status';
	const CAN_LOGIN_PANEL = 'can_login_panel';
	const UPLOAD = 'upload';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	protected $table = 'worker';

	protected $columns = [
		'id',
		'listing_id',
		'email',
		'password',
		'first_name',
		'last_name',
		'gsm',
		'birthday',
		'gender',
		'role',
		'status',
		'can_login_panel',
		'upload',
		'created_at',
		'updated_at',
		'deleted_at'
	];

	protected $casts = [
		self::ID => 'int',
		self::LISTING_ID => 'int',
		self::BIRTHDAY => 'datetime',
		self::UPLOAD => 'json',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $hidden = [
		self::PASSWORD
	];

	protected $fillable = [
		self::LISTING_ID,
		self::EMAIL,
		self::PASSWORD,
		self::FIRST_NAME,
		self::LAST_NAME,
		self::GSM,
		self::BIRTHDAY,
		self::GENDER,
		self::ROLE,
		self::STATUS,
		self::CAN_LOGIN_PANEL,
		self::UPLOAD
	];

	public function listing(): BelongsTo
	{
		return $this->belongsTo(Listing::class);
	}

	public function appointments(): HasMany
	{
		return $this->hasMany(Appointment::class);
	}
}
