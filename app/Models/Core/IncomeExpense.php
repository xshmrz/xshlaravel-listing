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
 * Class IncomeExpense
 * 
 * @property int $id
 * @property int|null $listing_id
 * @property int|null $income_expense_category_id
 * @property Carbon|null $day
 * @property float|null $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property IncomeExpenseCategory|null $income_expense_category
 * @property Listing|null $listing
 *
 * @package App\Models\Core
 */
class IncomeExpense extends Model
{
	use SoftDeletes;
	use QueryBuilderModelTrait;
	use TraitUpload;
	const ID = 'id';
	const LISTING_ID = 'listing_id';
	const INCOME_EXPENSE_CATEGORY_ID = 'income_expense_category_id';
	const DAY = 'day';
	const AMOUNT = 'amount';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	protected $table = 'income_expense';

	protected $columns = [
		'id',
		'listing_id',
		'income_expense_category_id',
		'day',
		'amount',
		'created_at',
		'updated_at',
		'deleted_at'
	];

	protected $casts = [
		self::ID => 'int',
		self::LISTING_ID => 'int',
		self::INCOME_EXPENSE_CATEGORY_ID => 'int',
		self::DAY => 'datetime',
		self::AMOUNT => 'float',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime'
	];

	protected $fillable = [
		self::LISTING_ID,
		self::INCOME_EXPENSE_CATEGORY_ID,
		self::DAY,
		self::AMOUNT
	];

	public function income_expense_category(): BelongsTo
	{
		return $this->belongsTo(IncomeExpenseCategory::class);
	}

	public function listing(): BelongsTo
	{
		return $this->belongsTo(Listing::class);
	}
}
