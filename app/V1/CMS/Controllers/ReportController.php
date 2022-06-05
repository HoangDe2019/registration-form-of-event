<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 5/7/2019
 * Time: 2:00 PM
 */

namespace App\V1\CMS\Controllers;


use App\Issue;
use App\ModuleCategory;
use App\OFFICE;
use App\Profile;
use App\Supports\OFFICE_Error;
use Carbon\Carbon;
use App\V1\CMS\Models\IssueModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends BaseController
{
    protected $model;

    /**
     * ReportController constructor.
     */
    public function __construct()
    {
        $this->model = new IssueModel();
    }

    public $dataCopy = [];

    // Report Module Add user
    public function reportModuleAll(Request $request)
    {
        $input = $request->all();
        $New = Issue::model()
            ->where('issues.status', '=', 'NEW');
        $InProgress = Issue::model()
            ->where('issues.status', '=', 'IN-PROGRESS');
        $Completed = Issue::model()
            ->where('issues.status', '=', 'COMPLETED');
        $Open = Issue::model()
            ->where('issues.status', '=', 'OPENED');
        $Solved = Issue::model()
            ->where('issues.status', '=', 'SOLVED');
        $TotalIssue = Issue::model();
        if (!empty($input['module_id'])) {
            $New = $New->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $InProgress = $InProgress->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $Completed = $Completed->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $Open = $Open->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $Solved = $Solved->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalIssue = $TotalIssue->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $New = $New->count();
        $InProgress = $InProgress->count();
        $Completed = $Completed->count();
        $Open = $Open->count();
        $Solved = $Solved->count();
        $TotalIssue = $TotalIssue->count();
        $Totalprogress = round(($Completed / $TotalIssue) * 100, 2) . '%';
        $data1 = array(
            'name' => 'Mới',
            'value' => $New,
            'status' => 'NEW',
        );
        $data2 = array(
            'name' => 'Đang tiến hành',
            'value' => $InProgress,
            'status' => 'IN-PROGRESS',
        );
        $data3 = array(
            'name' => 'Hoàn thành',
            'value' => $Completed,
            'status' => 'COMPLETED',
        );
        $data4 = array(
            'name' => 'Mở lại',
            'value' => $Open,
            'status' => 'OPENED',
        );
        $data5 = array(
            'name' => 'Đã giải quyết',
            'value' => $Solved,
            'status' => 'SOLVED',
        );
        $data6 = array(
            'name' => 'Tổng',
            'value' => $TotalIssue,
            'status' => '',
        );
        $data7 = array(
            'name' => 'Tổng Tiến Độ',
            'value' => $Totalprogress,
            'status' => 'percentage',
        );
        $data[] = $data1;
        $data[] = $data2;
        $data[] = $data3;
        $data[] = $data4;
        $data[] = $data5;
        $data[] = $data6;
        $data[] = $data7;
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    // Report Module id
    public function reportModule($id, Request $request)
    {
        $input = $request->all();
        if ($id == 0) {
            $listModuleCategory = ModuleCategory::model();
        } else {
            $listModuleCategory = ModuleCategory::model()->where('module_id', '=', $id);
        }
        if (!empty($input['limit'])) {
            $listModuleCategory = $listModuleCategory->limit($input['limit']);
        }
        $listModuleCategory = $listModuleCategory->get()->toArray();
        foreach ($listModuleCategory as $value) {
            $Issue = Issue::model()->where('module_category_id', '=', $value['id']);
            $New = Issue::model()->where('module_category_id', '=', $value['id'])
                ->where('issues.status', '=', 'NEW');
            $InProgress = Issue::model()->where('module_category_id', '=', $value['id'])
                ->where('issues.status', '=', 'IN-PROGRESS');
            $Completed = Issue::model()->where('module_category_id', '=', $value['id'])
                ->where('issues.status', '=', 'COMPLETED');
            $Open = Issue::model()->where('module_category_id', '=', $value['id'])
                ->where('issues.status', '=', 'OPENED');
            $Solved = Issue::model()->where('module_category_id', '=', $value['id'])
                ->where('issues.status', '=', 'SOLVED');
            $TotalIssue = Issue::model()
                ->where('issues.module_category_id', '=', $value['id']);
            if (!empty($input['module_id'])) {
                $Issue = $Issue->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $New = $New->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $InProgress = $InProgress->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $Completed = $Completed->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $Open = $Open->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $Solved = $Solved->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $TotalIssue = $TotalIssue->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
            }
            $Issue = $Issue->count();
            $New = $New->count();
            $InProgress = $InProgress->count();
            $Completed = $Completed->count();
            $Open = $Open->count();
            $Solved = $Solved->count();
            $TotalIssue = $TotalIssue->count();
            $data[] = [
                'module_CategoryName' => $value['name'],
                'New' => $New,
                'InProgress' => $InProgress,
                'Completed' => $Completed,
                'Open' => $Open,
                'Solved' => $Solved,
                'TotalIssue' => $TotalIssue,
            ];
        }
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    // Report Module user_id
    public function reportModuleUser($id, Request $request)
    {
        $input = $request->all();
        if (!empty($input['user_id'])) {
            $useer_id = $input['user_id'];

        } else {
            $useer_id = OFFICE::getCurrentUserId();
        }
        $listModuleCategory = ModuleCategory::model()->where('module_id', '=', $id)->get()->toArray();
        foreach ($listModuleCategory as $value) {
            $Issue = Issue::model()
                ->where('issues.module_category_id', '=', $value['id'])
                ->where('issues.user_id', '=', $useer_id);
            $New = Issue::model()
                ->where('issues.module_category_id', '=', $value['id'])
                ->where('issues.user_id', '=', $useer_id)
                ->where('issues.status', '=', 'NEW');
            $InProgress = Issue::model()
                ->where('issues.module_category_id', '=', $value['id'])
                ->where('issues.user_id', '=', $useer_id)
                ->where('issues.status', '=', 'IN-PROGRESS');
            $Completed = Issue::model()
                ->where('issues.module_category_id', '=', $value['id'])
                ->where('issues.user_id', '=', $useer_id)
                ->where('issues.status', '=', 'COMPLETED');
            $Open = Issue::model()
                ->where('issues.module_category_id', '=', $value['id'])
                ->where('issues.user_id', '=', $useer_id)
                ->where('issues.status', '=', 'OPENED');
            $Solved = Issue::model()
                ->where('issues.module_category_id', '=', $value['id'])
                ->where('issues.user_id', '=', $useer_id)
                ->where('issues.status', '=', 'SOLVED');
            $TotalIssue = Issue::model()
                ->where('issues.module_category_id', '=', $value['id'])
                ->where('issues.user_id', '=', $useer_id);
            if (!empty($input['module_id'])) {
                $Issue = $Issue->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $New = $New->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $InProgress = $InProgress->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $Completed = $Completed->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $Open = $Open->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $Solved = $Solved->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $TotalIssue = $TotalIssue->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
            }
            $Issue = $Issue->count();
            $New = $New->count();
            $InProgress = $InProgress->count();
            $Completed = $Completed->count();
            $Open = $Open->count();
            $Solved = $Solved->count();
            $TotalIssue = $TotalIssue->count();
            $data[] = [
                'module_CategoryName' => $value['name'],
                'New' => $New,
                'InProgress' => $InProgress,
                'Completed' => $Completed,
                'Open' => $Open,
                'Solved' => $Solved,
                'TotalIssue' => $TotalIssue,
            ];
        }
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }


    public function reportCompleted(Request $request)
    {
        $input = $request->all();
        if (!empty($input['user_id'])) {
            $userId = $input['user_id'];
        } else {
            $userId = OFFICE::getCurrentUserId();
        }
        $date = getdate();
        $t = $date['mon'];
        $year = $date['year'];
        $d = $date['mday'];
        $dYesterday = $date['mday'] - 1;
        $dates = date('Y-m-d', time());
        $result = $this->model->getWeekDays($dates);

        //  Total issue of day
        $TotalIssueInDay = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereDay('issues.updated_at', '=', $d)
            ->whereMonth('issues.updated_at', '=', $t)
            ->whereYear('issues.updated_at', '=', $year);

        //  Total issue of yesterday
        $TotalIssueInYesterday = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereDay('issues.updated_at', '=', $d)
            ->whereMonth('issues.updated_at', '=', $t)
            ->whereYear('issues.updated_at', '=', $year);

        //  Total issue  of week
        $TotalIssueWeek = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereDate('issues.updated_at', '>=', $result['1'])
            ->whereDate('issues.updated_at', '<=', $result['7']);

        //  Total hour  by issue of Month
        $TotalIssueMonth = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereMonth('issues.updated_at', '=', $t)
            ->whereYear('issues.updated_at', '=', $year);

        //  Total issue  of Year
        $TotalIssueYear = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereYear('issues.updated_at', '=', $year);
        if (!empty($input['module_id'])) {
            $TotalIssueInDay = $TotalIssueInDay->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalIssueInYesterday = $TotalIssueInYesterday->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalIssueWeek = $TotalIssueWeek->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalIssueMonth = $TotalIssueMonth->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalIssueYear = $TotalIssueYear->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $TotalIssueInDay = $TotalIssueInDay->count();
        $TotalIssueInYesterday = $TotalIssueInYesterday->count();
        $TotalIssueWeek = $TotalIssueWeek->count();
        $TotalIssueMonth = $TotalIssueMonth->count();
        $TotalIssueYear = $TotalIssueYear->count();
        //////-------------TOTAL-HOUSE-ISSUE--------------------//
        // Total hour  by issue of day
        $TotalHourByDay = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereDay('issues.updated_at', '=', $d)
            ->whereMonth('issues.updated_at', '=', $t)
            ->whereYear('issues.updated_at', '=', $year);
        //  Total hour  by issue of yesterday
        $TotalHourByYesterDay = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereDay('issues.updated_at', '=', $dYesterday)
            ->whereMonth('issues.updated_at', '=', $t)
            ->whereYear('issues.updated_at', '=', $year);
        // Total hour  by issue of the week
        $TotalHourByWeek = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereDate('issues.updated_at', '>=', $result['1'])
            ->whereDate('issues.updated_at', '<=', $result['7'])
            ->whereMonth('issues.updated_at', '=', $t)
            ->whereYear('issues.updated_at', '=', $year);
        // Total hour  by issue of Month
        $TotalHourByMonth = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereMonth('issues.updated_at', '=', $t)
            ->whereYear('issues.updated_at', '=', $year);
        // Total hour  by issue of Year
        $TotalHourByYear = Issue::model()
            ->where('issues.status', '=', 'COMPLETED')
            ->where('issues.user_id', '=', $userId)
            ->whereYear('issues.updated_at', '=', $year);
        if (!empty($input['module_id'])) {
            $TotalHourByDay = $TotalHourByDay->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalHourByYesterDay = $TotalHourByYesterDay->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalHourByWeek = $TotalHourByWeek->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalHourByMonth = $TotalHourByMonth->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
            $TotalHourByYear = $TotalHourByYear->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $TotalHourByDay = $TotalHourByDay->get()->toArray();
        $TotalHourByYesterDay = $TotalHourByYesterDay->get()->toArray();
        $TotalHourByWeek = $TotalHourByWeek->get()->toArray();
        $TotalHourByMonth = $TotalHourByMonth->get()->toArray();
        $TotalHourByYear = $TotalHourByYear->get()->toArray();
        $TotalHourByDay = $this->GetHoursByDay($TotalHourByDay);
        $TotalHourByYesterDay = $this->GetHoursByDay($TotalHourByYesterDay);
        $TotalHourByWeek = $this->GetHoursByDay($TotalHourByWeek);
        $TotalHourByMonth = $this->GetHoursByDay($TotalHourByMonth);
        $TotalHourByYear = $this->GetHoursByDay($TotalHourByYear);
        $new_data1 = array(
            'name' => 'HÔM NAY',
            'total_issue' => $TotalIssueInDay,
            'total_time' => $TotalHourByDay,
        );
        $new_data2 = array(
            'name' => 'HÔM QUA',
            'total_issue' => $TotalIssueInYesterday,
            'total_time' => $TotalHourByYesterDay,
        );
        $new_data3 = array(
            'name' => 'TRONG TUẦN',
            'total_issue' => $TotalIssueWeek,
            'total_time' => $TotalHourByWeek,
        );
        $new_data4 = array(
            'name' => 'THÁNG NÀY',
            'total_issue' => $TotalIssueMonth,
            'total_time' => $TotalHourByMonth,
        );
        $new_data5 = array(
            'name' => 'NĂM NAY',
            'total_issue' => $TotalIssueYear,
            'total_time' => $TotalHourByYear,
        );
        $data[] = $new_data1;
        $data[] = $new_data2;
        $data[] = $new_data3;
        $data[] = $new_data4;
        $data[] = $new_data5;
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function reportModuleUserDaily(Request $request)
    {
        $input = $request->all();
        $date = getdate();
        $t = $date['mon'];
        $year = $date['year'];
        $d = $date['mday'];
        $dates = date('Y-m-d', time());
        $listIssue = Issue::model()->get()->toArray();
        $idBool = [];
        foreach ($listIssue as $value) {
            if (in_array($value['user_id'], $idBool)) {
                continue;
            }
            $nameUser = $this->GetUserName($value['user_id']);
            $IssueToday = Issue::model()
                ->where('user_id', '=', $value['user_id'])
                ->whereDay('created_at', '=', $d)
                ->whereMonth('created_at', '=', $t)
                ->whereYear('created_at', '=', $year);
            $TotalHourByDay = Issue::model()
                ->where('issues.user_id', '=', $value['user_id'])
                ->whereDay('issues.created_at', '=', $d)
                ->whereMonth('issues.created_at', '=', $t)
                ->whereYear('issues.created_at', '=', $year);
            if (!empty($input['module_id'])) {
                $IssueToday = $IssueToday->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $IssueToday = $IssueToday->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
            }
            $IssueToday = $IssueToday->count();
            $TotalHourByDay = $TotalHourByDay->get()->toArray();

            $TotalHourByDay = $this->GetHoursByDay($TotalHourByDay);
            {
                $data[] = [
                    'name' => $nameUser,
                    'issueToday' => $IssueToday,
                    'workTime' => $TotalHourByDay,
                ];
            }
            array_push($idBool, $value['user_id']);
        }
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function reportModuleUserDailyExport(Request $request)
    {
        $input = $request->all();
        $date = getdate();
        $t = $date['mon'];
        $year = $date['year'];
        $d = $date['mday'];
        $dates = date('Y-m-d', time());
        $listIssue = Issue::model()->get()->toArray();
        $idBool = [];
        foreach ($listIssue as $value) {
            if (in_array($value['user_id'], $idBool)) {
                continue;
            }
            $nameUser = $this->GetUserName($value['user_id']);
            $IssueToday = Issue::model()
                ->where('issues.user_id', '=', $value['user_id'])
                ->whereDay('issues.created_at', '=', $d)
                ->whereMonth('issues.created_at', '=', $t)
                ->whereYear('issues.created_at', '=', $year);
            $TotalHourByDay = Issue::model()
                ->where('issues.user_id', '=', $value['user_id'])
                ->whereDay('issues.created_at', '=', $d)
                ->whereMonth('issues.created_at', '=', $t)
                ->whereYear('issues.created_at', '=', $year);
            if (!empty($input['module_id'])) {
                $IssueToday = $IssueToday->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
                $TotalHourByDay = $TotalHourByDay->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id']);
            }
            $IssueToday = $IssueToday->count();
            $TotalHourByDay = $TotalHourByDay->get()->toArray();
            $TotalHourByDay = $this->GetHoursByDay($TotalHourByDay);
            {
                $data[] = [
                    'name' => $nameUser,
                    'issueToday' => $IssueToday,
                    'workTime' => $TotalHourByDay,
                ];
            }
            array_push($idBool, $value['user_id']);
        }
        try {
            $date = date('YmdHis', time());
            $dataReportUser = [
                [
                    'USER NAME',
                    'VẤN ĐỀ HÔM NAY',
                    'THỜI GIAN LÀM VIỆC(H)',
                ]
            ];
            foreach ($data as $value) {
                $dataReportUser[] = [
                    'name' => $value['name'],
                    'issueToday' => $value['issueToday'],
                    'workTime' => $value['workTime'],
                ];
            }
            $this->ExcelExport("ModuleUserDaily_$date", storage_path('Export') . "User/", $dataReportUser);
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = OFFICE_Error::handle($ex);
            return $this->response->errorBadRequest($response["message"]);
        }
    }

    public function GetUserName($id)
    {
        $profile = Profile::where(['user_id' => $id])->first();
        return $profile->full_name;
    }

    public function GetHoursByDay($time)
    {
        $Total = 0;
        foreach ($time as $value) {
            $Total += abs(($value['estimated_time']));
        }
        return $Total;
    }

    public function Round($TotalHour)
    {
        return (round($TotalHour, 0, PHP_ROUND_HALF_UP));
    }

    public function statisticTop5User(Request $request)
    {
        $input = $request->all();
        $data = DB::table('users')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('issues', 'issues.user_id', '=', 'users.id')
            ->select(DB::raw('profiles.full_name as user_name, count(issues.id) as total_issue'))
            ->groupBy('issues.user_id')
            ->orderBy('total_issue', 'DESC');
        if (!empty($input['module_id'])) {
            $data = $data->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $data = $data->limit(5)->get();
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function statisticIssueInComplete(Request $request)
    {
        $input = $request->all();
        $data = DB::table('users')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('issues', 'issues.user_id', '=', 'users.id')
            ->select(DB::raw('issues.id as id,issues.name as issue_name, profiles.full_name as user_name, issues.created_at as created_at'))
            ->where('issues.status', '!=', 'COMPLETED')
            ->whereNull('issues.deleted_at')
            ->orderBy('issues.id', 'DESC');
        if (!empty($input['module_id'])) {
            $data = $data->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $data = $data->limit(5)->get();
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function statisticIssueComplete(Request $request)
    {
        $input = $request->all();
        $data = DB::table('users')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('issues', 'issues.user_id', '=', 'users.id')
            ->select(DB::raw('issues.id as id,issues.name as issue_name, profiles.full_name as user_name, issues.created_at as created_at'))
            ->where('issues.status', 'COMPLETED')
            ->whereNull('issues.deleted_at')
            ->orderBy('issues.id', 'DESC');
        if (!empty($input['module_id'])) {
            $data = $data->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $data = $data->limit(5)->get();
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function statisticFile()
    {
        $data = DB::table('files')
            ->join('log_filefolder', 'log_filefolder.file_id', '=', 'files.id')
            ->whereNull('log_filefolder.deleted_at')
            ->select(DB::raw('files.id as id,files.file_name as file_name, log_filefolder.created_at as view_at'))
            ->orderBy('log_filefolder.created_at', 'DESC')
            ->limit(10)
            ->get();
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function statisticFolder()
    {
        $data = DB::table('folders')
            ->join('log_filefolder', 'log_filefolder.folder_id', '=', 'folders.id')
            ->whereNull('log_filefolder.deleted_at')
            ->select(DB::raw('folders.id as id,folders.folder_name as folder_name, log_filefolder.created_at as view_at'))
            ->orderBy('log_filefolder.created_at', 'DESC')
            ->limit(10)
            ->get();
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function statisticIssueDelay(Request $request)
    {
        $input = $request->all();
        function getDateTime($deadline, $timeNow)
        {
            $diff = abs(strtotime($timeNow) - strtotime($deadline));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
            $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
            $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
            if ($years == 0) {
                if ($months == 0) {
                    if ($days == 0) {
                        if ($hours == 0) {
                            if ($minutes == 0) {
                                return $seconds . " giây";
                            }
                            return $minutes . " phút " . $seconds . " giây";
                        }
                        return $hours . " giờ " . $minutes . " phút " . $seconds . " giây";
                    }
                    return $days . " ngày " . $hours . " giờ " . $minutes . " phút " . $seconds . " giây";
                }
                return $months . " tuần " . $days . " ngày " . $hours . " giờ " . $minutes . " phút " . $seconds . " giây";
            } else {
                return $years . " năm " . $months . " tuần " . $days . " ngày " . $hours . " giờ " . $minutes . " phút " . $seconds . " giây";
            }
        }

        $timeNow = Carbon::now();
        $data = DB::table('users')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('issues', 'issues.user_id', '=', 'users.id')
            ->select(DB::raw('issues.id as id,issues.name as issue_name, profiles.full_name as full_name, issues.deadline as deadline'))
            ->where('issues.status', '!=', 'COMPLETED')
            ->whereDate('issues.deadline', '<', $timeNow)
            ->whereNull('issues.deleted_at')
            ->limit('5');
        if (!empty($input['module_id'])) {
            $data = $data->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $data = $data->get()->toArray();
        $result = [];
        foreach ($data as $datum) {
            $result[] = [
                'id' => $datum->id,
                'issue_name' => $datum->issue_name,
                'full_name' => $datum->full_name,
                'deadline' => $datum->deadline,
                'timeDelay' => getDateTime($datum->deadline, $timeNow)
            ];
        }
        if (empty($result)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $result]);
    }

    public function issueByDay(Request $request)
    {
        $input = $request->all();
        $query = Issue::model()
            ->select(DB::raw("DATE_FORMAT(issues.created_at, '%d-%m-%Y') date"),
                (DB::raw(' count(DISTINCT issues.id) as total_issue')))
            ->groupBy('date')
            ->orderBy('issues.id', 'ASC');

        if (!empty($input['from']) && !empty($input['to'])) {
            $query = $query->where('issues.created_at', '>=', [date("Y-m-d", strtotime($input['from']))])
                ->where('issues.created_at', '<=', [date("Y-m-d", strtotime($input['to']))]);
        }
        if (!empty($input['module_id'])) {
            $query = $query->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $data = $query->get();
        $data = array_pluck($data, null, 'date');
        if (!empty($input['from']) && !empty($input['to'])) {
            $day = $this->model->getDatesBetween($input['from'], $input['to']);
            $dataJonTo = [];
            foreach ($day as $value) {
                $detailData = array_get($data, $value);
                if (empty($detailData)) {
                    $dataJonTo[$value]['date'] = $value;
                    $dataJonTo[$value]['total_issue'] = 0;
                    continue;
                }
                $dataJonTo[] = $detailData;
            }
            $dataJonTo = array_values($dataJonTo);

            return response()->json(['data' => $dataJonTo]);

        } else {
            $data = $query->take(6)->get();
            $datas = array_pluck($data, null, 'date');
            $dataJon = [];
            $getWeekDays = $this->model->getWeekDays($data[0]->date, $output_format = 'd-m-Y');

            foreach ($getWeekDays as $dayValue) {
                $detailData = array_get($datas, $dayValue);
                if (empty($detailData)) {
                    $dataJon[$dayValue]['date'] = $dayValue;
                    $dataJon[$dayValue]['total_issue'] = 0;
                    continue;
                }
                $dataJon[] = $detailData;
            }
            $dataJon = array_values($dataJon);
        }
        return response()->json(['data' => $dataJon]);
    }

    public function issueByMonth(Request $request)
    {
        $input = $request->all();
        $query = Issue::model()
            ->select(DB::raw("DATE_FORMAT(issues.created_at,'%m-%Y') date"),
                (DB::raw(' count(DISTINCT issues.id) as total_issue')))
            ->groupBy('date')
            ->take(6);
        if (!empty($input['from']) && !empty($input['to'])) {
            $query = $query->where('issues.created_at', '>=', [date("Y-m-d", strtotime($input['from']))])
                ->where('issues.created_at', '<=', [date("Y-m-d", strtotime($input['to']))]);
        }
        if (!empty($input['module_id'])) {
            $query = $query->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $data = $query->get();
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function issueByQuarterly(Request $request)
    {
        $input = $request->all();
        $query = Issue::model()
            ->select((DB::raw('QUARTER(issues.created_at) AS quarter,count(DISTINCT issues.id) as total_issue')))
            ->groupBy('quarter')
            ->orderBy('quarter', 'DESC');
        if (!empty($input['from']) && !empty($input['to'])) {
            $query = $query->where('issues.created_at', '>=', [date("Y-m-d", strtotime($input['from']))])
                ->where('issues.created_at', '<=', [date("Y-m-d", strtotime($input['to']))]);
        }
        if (!empty($input['module_id'])) {
            $query = $query->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        $data = $query->get();
        if (empty($data)) {
            return ['data' => ''];
        }
        return response()->json(['data' => $data]);
    }

    public function statisticIssueDelayInDay(Request $request)
    {
        $input = $request->all();
        $timeNow = Carbon::now();
        if (!empty($input['from']) && !empty($input['to']) && !empty($input['module_id'])) {
            $days = $this->model->getDatesBetween($input['from'], $input['to']);
            foreach ($days as $day) {
                $dayConvert = date('Y-m-d', strtotime($day));
                $query = Issue::model()
                    ->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id'])
                    ->where('issues.status', '!=', 'COMPLETED')
                    ->whereDate('issues.created_at', $dayConvert)
                    ->whereDate('issues.deadline', '<=', $timeNow->toDateString())
                    ->select((DB::raw(' count(DISTINCT issues.id) as total_issue')))
                    ->get();
                $result[] = [
                    'date' => $dayConvert,
                    'data' => $query,
                ];
                unset($query);
            }
        } else if (empty($input['from']) && empty($input['to']) && !empty($input['module_id'])) {
            $toDate_1 = $timeNow->toDateString();
            $from_Date_1 = $timeNow->subDay(7)->toDateString();
            $days = $this->model->getDatesBetween($from_Date_1, $toDate_1);
            foreach ($days as $day) {
                $dayConvert = date('Y-m-d', strtotime($day));
                $query = Issue::model()
                    ->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id'])
                    ->where('issues.status', '!=', 'COMPLETED')
                    ->whereDate('issues.created_at', $dayConvert)
                    ->whereDate('issues.deadline', '<=', $timeNow->toDateString())
                    ->select((DB::raw(' count(DISTINCT issues.id) as total_issue')))
                    ->get();
                $result[] = [
                    'date' => $dayConvert,
                    'data' => $query,
                ];
                unset($query);
            }
        } else {
            $toDate_2 = $timeNow->toDateString();
            $fromDate_2 = $timeNow->subDay(7)->toDateString();
            $days = $this->model->getDatesBetween($fromDate_2, $toDate_2);
            foreach ($days as $day) {
                $dayConvert = date('Y-m-d', strtotime($day));
                $query = Issue::model()
                    ->where('issues.status', '!=', 'COMPLETED')
                    ->whereDate('issues.created_at', $dayConvert)
                    ->whereDate('issues.deadline', '<=', $timeNow->toDateString())
                    ->select((DB::raw(' count(DISTINCT issues.id) as total_issue')))
                    ->get();
                $result[] = [
                    'date' => $dayConvert,
                    'data' => $query,
                ];
                unset($query);
            }
        }
        if (empty($result)) {
            return ['data' => []];
        }
        return response()->json(['data' => $result]);
    }

    public function statisticIssueUserInDay(Request $request)
    {
        $input = $request->all();
        $query = DB::table('issues')
            ->select(DB::raw("DATE_FORMAT(issues.created_at, '%d-%m-%Y') date, 
             issues.user_id as user , profiles.full_name as name"),
                (DB::raw(' count(DISTINCT issues.id) as total_issue')))
            ->join('users', 'users.id', '=', 'issues.user_id')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->groupBy('user', 'date')
            ->groupBy('user')
            ->orderBy('user', 'ASC')
            ->orderBy('issues.id', 'ASC');
        if (!empty($input['module_id'])) {
            $query = $query->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                ->join('modules', 'modules.id', '=', 'module_category.module_id')
                ->where('modules.id', $input['module_id']);
        }
        if (!empty($input['from']) && !empty($input['to'])) {
            $query = $query->where('issues.created_at', '>=', [date("Y-m-d", strtotime($input['from']))])
                ->where('issues.created_at', '<=', [date("Y-m-d", strtotime($input['to']))]);
            $query = $query->get();
            $day = $this->model->getDatesBetween($input['from'], $input['to']);
            $dataJonTo = [];
            $userAll = DB::table('users')->get();
            foreach ($userAll as $user) {
                $bool = false;
                $dataJonToChild = [];
                foreach ($query as $key => $value) {
                    if ($user->id == $value->user) {
                        $bool = true;
                        foreach ($day as $dayValue) {
                            if ($value->date == $dayValue) {
                                $dataJonToChild [] = [
                                    'total_issue' => $value->total_issue,
                                    'user_id' => $value->user,
                                    'date' => $dayValue,
                                ];
                            }
                            continue;
                        }
                    }
                }
                if ($bool) {
                    $dataJonTo[] = [
                        'user' => $user->code,
                        'detail' => $dataJonToChild
                    ];
                }

            }
            $dataJon = array_values($dataJonTo);
            return response()->json(['data' => $dataJon]);
        } else {
            $query_none_from = $query->get()->toArray();
            $query_none_from = array_reverse($query_none_from);
            $getWeekDays = $this->model->getWeekDays($query_none_from[0]->date, $output_format = 'd-m-Y');
            $dataJonNoneForm = [];
            $userAll = DB::table('users')->get();
            foreach ($userAll as $user) {
                $bool = false;
                $dataJonChild = [];
                foreach ($query_none_from as $key => $value) {
                    if ($user->id == $value->user) {
                        $bool = true;
                        foreach ($getWeekDays as $dayValue) {
                            if ($value->date == $dayValue) {
                                $dataJonChild [] = [
                                    'total_issue' => $value->total_issue,
                                    'user_id' => $value->user,
                                    'date' => $dayValue,
                                ];
                            }
                            continue;
                        }
                    }
                }
                if ($bool) {
                    $dataJonNoneForm[] = [
                        'user' => $user->code,
                        'detail' => $dataJonChild
                    ];
                }
            }
        }
        $dataJonNoneForm = array_values($dataJonNoneForm);
        return response()->json(['data' => $dataJonNoneForm]);
    }

    public function statisticIssueInMonth(Request $request)
    {
        $input = $request->all();
        if (!empty($input['from']) && !empty($input['to']) && !empty($input['module_id'])) {
            $monthFrom = date("m", strtotime($input['from']));
            $monthTo = date("m", strtotime($input['to']));
            for ($i = (int)$monthFrom; $i <= $monthTo; $i++) {
                $TotalIssueInMonth = Issue::model()
                    ->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                    ->join('modules', 'modules.id', '=', 'module_category.module_id')
                    ->where('modules.id', $input['module_id'])
                    ->whereMonth('issues.created_at', '=', $i);
                $result[] = [
                    'month' => $i,
                    'total_issue' => $TotalIssueInMonth->count()
                ];
            }
        } else if (!empty($input['from']) && !empty($input['to']) && empty($input['module_id'])) {
            $monthFrom = date("m", strtotime($input['from']));
            $monthTo = date("m", strtotime($input['to']));
            for ($i = (int)$monthFrom; $i <= $monthTo; $i++) {
                $TotalIssueInMonth = Issue::model()
                    ->whereMonth('issues.created_at', '=', $i);
                $result[] = [
                    'month' => $i,
                    'total_issue' => $TotalIssueInMonth->count()
                ];
            }
        } else {
            $monthFrom = 1;
            $monthTo = 12;
            for ($i = (int)$monthFrom; $i <= $monthTo; $i++) {
                $TotalIssueInMonth = Issue::model()
                    ->whereMonth('issues.created_at', '=', $i);
                $result[] = [
                    'month' => $i,
                    'total_issue' => $TotalIssueInMonth->count()
                ];
            }
        }
        if (empty($result)) {
            return ['data' => []];
        }
        return response()->json(['data' => $result]);
    }

    public function statisticIssueInTrimester(Request $request)
    {
        $input = $request->all();
        $Trimester_1 = 0;
        $Trimester_2 = 0;
        $Trimester_3 = 0;
        $Trimester_4 = 0;
        $yearNow = Carbon::now()->year;
        if (!empty($input['module_id'])) {
            for ($i = 1; $i <= 12; $i++) {
                if ($i <= 3) {
                    $TotalIssueInMonth = Issue::model()
                        ->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                        ->join('modules', 'modules.id', '=', 'module_category.module_id')
                        ->where('modules.id', $input['module_id'])
                        ->whereMonth('issues.created_at', '=', $i)
                        ->whereYear('issues.created_at', '=', $yearNow);
                    $Trimester_1 = $Trimester_1 + $TotalIssueInMonth->count();
                } else if ($i > 3 && $i <= 6) {
                    $TotalIssueInMonth = Issue::model()
                        ->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                        ->join('modules', 'modules.id', '=', 'module_category.module_id')
                        ->where('modules.id', $input['module_id'])
                        ->whereMonth('issues.created_at', '=', $i)
                        ->whereYear('issues.created_at', '=', $yearNow);
                    $Trimester_2 = $Trimester_2 + $TotalIssueInMonth->count();
                } else if ($i > 6 && $i <= 9) {
                    $TotalIssueInMonth = Issue::model()
                        ->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                        ->join('modules', 'modules.id', '=', 'module_category.module_id')
                        ->where('modules.id', $input['module_id'])
                        ->whereMonth('issues.created_at', '=', $i)
                        ->whereYear('issues.created_at', '=', $yearNow);
                    $Trimester_3 = $Trimester_3 + $TotalIssueInMonth->count();
                } else {
                    $TotalIssueInMonth = Issue::model()
                        ->join('module_category', 'module_category.id', '=', 'issues.module_category_id')
                        ->join('modules', 'modules.id', '=', 'module_category.module_id')
                        ->where('modules.id', $input['module_id'])
                        ->whereMonth('issues.created_at', '=', $i)
                        ->whereYear('issues.created_at', '=', $yearNow);
                    $Trimester_4 = $Trimester_4 + $TotalIssueInMonth->count();
                }
            }
        } else {
            for ($i = 1; $i <= 12; $i++) {
                if ($i <= 3) {
                    $TotalIssueInMonth = Issue::model()
                        ->whereMonth('issues.created_at', '=', $i)
                        ->whereYear('issues.created_at', '=', $yearNow);
                    $Trimester_1 = $Trimester_1 + $TotalIssueInMonth->count();
                } else if ($i > 3 && $i <= 6) {
                    $TotalIssueInMonth = Issue::model()
                        ->whereMonth('issues.created_at', '=', $i)
                        ->whereYear('issues.created_at', '=', $yearNow);
                    $Trimester_2 = $Trimester_2 + $TotalIssueInMonth->count();
                } else if ($i > 6 && $i <= 9) {
                    $TotalIssueInMonth = Issue::model()
                        ->whereMonth('issues.created_at', '=', $i)
                        ->whereYear('issues.created_at', '=', $yearNow);
                    $Trimester_3 = $Trimester_3 + $TotalIssueInMonth->count();
                } else {
                    $TotalIssueInMonth = Issue::model()
                        ->whereMonth('issues.created_at', '=', $i)
                        ->whereYear('issues.created_at', '=', $yearNow);
                    $Trimester_4 = $Trimester_4 + $TotalIssueInMonth->count();
                }
            }
        }
        $data_1 = array(
            'name' => 'Quý 1',
            'total_issue' => $Trimester_1,
        );
        $data_2 = array(
            'name' => 'Quý 2',
            'total_issue' => $Trimester_2,
        );
        $data_3 = array(
            'name' => 'Quý 3',
            'total_issue' => $Trimester_3,
        );
        $data_4 = array(
            'name' => 'Quý 4',
            'total_issue' => $Trimester_4,
        );
        $data[] = $data_1;
        $data[] = $data_2;
        $data[] = $data_3;
        $data[] = $data_4;
        if (empty($data)) {
            return ['data' => []];
        }
        return response()->json(['data' => $data]);
    }
}