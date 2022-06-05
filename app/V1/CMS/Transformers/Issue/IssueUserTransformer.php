<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 7/4/2019
 * Time: 3:34 PM
 */

namespace App\V1\CMS\Transformers\Issue;


use App\Issue;
use App\Supports\OFFICE_Error;
use League\Fractal\TransformerAbstract;

class IssueUserTransformer extends TransformerAbstract
{
    /**
     * @param Issue $issue
     * @return array
     * @throws \Exception
     */
    public function transform(Issue $issue)
    {
        try {
            return [
                'id' => $issue->id,
                'name' => $issue->name,
                'created_at' => date('d/m/Y H:i', strtotime($issue->created_at)),
                'deadline' => !empty($issue->deadline) ? date('d-m-Y H:i',
                    strtotime($issue->deadline)) : null,
                'estimated_time' => $issue->estimated_time,
            ];
        } catch (\Exception $ex) {
            $response = OFFICE_Error::handle($ex);
            throw new \Exception($response['message'], $response['code']);
        }
    }
}