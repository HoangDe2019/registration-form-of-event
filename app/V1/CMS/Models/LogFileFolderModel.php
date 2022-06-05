<?php


namespace App\V1\CMS\Models;


use App\LogFileFolder;
use App\OFFICE;

class LogFileFolderModel extends AbstractModel
{
    public function __construct(LogFileFolder $model = null)
    {
        parent::__construct($model);
    }

    public function searchHistory($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        $query = $query->where('created_by', OFFICE::getCurrentUserId())->orderBY('id', 'DESC');
        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                if (!empty($input['sort']['date'])) {
                    return $query->get();
                } else {
                    return $query->paginate($limit);
                }
            }
        }
    }

    public function searchAction($input = [], $with = [], $limit = null)
    {
        $query = $this->make($with);
        $this->sortBuilder($query, $input);
        $query = $query->where('created_by', OFFICE::getCurrentUserId())->orderBY('id', 'DESC')->take(10)->get();
        if ($limit) {
            if ($limit === 1) {
                return $query->first();
            } else {
                if (!empty($input['sort']['date'])) {
                    return $query->get();
                } else {
                    return $query->paginate($limit);
                }
            }
        }
    }
}