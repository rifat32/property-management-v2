<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Http\Utils\ErrorUtil;
use App\Models\Job;
use Exception;
use Illuminate\Http\Request;

class ClientJobController extends Controller
{
    use ErrorUtil;
   /**
    *
     * @OA\Get(
     *      path="/v1.0/client/jobs/{perPage}",
     *      operationId="getJobsClient",
     *      tags={"client.job"},
    *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="perPage",
     *         in="path",
     *         description="perPage",
     *         required=true,
     *  example="6"
     *      ),
     *      summary="This method is to get  jobs ",
     *      description="This method is to get jobs",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

    public function getJobsClient($perPage,Request $request) {
        try{

            $jobsQuery = Job::with("job_sub_services.sub_service")
            ->where([
                "customer_id" => $request->user()->id
            ]);

            if(!empty($request->search_key)) {
                $jobsQuery = $jobsQuery->where(function($query) use ($request){
                    $term = $request->search_key;
                    $query->where("car_registration_no", "like", "%" . $term . "%");
                });

            }

            if(!empty($request->start_date) && !empty($request->end_date)) {
                $jobsQuery = $jobsQuery->whereBetween('created_at', [
                    $request->start_date,
                    $request->end_date
                ]);

            }
            $jobs = $jobsQuery->orderByDesc("id")->paginate($perPage);
            return response()->json($jobs, 200);
        } catch(Exception $e){

        return $this->sendError($e,500);
        }
    }




     /**
        *
     * @OA\Get(
     *      path="/v1.0/client/jobs/single/{id}",
     *      operationId="getJobByIdClient",
     *      tags={"client.job"},
    *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id",
     *         required=true,
     *  example="6"
     *      ),
     *      summary="This method is to get  job by id ",
     *      description="This method is to get job by id",
     *

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     * @OA\JsonContent(),
     *      ),
     *        @OA\Response(
     *          response=422,
     *          description="Unprocesseble Content",
     *    @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *   @OA\JsonContent()
     * ),
     *  * @OA\Response(
     *      response=400,
     *      description="Bad Request",
     *   *@OA\JsonContent()
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found",
     *   *@OA\JsonContent()
     *   )
     *      )
     *     )
     */

    public function getJobByIdClient($id,Request $request) {
        try{

            $job = Job::with("job_sub_services.sub_service")
            ->where([
                "id" => $id,
                "customer_id" => $request->user()->id
            ])
            ->first();

            if(!$job){
                return response()->json([
            "message" => "job not found"
                ], 404);
            }


            return response()->json($job, 200);
        } catch(Exception $e){

        return $this->sendError($e,500);
        }
    }

}