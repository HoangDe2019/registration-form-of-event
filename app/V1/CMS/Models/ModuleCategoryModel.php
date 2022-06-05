<?php
/**
 * Created by PhpStorm.
 * User: DAT
 * Date: 4/8/2019
 * Time: 11:24 AM
 */

namespace App\V1\CMS\Models;

use App\Issue;
use App\ModuleHasUser;
use App\Supports\Message;
use App\ModuleCategory;
use App\OFFICE;
use App\User;

class ModuleCategoryModel extends AbstractModel
{
    public function __construct(ModuleCategory $model = null)
    {
        parent::__construct($model);
    }

    public function upsert($input)
    {
        $id = !empty($input['id']) ? $input['id'] : 0;
        if ($id) {
            $this->checkUnique(['code' => $input['code']], $id);
            $categoryTask = ModuleCategory::find($id);
            if (empty($categoryTask)) {
                throw new \Exception(Message::get("V003", "ID: #$id"));
            }
            $categoryTask->name = array_get($input, 'name', $categoryTask->name);
            $categoryTask->code = array_get($input, 'code', $categoryTask->code);
            $categoryTask->module_id = array_get($input, 'module_id', $categoryTask->module_id);
            $categoryTask->description = array_get($input, 'description', $categoryTask->description);
            $categoryTask->is_active = array_get($input, 'is_active', $categoryTask->is_active);
            $categoryTask->updated_at = date("Y-m-d H:i:s", time());
            $categoryTask->updated_by = OFFICE::getCurrentUserId();
            $categoryTask->save();
        } else {
            $param = [
                'name' => $input['name'],
                'code' => $input['code'],
                'module_id' => array_get($input, 'module_id', null),
                'description' => array_get($input, 'description', null),
                'is_active' => 1,
            ];
            $categoryTask = $this->create($param);
        }
        return $categoryTask;
    }

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
        $isSupper = OFFICE::isSuper();
        $moduleIds = ModuleHasUser::where('user_id', $user_id)->pluck('module_id')->toArray();
        if ($roleCurrent == USER_ROLE_ADMIN && $isSupper == true) {
            if (!empty($input['module_id'])) {
                $query = $query->whereHas('module', function ($q) use ($input) {
                    $q->where('id', $input['module_id']);
                });
            }
        } elseif ($roleCurrent == USER_ROLE_ADMIN && $isSupper == false) {
            $query = $query->whereHas('module', function ($q) use ($moduleIds, $user_id) {
                $q->whereIn('id', $moduleIds)
                    ->orWhere('created_by', $user_id);
            });
            $query = $query->orWhere('created_by', $user_id);
        } else {
            $query = $query->whereHas('module', function ($q) use ($moduleIds, $user_id) {
                $q->whereIn('id', $moduleIds);
            });
        }
        if (!empty($input['code'])) {
            $query = $query->where('code', 'like', "%{$input['code']}%");
        }
        if (!empty($input['name'])) {
            $query = $query->where('name', 'like', "%{$input['name']}%");
        }
        if (!empty($input['module_name'])) {
            $query = $query->whereHas('module', function ($q) use ($input) {
                $q->where('name', 'like', "%{$input['module_name']}%");
            });
        }
        if (!empty($input['id'])) {
            $query = $query->whereHas('module', function ($q) use ($input) {
                $q->where('id', 'like', "%{$input['id']}%");
            });
        }
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