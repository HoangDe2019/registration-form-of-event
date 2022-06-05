<?php
/**
 * Created by PhpStorm.
 * User: kpistech2
 * Date: 2019-03-25
 * Time: 23:36
 */

namespace App\V1\CMS\Traits;


use App\Customer;
use App\CustomerType;
use App\Issue;
use App\Order;
use App\OrderDetail;
use App\SSC;
use App\Supports\Message;
use App\UnitConvert;
use App\User;
use App\UserProductHistory;
use App\UserSession;
use App\V1\CMS\Models\OrderHistoryDetailModel;
use App\V1\CMS\Models\PromotionDetailModel;
use Illuminate\Support\Facades\DB;

trait OrderTrait
{
    /**
     * @param int $id
     * @param array $input
     * @return mixed
     * @throws \Exception
     */
    private function changeStatus(int $id, array $input)
    {
        $status = $input['status'];
        $order = Order::find($id);
        if (empty($order)) {
            throw new \Exception(Message::get("orders.not-exist", "#$id"));
        }

        switch ($status) {
            case ORDER_STATUS_PENDING:
            case ORDER_STATUS_APPROVED:
            case ORDER_STATUS_REJECTED:
                $this->validateSts($order, $status);
                break;
            case ORDER_STATUS_SHIPPED:
                $this->validateSts($order, $status);
                $orderDetails = object_get($order, 'details', []);
                $orderDetails = !empty($orderDetails) ? array_pluck($orderDetails, null, 'id') : [];
                foreach ($input['details'] as $detail) {
                    /** @var OrderDetail $orderDetail */
                    $orderDetail = array_get($orderDetails, $detail['id']);
                    if (empty($orderDetail)) {
                        continue;
                    }

                    $inputQtyWillShip = !empty($detail['ship_number']) ? $detail['ship_number'] : 0;
                    $orderDetail->shipped_qty = !empty($orderDetail->shipped_qty) ? $orderDetail->shipped_qty : 0;

                    if ($orderDetail->shipped_qty + $inputQtyWillShip > $orderDetail->qty) {
                        throw new \Exception(Message::get("V011", Message::get("shipped_qty"), $orderDetail->qty));
                    }

                    // 1. Update shipped_qty for order details
                    $orderDetail->shipped_qty += $inputQtyWillShip;
                    $orderDetail->save();

                    // 2. Create history for each ship.
                    $orderHistoryDetail = new OrderHistoryDetailModel();
                    $orderHistoryDetail->create([
                        'order_detail_id' => $orderDetail->id,
                        'shipped_qty' => $inputQtyWillShip,
                    ]);
                }

                break;
            case ORDER_STATUS_COMPLETED:
                $this->validateSts($order, $status);
                $order->status = $status;
                $order->save();

                break;

                // Check is agency
                $customer = Customer::model()->where('id', $order->customer_id)->first();
                if (empty($customer) || $customer->is_agency != 1) {
                    break;
                }

                $promotionDetailModel = new PromotionDetailModel();
                $productPromotion = $promotionDetailModel->getPromotionForCustomer($order->customer_id);

                // Get Promotion.
                //$promotions = PromotionDetail::model()->get()->toArray();
                $unitConverts = UnitConvert::model()->get();
                $converts = [];
                foreach ($unitConverts as $unitConvert) {
                    $converts[$unitConvert->from_unit_id . "-" . $unitConvert->to_unit_id] = $unitConvert->rate;
                }

                $productPoint = [];
                $logs = [];
                $detailPoint = [];
                $qtyUsed = [];
                $orderDetailIds = [];
                foreach ($productPromotion as $item) {
                    $productPoint[$item->order_product_id] = $productPoint[$item->order_product_id] ?? 0;
                    $qtyUsed[$item->order_product_id] = $qtyUsed[$item->order_product_id] ?? 0;

                    if (empty($converts[$item->order_unit_id . "-" . $item->promo_unit_id])) {
                        continue;
                    }
                    $rate = $converts[$item->order_unit_id . "-" . $item->promo_unit_id];

                    $productPoint[$item->order_product_id] += ($item->order_qty * $rate);
                    $qtyUsed[$item->order_product_id] += $item->order_qty;

                    $number = floor($productPoint[$item->order_product_id] / $item->promo_qty);

                    $numberRemain = $productPoint[$item->order_product_id] - $number;

                    $dataLogs = [
                        'order_detail_id' => $item->order_detail_id,
                        'product_id' => $item->order_product_id,
                        'qty_used' => 0,
                        'qty_promo' => 0,
                        'point_converted' => 0,
                        'data' => $item->toArray(),
                    ];
                    $orderDetailIds[] = $item->order_detail_id;
                    if ($number >= $item->promo_qty) {
                        $unused = $numberRemain / $rate;
                        $qtyUsed[$item->order_product_id] -= $unused;
                        $dataLogs['qty_used'] = $qtyUsed[$item->order_product_id];
                        $dataLogs['qty_promo'] = $number;
                        $dataLogs['point_converted'] = $item->promo_point * $number;

                        // Increment Point
                        $customer = Customer::find($order->customer_id);
                        $customerType = CustomerType::model()->orderBy('point', 'desc')->get();
                        $customer->point += ($item->promo_point * $number);
                        foreach ($customerType as $cusType) {
                            if ($customer->point >= $cusType->point) {
                                $customer->type_id = $cusType->id;
                                break;
                            }
                        }
                        $customer->updated_at = date("Y-m-d H:i:s", time());
                        $customer->updated_by = SSC::getCurrentUserId();
                        $customer->save();

                        // Decrement Order Detail qty_unused
                        $detailPoint[$item->order_product_id][] = $dataLogs;
                        OrderDetail::whereIn('id', $orderDetailIds)->update(['qty_unused' => 0]);
                        if ($unused) {
                            OrderDetail::where('id', $item->order_detail_id)->update(['qty_unused' => $unused]);
                        }

                        // Write Log Point
                        $now = date("Y-m-d H:i:s", time());
                        $me = SSC::getCurrentUserId();
                        $logs[] = [
                            "product_id" => $item->order_product_id,
                            "promotion_id" => $item->promo_id,
                            "customer_id" => $order->customer_id,
                            "total_point" => ($item->promo_point * $number),
                            "qty" => $dataLogs['qty_used'],
                            "qty_promo" => $dataLogs['qty_promo'],
                            "data" => json_encode($detailPoint[$item->order_product_id]),
                            "created_at" => $now,
                            "created_by" => $me,
                            "updated_at" => $now,
                            "updated_by" => $me,
                        ];

                        // Reset
                        $productPoint[$item->order_product_id] = $unused * $rate;
                        $qtyUsed[$item->order_product_id] = 0;
                        $orderDetailIds = [];
                    }

                    $detailPoint[$item->order_product_id][] = $dataLogs;
                }

                if (!empty($logs)) {
                    UserProductHistory::insert($logs);
                }
        }

        $order->status = $status;
        $order->save();

        return $order->code;
    }

