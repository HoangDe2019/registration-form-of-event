<?php
/**
 * User: Dai Ho
 * Date: 22-Mar-17
 * Time: 23:43
 */

namespace App\V1\CMS\Models;

use App\OFFICE;
use App\News;
use App\NewsCategory;
use App\Supports\OFFICE_Error;
use Illuminate\Support\Facades\DB;

/**
 * Class NewsModel
 *
 * @package App\V1\CMS\Models
 */
class NewsModel extends AbstractModel
{
    public function __construct(News $model = null)
    {
        parent::__construct($model);
    }

    /**
     * @param $input
     * @return mixed
     * @throws \Exception
     */
    public function upsert($input)
    {
        $param = [
            'title' => $input['title'],
            'alias' => SSC::strToSlug($input['title']),
            'short_description' => SSC::array_get($input, 'short_description'),
            'description' => SSC::array_get($input, 'description'),
            'thumbnail' => SSC::array_get($input, 'thumbnail'),
            'image' => SSC::array_get($input, 'image'),
            'content' => SSC::array_get($input, 'content'),
            'published_date' => SSC::array_get($input, 'thumbnail'),
            'is_approved' => SSC::array_get($input, 'image'),
            'is_active' => SSC::array_get($input, 'image'),
        ];

        DB::beginTransaction();
        $id = !empty($input['id']) ? $input['id'] : 0;
        $newsModel = new NewsModel();
        $newsModel->checkUnique([
            'alias' => strtoupper($param['alias']),
        ], $id);

        if ($id) {
            $allNewsCategory = NewsCategory::whereRaw("news_id = $id")->get();
            $allNewsCategory = array_pluck($allNewsCategory, 'id', 'category_id');

            $param['id'] = $input['id'];
            $news = $newsModel->update($param);
        } else {
            $allNewsCategory = [];

            // Create
            $news = $newsModel->create($param);
        }

        $newsId = $news->id;
        $newsCategoryDelete = $allNewsCategory;
        $categoryInput = array_get($input, 'category');
        if (!empty($categoryInput)) {
            foreach ($categoryInput as $catInputId) {
                if (!empty($allNewsCategory[$catInputId])) {
                    // Update News Category
                    $nc = NewsCategory::findOrFail($allNewsCategory[$catInputId]);
                    $nc->updated_at = date('Y-m-d H:i:s', time());
                    $nc->updated_by = SSC::getCurrentUserId();
                    $nc->save();
                    unset($newsCategoryDelete[$catInputId]);
                } else {
                    // Insert News Category
                    $nc = new NewsCategory();
                    $nc->news_id = $newsId;
                    $nc->category_id = $catInputId;
                    $nc->created_at = date('Y-m-d H:i:s', time());
                    $nc->created_by = SSC::getCurrentUserId();
                    $nc->save();
                }
            }
        }
        // Delete Time Category
        NewsCategory::destroy($newsCategoryDelete);

        DB::commit();

        return $news;
    }

    /**
     * @param $input
     *
     * @return array|string
     */
    public function createAtParam($input)
    {
        if (isset($input['from']) && $input['from'] !== "" && isset($input['to']) && $input['to'] !== "") {
            return ['between' => [$input['from'] . " 00:00:00", $input['to'] . " 23:59:59"]];
        }

        if (isset($input['from']) && $input['from'] !== "") {
            return ['>=' => $input['from'] . " 00:00:00"];
        }

        if (isset($input['to']) && $input['to'] !== "") {
            return ['<=' => $input['to'] . " 23:59:59"];
        }

        return "";
    }
}