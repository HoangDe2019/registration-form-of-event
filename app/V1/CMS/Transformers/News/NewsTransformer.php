<?php
/**
 * User: Dai Ho
 * Date: 22-Mar-17
 * Time: 23:43
 */

namespace App\V1\CMS\Transformers\News;

use App\Supports\OFFICE_Error;
use App\News;
use League\Fractal\TransformerAbstract;

/**
 * Class NewsTransformer
 *
 * @package App\V1\CMS\Transformers
 */
class NewsTransformer extends TransformerAbstract
{
    public function transform(News $news)
    {
        $category = array_pluck($news->newsCategory->toArray(), 'category.name');
        try {
            return [
                'id' => $news->id,
                'title' => $news->title,
                'short_description' => $news->short_description,
                'description' => $news->description,
                'thumbnail' => $news->thumbnail,
                'category' => rtrim(implode(", ", $category), ", "),
                'view_count' => $news->view_count,
                'is_active' => $news->is_active,
                'created_at' => date('Y/m/d H:i:s', strtotime($news->created_at)),
                'updated_at' => strtotime($news->updated_at) > 0 ? date('d/m/Y H:i', strtotime($news->updated_at)) : '',
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}