    /**
     * @param Order $order
     * @return bool
     * @throws \Exception
     */
    private function validateSts(Order $order, $status)
    {
        $statusAllow = $this->getNextStatuses($order->status);
        if (empty($statusAllow)) {
            throw new \Exception(Message::get("orders.change-status-block", $order->code));
        }

        $strAllow = implode(", ", $statusAllow);
        if (!in_array($status, $statusAllow)) {
            throw new \Exception(Message::get("orders.change-status-allow", $order->code, "($strAllow)"));
        }

        return true;
    }

    /**
     * @param string $orderStatus
     * @return array
     */
    private function getNextStatuses($orderStatus)
    {
        $nextStatus = [];

        switch ($orderStatus) {
            case null:
                $nextStatus = [ORDER_STATUS_PENDING, ORDER_STATUS_APPROVED];
                break;
            case ORDER_STATUS_PENDING:
                $nextStatus = [ORDER_STATUS_PENDING, ORDER_STATUS_APPROVED, ORDER_STATUS_REJECTED];
                break;
            case ORDER_STATUS_APPROVED:
                $nextStatus = [ORDER_STATUS_APPROVED, ORDER_STATUS_SHIPPED, ORDER_STATUS_COMPLETED];
                break;
            case ORDER_STATUS_SHIPPED:
                $nextStatus = [ORDER_STATUS_SHIPPED, ORDER_STATUS_COMPLETED];
                break;
            case ORDER_STATUS_COMPLETED:
                $nextStatus = [];
                break;
        }

        return $nextStatus;
    }

    /**
     * @param Order $order
     * @return array
     */
    private function getDeviceIds(Issue $issue)
    {
//        $customerId = $order->customer_id;
//        $sellerId = $order->seller_id;
//        $userId = $order->updated_by;
//
//        $termUser = [
//            $sellerId => $sellerId,
//            $userId   => $userId,
//        ];
//
//        if (!empty($customerId)) {
//            $customer = Customer::find($customerId);
//            if (!empty($customer)) {
//                $customerCode = $customer->code;
//                $user = User::model()->where('code', $customer->code)->where('user_type', 'CUSTOMER')->first();
//                if (!empty($user)) {
//                    $termUser[$user->id] = $user->id;
//                }
//            }
//        }
//
//        $userSession = UserSession::model()->whereIn('user_id', array_values($termUser))->where('deleted', '0')->get();
//        $deviceIds = array_pluck($userSession, 'device_id');
//
//        return array_filter($deviceIds);

        $userId = $issue->user_id;
        $createdBy = $issue->created_by;
        $termUser = [
            $createdBy => $createdBy,
            $userId => $userId,
        ];

        $userSession = UserSession::model()->whereIn('user_id', array_values($termUser))->where('deleted', '0')->get();
        $deviceIds = array_pluck($userSession, 'user_id');
        return array_filter($deviceIds);
    }
}
/*
set @orderId1 = (select group_concat(id) from (select id from orders where order_type=1 group by  customer_id limit 110 offset 0) as xxx);
set @orderId2 = (select group_concat(id) from (select id from orders where order_type=1 group by customer_id limit 110 offset 110) as yyy);
update orders set status = "PENDING" where order_type=1
update orders set status = "COMPLETED" where order_type=1
update orders set status="PENDING" where find_in_set(id, @orderId1);
update orders set status="PENDING" where find_in_set(id, @orderId1);
*/