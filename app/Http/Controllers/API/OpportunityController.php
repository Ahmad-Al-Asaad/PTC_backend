<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Opportunity as OpportunityResource;
use App\Http\Resources\OpportunityQuestion as OpportunityQuestionResource;
use App\Http\Resources\OpportunityQuestion as QuestionResource;
use App\Models\Company;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OpportunityController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/opportunity/addOpportunity",
     *   tags={"Opportunities"},
     *   summary="add for new opportunity ",
     *   operationId="1-add",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *   @OA\RequestBody(
     *    required=false,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="title", type="string", example="frontEnd"),
     *       @OA\Property(property="state", type="number", example=1),
     *       @OA\Property(property="type", type="number", example=1),
     *       @OA\Property(property="companyID", type="number", example=1),
     *       @OA\Property(property="freeDesks", type="number", example=1),
     *       @OA\Property(property="lastDateForRegister", type="date", example="1/2/2020"),
     *       @OA\property(property="salary", type="number", example=20000),
     *       @OA\Property(property="time", type="number", example=1),
     *       @OA\Property(property="location", type="string", example="Damascus/Mazaa"),
     *       @OA\Property(property="scope", type="string", example="WebsiteDeveloper"),
     *       @OA\Property(property="description", type="string", example=""),
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

    public function addOpportunity(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|string|unique:opportunities',
                'state' => 'required',
                'companyID' => 'required',
                'type' => 'required',
                'freeDesks' => 'required',
                'lastDateForRegister' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $companyID = Company::find($request->companyID);
        if (is_null($companyID)) {
            return $this->sendError('Not Found Company.');
        } else {

            $opportunity = new Opportunity;
            $opportunity->title = $request->title;
            $opportunity->state = $request->state;
            $opportunity->type = $request->type;
            $opportunity->companyID = $request->companyID;
            $opportunity->freeDesks = $request->freeDesks;
            $opportunity->lastDateForRegister = $request->lastDateForRegister;
            $opportunity->salary = isset($request->salary) ? $request->salary : null;
            $opportunity->time = isset($request->time) ? $request->time : null;
            $opportunity->location = isset($request->location) ? $request->location : null;
            $opportunity->scope = isset($request->scope) ? $request->scope : null;
            $opportunity->description = isset($request->description) ? $request->description : null;
            $opportunity->save();

            return $this->sendResponse(new OpportunityResource($opportunity), 'Add Opportunity successfully.');
        }
    }


    /**
     * @OA\put(
     ** path="/api/opportunity/deleteOpportunity/{id}",
     *   tags={"Opportunities"},
     *   summary="delet from opportunity by id",
     *   operationId="2-deleteOpportunity",
     *   security={{ "bearer_token":{} }},
     *   description="change state opportuntity to delete",
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
    public function deleteOpportunity($id)
    {
        $opportunity = Opportunity::find($id);
        if (is_null($opportunity)) {
            return $this->sendError('Not Found Opportunity.');
        } else {
            $opportunity->state = '3';
            $opportunity->save();
            return $this->sendResponse(new OpportunityResource($opportunity), 'change state to delete successfully.');
        }
    }


    /**
     * @OA\put(
     ** path="/api/opportunity/editOpportunity",
     *   tags={"Opportunities"},
     *   summary="edit info opportunity ",
     *   operationId="3-editOpportunity",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *   @OA\RequestBody(
     *    required=false,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="id", type="number", example=1),
     *       @OA\property(property="title", type="string", example="frontEnd"),
     *       @OA\Property(property="state", type="number", example=1),
     *       @OA\Property(property="type", type="number", example=1),
     *       @OA\Property(property="companyID", type="number", example=1),
     *       @OA\Property(property="freeDesks", type="number", example=1),
     *       @OA\Property(property="lastDateForRegister", type="date", example="1/2/2020"),
     *       @OA\property(property="salary", type="number", example=20000),
     *       @OA\Property(property="time", type="number", example=1),
     *       @OA\Property(property="location", type="string", example="Damascus/Mazaa"),
     *       @OA\Property(property="scope", type="string", example="WebsiteDeveloper"),
     *       @OA\Property(property="description", type="string", example=""),
     *    ),
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
    public function editOpportunity(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|string',
                'state' => 'required',
                'companyID' => 'required',
                'type' => 'required',
                'freeDesks' => 'required',
                'lastDateForRegister' => 'required'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $opportunity = Opportunity::where('id', $request->id)->first();

        if (is_null($opportunity)) {
            return $this->sendError('Not Found ');
        } else {
            $opportunity->title = $request->title;
            $opportunity->state = $request->state;
            $opportunity->type = $request->type;
            $opportunity->companyID = $request->companyID;
            $opportunity->freeDesks = $request->freeDesks;
            $opportunity->lastDateForRegister = $request->lastDateForRegister;
            $opportunity->salary = isset($request->salary) ? $request->salary : null;
            $opportunity->time = isset($request->time) ? $request->time : null;
            $opportunity->location = isset($request->location) ? $request->location : null;
            $opportunity->scope = isset($request->scope) ? $request->scope : " ";
            $opportunity->description = isset($request->description) ? $request->description : null;
            $opportunity->save();

            return $this->sendResponse(new OpportunityResource($opportunity), 'opportunity edit successfully.');
        }
    }

    /**
     * @OA\Get(
     ** path="/api/opportunity/showOpportunityById/{id}",
     *   tags={"Opportunities"},
     *   summary="show from opportunity By ID",
     *   operationId="4-showOpportunityById",
     *   description="Returns info spcific Opportunity",
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
    public function showOpportunityById($id)
    {
        $opportunity = Opportunity::find($id);
        if (is_null($opportunity))
            return $this->sendError('Not Found Opportunity.');
        return $this->sendResponse($opportunity, 'Found it');
    }

    /**
     * @OA\Get(
     *      path="/api/opportunity/showAllOpportunity",
     *      operationId="5-showAllOpportunity",
     *      tags={"Opportunities"},
     *      security={{ "bearer_token":{} }},
     *      summary="show list of Opportunities",
     *      description="Returns list of all Opportunities",
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

    public function getAllOpportunity()
    {
        $products = Opportunity::all();

        return $this->sendResponse(OpportunityResource::collection($products), 'Opportunities retrieved successfully.');
    }

    /**
     * @OA\put(
     ** path="/api/opportunity/changeStateOpportunity/{id}/{state}",
     *   tags={"Opportunities"},
     *   summary="changeState from opportunity ",
     *   operationId="6-changeState",
     *   security={{ "bearer_token":{} }},
     *   description="change state  opportunity to any state (1-active or 2-inactive or 3-delete or 4-finished)",
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="state",
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
     *)
     **/
    public function changeState($id, $state)
    {
        $opportunity = Opportunity::find($id);
        if (is_null($opportunity)) {
            return $this->sendError('Not Found Opportunity.');
        } else {
            $opportunity->state = $state;
            $opportunity->save();

            return $this->sendResponse(new OpportunityResource($opportunity), 'opportunity edit successfully.');
        }
    }

}
