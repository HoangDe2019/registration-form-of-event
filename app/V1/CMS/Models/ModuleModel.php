<?php
/**
 * Created by PhpStorm.
 * User: SangNguyen
 * Date: 4/22/2019
 * Time: 11:39 AM
 */

namespace App\V1\CMS\Models;


use App\Module;
use App\ModuleHasUser;
use App\OFFICE;
use App\Supports\Message;
use App\User;
use Illuminate\Support\Arr;

class ModuleModel extends AbstractModel
{
    public function __construct(Module $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($input['user_module_details']) {
            foreach ($input['user_module_details'] as $detail) {
                $user = User::find($detail);
                if (!$user) {
                    throw new \Exception(Message::get('V003', Message::get('user_id') . " [#$detail]"));
                }
            }
        }
        if ($id) {
            $this->checkUnique(['code' => $input['code']], $id);
            $result = Module::find($id);
            if (empty($result)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $result->name = array_get($input, 'name', $result->name);
            $result->code = array_get($input, 'code', $result->code);
            $result->description = array_get($input, 'description', $result->description);
            $result->user_module_details = array_get($input, 'user_module_details', $result->user_module_details);
            $result->is_active = array_get($input, 'is_active', $result->is_active);
            $result->updated_at = date("Y-m-d H:i:s", time());
            $result->updated_by = OFFICE::getCurrentUserId();
            $result->save();
        } else {
            $param = [
                'name' => $input['name'],
                'code' => $input['code'],
                'description' => array_get($input, 'description', null),
                'user_module_details' => array_get($input, 'user_module_details', null),
                'is_active' => 1,
            ];

            $result = $this->create($param);
        }
        $userIds = (array_pluck($input['user_module_details'], 'id'));
        if (!empty($userIds)) {
            $result->users()->sync($userIds);
        }
        return $result;
    }

//    public function search($input = [], $with = [], $limit = null)
//    {
//        $query = $this->make($with);
//        $this->sortBuilder($query, $input);
//        $issues              = new IssueModel();
//        $issuesTable         = $issues->getTable();
//        $moduleCategory      = new ModuleCategoryModel();
//        $moduleCategoryTable = $moduleCategory->getTable();
//        $module              = new ModuleModel();
//        $moduleTable         = $module->getTable();
//        $user_id             = OFFICE::getCurrentUserId();
//        $roleCurrent         = OFFICE::getCurrentRoleCode();
//        if ($roleCurrent == USER_ROLE_ADMIN) {
//            if (!empty($input['name'])) {
//                $query = $query->where($moduleTable . '.name', 'like', "%{$input['name']}%");
//            }
//        } else {
//            $query = $query
//                ->join($moduleCategoryTable, $moduleCategoryTable . '.module_id', '=', $moduleTable . '.id')
//                ->join($issuesTable, $issuesTable . '.module_category_id', '=', $moduleCategoryTable . '.id')
//                ->where($issuesTable . '.user_id', $user_id)
//                ->whereNull($moduleCategoryTable . '.deleted_at')
//                ->whereNull($moduleTable . '.deleted_at')
//                ->whereNull($issuesTable . '.deleted_at');
//
//            if (!empty($input['name'])) {
//                $query = $query->where($moduleTable . '.name', 'like', "%{$input['name']}%");
//            }
//            $query = $query->select($moduleTable . '.*')->distinct();
//        }
//        $query = $query->orderBy($moduleTable . '.id', 'DESC');
//        if ($limit) {
//            if ($limit === 1) {
//                return $query->first();
//            } else {
//                return $query->paginate($limit);
//            }
//        } else {
//            return $query->get();
//        }
//    }

    public function search($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        $issues = new IssueModel();
        $issuesTable = $issues->getTable();
        $moduleCategory = new ModuleCategoryModel();
        $moduleCategoryTable = $moduleCategory->getTable();
        $module = new ModuleModel();
        $moduleTable = $module->getTable();
        $user_id = OFFICE::getCurrentUserId();
        $roleCurrent = OFFICE::getCurrentRoleCode();
        $user_is_supper = OFFICE::isSuper();
        $moduleIds = ModuleHasUser::where('user_id', $user_id)->pluck('module_id')->toArray();
        if ($roleCurrent == USER_ROLE_ADMIN && $user_is_supper == true) {

        } elseif ($roleCurrent == USER_ROLE_ADMIN && $user_is_supper == false) {
            $query->where($moduleTable . '.created_by', $user_id)
                ->orWhereIn($moduleTable . '.id', $moduleIds);
        } else {
            $query->whereIn($moduleTable . '.id', $moduleIds);
        }
        if (!empty($input['name'])) {
            $query = $query->where($moduleTable . '.name', 'like', "%{$input['name']}%");
        }
        $query = $query->orderBy($moduleTable . '.id', 'DESC');
        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }

    public function getModule($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        $issues = new IssueModel();
        $issuesTable = $issues->getTable();
        $moduleCategory = new ModuleCategoryModel();
        $moduleCategoryTable = $moduleCategory->getTable();
        $module = new ModuleModel();
        $moduleTable = $module->getTable();
        $user_id = OFFICE::getCurrentUserId();
        $roleCurrent = OFFICE::getCurrentRoleCode();
        $user_is_supper = OFFICE::isSuper();
        $moduleIds = ModuleHasUser::where('user_id', $user_id)->pluck('module_id')->toArray();
        if ($roleCurrent == USER_ROLE_ADMIN && $user_is_supper == true) {

        } elseif ($roleCurrent == USER_ROLE_ADMIN && $user_is_supper == false) {
            $query->where($moduleTable . '.created_by', $user_id)
                ->orWhereIn($moduleTable . '.id', $moduleIds);
        } else {
            $query->whereIn($moduleTable . '.id', $moduleIds);
        }
        if (!empty($input['name'])) {
            $query = $query->where($moduleTable . '.name', 'like', "%{$input['name']}%");
        }
        $query = $query->orderBy($moduleTable . '.id', 'DESC');
        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                return $query->paginate($limit);
            }
        } else {
            return $query->get();
        }
    }
}