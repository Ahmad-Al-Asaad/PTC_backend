<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Opportunity as OpportunityResource;
//use App\Http\Resources\OpportunityQuestion;
use App\Http\Resources\OpportunityQuestion as QuestionResource;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\OpportunityQuestion;
use App\Models\OpportunityQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpportunityQuestionController extends BaseController
{
    /**
     * @OA\Post(
     *   path="/api/opportunityQuestion/addOpportunityQuestion",
     *   tags={"OpportunityQuestion"},
     *   summary="add for new OpportunityQuestion ",
     *   operationId="1-addOpportunityQuestion",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *    @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="opportunityId", type="number", example=1),
     *       @OA\Property(property="title", type="string", example="what ?"),
     *       @OA\Property(property="type", type="number", example=1),
     *       @OA\Property(property="required", type="boolean", example=1),
     *       @OA\Property(
     *             property="questionsAnswers",
     *             type="array" ,
     *             @OA\Items(
     *                    @OA\Property(property="title", type="string", example="title12"),
     *                    @OA\Property(property="state", type="number", example=1),
     *            ),
     *       ),
     *    ),
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

    public function addQuestion(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'opportunityId' => 'required',
                'title' => 'required',
                'type' => 'required',
                'required' => 'required|boolean'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $opportunityId = Opportunity::find($request->opportunityId);
        if (is_null($opportunityId)) {
            return $this->sendError('OpportunityId Not Found.');
        } else {
            $question = new OpportunityQuestion;
            $question->opportunityId = $request->opportunityId;
            $question->title = $request->title;
            $question->type = $request->type;
            $question->required = $request->required;
            $question->save();

            if (isset($request->questionsAnswers)) {
                foreach ($request->questionsAnswers as $key => $value) {
                    $questionAnswer = new OpportunityQuestionAnswer;
                    $questionAnswer->questionID = $question->id;
                    $questionAnswer->title = $value['title'];
                    $questionAnswer->state = $value['state'];
                    $questionAnswer->save();
                }
            }

            return $this->sendResponse(new QuestionResource($question), 'Add OpportunityQuestion successfully.');
        }
    }

    /**
     * @OA\put(
     ** path="/api/opportunityQuestion/editOpportunityQuestion",
     *   tags={"OpportunityQuestion"},
     *   summary="edit for new OpportunityQuestion ",
     *   operationId="2-editOpportunityQuestion",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *    @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="id", type="number", example=1),
     *       @OA\property(property="opportunityId", type="number", example=1),
     *       @OA\Property(property="title", type="string", example="what ?"),
     *       @OA\Property(property="type", type="number", example=1),
     *       @OA\Property(property="required", type="boolean", example=1),
     *       @OA\Property(
     *                  property="questionsAnswers",
     *                  type="array" ,
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="title", type="string", example="title12"),
     *                      @OA\Property(property="state", type="number", example=1),
     *                  ),
     *              ),
     *    ),
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

    public function editQuestion(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'opportunityId' => 'required',
                'title' => 'required',
                'type' => 'required',
                'required' => 'required|boolean'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $question = OpportunityQuestion::where('id', $request->id)->first();
        if (is_null($question)) {
            return $this->sendError('Not Found OpportunityQuestion.');
        } else {
            $question->title = $request->title;
            $question->type = $request->type;
            $question->required = $request->required;
            $question->save();

            if (isset($request->questionsAnswers)) {
                foreach ($request->questionsAnswers as $key => $value) {
                    $questionAnswer = OpportunityQuestionAnswer::where('id', $value['id'])->first();
                    if (is_null($questionAnswer)) {
                        return $this->sendError('questionAnswer Not Found');
                    }
                    $questionAnswer->questionID = $question->id;
                    $questionAnswer->title = $value['title'];
                    $questionAnswer->state = $value['state'];
                    $questionAnswer->save();
                }
            }
            return $this->sendResponse(new QuestionResource($question), 'The Edit has been Done.');
        }
    }

    /**
     * @OA\delete(
     ** path="/api/opportunityQuestion/deleteOpportunityQuestion/{id}",
     *   tags={"OpportunityQuestion"},
     *   summary="delet from OpportunityQuestion by id",
     *   operationId="3-deleteOpportunityQuestion",
     *   security={{ "bearer_token":{} }},
     *   description="",
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
    public function deleteQuestion($id)
    {
        $question = OpportunityQuestion::find($id);
        if (is_null($question)) {
            return $this->sendError('Not Found OpportunityQuestion.');
        } else {
            $question->delete();
            return $this->sendSuccess('delete OpportunityQuestion successfully.');
        }
    }

    /**
     * @OA\Get(
     ** path="/api/opportunityQuestion/showOpportunityQuestionsByOpportunityID/{id}",
     *   tags={"OpportunityQuestion"},
     *   summary="show from show Opportunity Questions By OpportunityID ",
     *   operationId="4-showOpportunityQuestions",
     *   security={{ "bearer_token":{} }},
     *   description="",
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
    public function showQuestionsByOpportunityID($opportunityID)
    {
        $question = OpportunityQuestion::where('opportunityId', $opportunityID)->get();

        return $this->sendResponse(QuestionResource::collection($question), 'We Found it.');

    }

    /**
     * @OA\Get(
     ** path="/api/opportunityQuestion/showAllOpportunityQuestions",
     *   tags={"OpportunityQuestion"},
     *   summary="show from OpportunityQuestion ",
     *   operationId="5-showAllOpportunityQuestions",
     *   security={{ "bearer_token":{} }},
     *   description="Returns list of all OpportunityQuestions",
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
    public function showAllQuestions()
    {
        $products = OpportunityQuestion::all();

        return $this->sendResponse(QuestionResource::collection($products), 'successfully.');
    }
}
