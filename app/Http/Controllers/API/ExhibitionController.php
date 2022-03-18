<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Exhibition as ExhibitionResource;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExhibitionController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/Exhibition/addExhibition",
     *   tags={"Exhibition"},
     *   summary="add for new Exhibition ",
     *   operationId="1-add",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *   @OA\RequestBody(
     *    required=false,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="title", type="string", example="frontEnd"),
     *       @OA\Property(property="startDate", type="date", example="1/2/2020"),
     *       @OA\Property(property="endDate", type="date", example="1/2/2020"),
     *       @OA\Property(property="location", type="string", example="damascus"),
     *       @OA\Property(property="description", type="string", example=""),
     *       @OA\Property(property="manager", type="string", example=""),
     *    )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    public function addExhibition(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'startDate' => 'dateFormat:'. config('app.dateFormat'),
                'endDate' => 'dateFormat:'. config('app.dateFormat'),
                'location' => 'required',
                'description' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $exhibition = new Exhibition;
        $exhibition->title = $request->title;
        $exhibition->startDate = $request->startDate;
        $exhibition->endDate = $request->endDate;
        $exhibition->location = $request->location;
        $exhibition->description = $request->description;
        $exhibition->manager = isset($request->manager) ? $request->manager : null;
        $exhibition->save();

        return $this->sendResponse(new ExhibitionResource($exhibition), 'Add Exhibition successfully.');

    }


    /**
     * @OA\put(
     ** path="/api/Exhibition/deleteExhibition/{id}",
     *   tags={"Exhibition"},
     *   summary="delet from Exhibition by id",
     *   operationId="2-deleteExhibition",
     *   security={{ "bearer_token":{} }},
     *   description="change state Exhibition to delete",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function deleteExhibition($id)
    {
        $exhibition = Exhibition::find($id);
        if (is_null($exhibition)) {
            return $this->sendError('Not Found Exhibition.');
        } else {
            $exhibition->delete();
            return $this->sendResponse(new ExhibitionResource($exhibition), 'change state to delete successfully.');
        }
    }


    /**
     * @OA\put(
     ** path="/api/Exhibition/editExhibition",
     *   tags={"Exhibition"},
     *   summary="edit info Exhibition ",
     *   operationId="3-editExhibition",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *   @OA\RequestBody(
     *    required=false,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="id", type="number", example=1),
     *       @OA\property(property="title", type="string", example="frontEnd"),
     *       @OA\Property(property="startDate", type="date", example="1/2/2020"),
     *       @OA\Property(property="endDate", type="date", example="1/2/2020"),
     *       @OA\Property(property="location", type="string", example="damascus"),
     *       @OA\Property(property="description", type="string", example=""),
     *       @OA\Property(property="manager", type="string", example=""),
     *    )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function editExhibition(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required',
                'title' => 'required',
                'startDate' => 'dateFormat:'. config('app.dateFormat'),
                'endDate' => 'dateFormat:'. config('app.dateFormat'),
                'location' => 'required',
                'description' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $exhibition = Exhibition::where('id', $request->id)->first();

        if (is_null($exhibition)) {
            return $this->sendError('Not Found ');
        } else {
            $exhibition->title = $request->title;
            $exhibition->startDate = $request->startDate;
            $exhibition->endDate = $request->endDate;
            $exhibition->location = $request->location;
            $exhibition->description = $request->description;
            $exhibition->manager = isset($request->manager) ? $request->manager : null;
            $exhibition->save();

            return $this->sendResponse(new ExhibitionResource($exhibition), 'Exhibition edit successfully.');
        }
    }

    /**
     * @OA\Get(
     ** path="/api/Exhibition/showExhibitionById/{id}",
     *   tags={"Exhibition"},
     *   summary="show from Exhibition By ID",
     *   operationId="4-showExhibitionById",
     *   description="Returns info spcific Exhibition",
     *   security={{ "bearer_token":{} }},
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *
     *)
     **/
    public function showExhibitionById($id)
    {
        $exhibition = Exhibition::find($id);

        return $this->sendResponse($exhibition, 'Found it');
    }

    /**
     * @OA\Get(
     *      path="/api/Exhibition/showAllExhibition",
     *      operationId="5-showAllExhibition",
     *      tags={"Exhibition"},
     *      security={{ "bearer_token":{} }},
     *      summary="show list of Exhibition",
     *      description="Returns list of all Exhibition",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *      ),
     *       @OA\Response(
     *      response=404,
     *      description="not found"
     *      ),
     *  )
     */

    public function getAllExhibition()
    {
        $products = Exhibition::all();

        return $this->sendResponse(ExhibitionResource::collection($products), 'Exhibition retrieved successfully.');
    }
}
