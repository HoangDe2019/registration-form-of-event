<?php
/**
 * User: kpistech2
 * Date: 2019-03-28
 * Time: 22:16
 */

namespace App\V1\CMS\Models;


use App\UserProductHistory;
use Illuminate\Support\Facades\DB;

class UserProductHistoryModel extends AbstractModel
{
    /**
     * OrderDetailModel constructor.
     *
     * @param UserProductHistory|null $model
     */
    public function __construct(UserProductHistory $model = null)
    {
        parent::__construct($model);
    }

    public function getAccumulation($customerCode)
    {
        $result = $this->model->select([
            'p.id as product_id',
            'p.code as product_code',
            'p.name as product_name',
            DB::raw("sum(" . $this->getTable() . '.qty) as qty'),
            DB::raw("sum(" . $this->getTable() . '.total_point) as point')
        ])
            ->join('products as p', 'p.id', '=', $this->getTable() . '.product_id')
            ->join('customers as c', 'c.id', '=', $this->getTable() . '.customer_id')
            ->where("c.code", $customerCode)
            ->groupBy($this->getTable() . '.product_id')
            ->get();
        return $result;
    }
}
