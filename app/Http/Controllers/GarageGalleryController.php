<?php

namespace App\Http\Controllers;

use App\Http\Requests\GarageGalleryCreateRequest;
use App\Http\Utils\ErrorUtil;
use App\Http\Utils\GarageUtil;
use App\Models\GarageGallery;
use Exception;
use Illuminate\Http\Request;

class GarageGalleryController extends Controller
{
use ErrorUtil,GarageUtil;
      /**
        *
     * @OA\Post(
     *      path="/v1.0/garage-galleries/{garage_id}",
     *      operationId="createGarageGallery",
     *      tags={"garage_gallery_management"},
     *       security={
     *           {"bearerAuth": {}}
     *       },
     *         @OA\Parameter(
     *         name="garage_id",
     *         in="path",
     *         description="garage_id",
     *         required=true,
     *  example="1"
     *      ),
     *      summary="This method is to store garage gallery",
     *      description="This method is to store garage gallery",
     *
   *  @OA\RequestBody(
        *   * @OA\MediaType(
*     mediaType="multipart/form-data",
*     @OA\Schema(
*         required={"images[]"},
*         @OA\Property(
*             description="array of images to upload",
*             property="images[]",
*             type="array",
*             @OA\Items(
*                 type="file"
*             ),
*             collectionFormat="multi",
*         )
*     )
* )



     *      ),
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

    public function createGarageGallery($garage_id,GarageGalleryCreateRequest $request)
    {
        try{
            if(!$request->user()->hasPermissionTo('garage_gallery_create')){
                 return response()->json([
                    "message" => "You can not perform this action"
                 ],401);
            }

            if (!$this->garageOwnerCheck($garage_id)) {
                return response()->json([
                    "message" => "you are not the owner of the garage or the requested garage does not exist."
                ], 401);
            }
            $insertableData = $request->validated();

            $location =  config("setup-config.garage_gallery_location");

            foreach($insertableData["images"] as $image){
                $new_file_name = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path($location), $new_file_name);


                GarageGallery::create([
                    "image" => $new_file_name,
                    "garage_id" => $garage_id
                ]);

            }

            return response()->json(["ok" => true], 201);


        } catch(Exception $e){
            error_log($e->getMessage());
        return $this->sendError($e,500);
        }
    }
 /**
        *
     * @OA\Get(
     *      path="/v1.0/garage-galleries/{garage_id}",
     *      operationId="getGarageGalleries",
     *      tags={"garage_gallery_management"},
    *       security={
     *           {"bearerAuth": {}}
     *       },

     *              @OA\Parameter(
     *         name="garage_id",
     *         in="path",
     *         description="garage_id",
     *         required=true,
     *  example="6"
     *      ),
     *      summary="This method is to get garage galleries",
     *      description="This method is to get garage galleries",
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

    public function getGarageGalleries($garage_id,Request $request) {
        try{
            if(!$request->user()->hasPermissionTo('garage_gallery_view')){
                return response()->json([
                   "message" => "You can not perform this action"
                ],401);
           }

           if (!$this->garageOwnerCheck($garage_id)) {
            return response()->json([
                "message" => "you are not the owner of the garage or the requested garage does not exist."
            ], 401);
        }

            $data["garage_galleries"] = GarageGallery::where([
               "garage_id" => $garage_id
            ])->orderByDesc("id")->get();

            $data["image_location_folder"] =  config("setup-config.garage_gallery_location");
            return response()->json($data, 200);
        } catch(Exception $e){

        return $this->sendError($e,500);
        }
    }


       /**
        *
     *     @OA\Delete(
     *      path="/v1.0/garage-galleries/{garage_id}/{id}",
     *      operationId="deleteGarageGalleryById",
     *      tags={"garage_gallery_management"},
    *       security={
     *           {"bearerAuth": {}}
     *       },
     * *              @OA\Parameter(
     *         name="garage_id",
     *         in="path",
     *         description="garage_id",
     *         required=true,
     *  example="1"
     *      ),
     *              @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id",
     *         required=true,
     *  example="1"
     *      ),
     *      summary="This method is to delete garage gallery by id",
     *      description="This method is to delete garage gellery by id",
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

    public function deleteGarageGalleryById($garage_id,$id,Request $request) {

        try{
            if(!$request->user()->hasPermissionTo('garage_gallery_delete')){
                return response()->json([
                   "message" => "You can not perform this action"
                ],401);
           }
           if (!$this->garageOwnerCheck($garage_id)) {
            return response()->json([
                "message" => "you are not the owner of the garage or the requested garage does not exist."
            ], 401);
        }

        $garage_gallery  = GarageGallery::where([
            "id" => $id,
            "garage_id" => $garage_id
           ])
           ->first();
           if(!$garage_gallery) {
            return response()->json([
                "message" => "gallery not found"
                    ], 404);
           }

        // Define the path of the file you want to delete
$location =  config("setup-config.garage_gallery_location");
$file_path = public_path($location) . '/' . $garage_gallery->image;

// Check if the file exists before trying to delete it
if (file_exists($file_path)) {
    unlink($file_path);
}
        $garage_gallery->delete();




            return response()->json(["ok" => true], 200);
        } catch(Exception $e){

        return $this->sendError($e,500);
        }

    }



}