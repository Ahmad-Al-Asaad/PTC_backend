<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExhibitionQuestion as QuestionResource;
use App\Models\Exhibition;
use App\Models\ExhibitionQuestion;
use App\Models\ExhibitionQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExhibitionQuestionController extends BaseController
{
    /**
     * @OA\Post(
     *   path="/api/ExhibitionQuestion/addExhibitionQuestion",
     *   tags={"ExhibitionQuestion"},
     *   summary="add for new ExhibitionQuestion ",
     *   operationId="1-addExhibitionQuestion",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *    @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="exhibitionId", type="number", example=1),
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
                'exhibitionId' => 'required',
                'title' => 'required',
                'type' => 'required',
                'required' => 'required|boolean'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $exhibitionId2 = Exhibition::find($request->exhibitionId);
//        $exhibitionId2 = Exhibition::where('id', $request->exhibitionId)->first();

        if (is_null($exhibitionId2)) {
            return $this->sendError('ExhibitionId Not Found.');
        } else {
            $question = new ExhibitionQuestion;
            $question->exhibitionId = $request->exhibitionId;
            $question->title = $request->title;
            $question->type = $request->type;
            $question->required = $request->required;
            $question->save();

            if (isset($request->questionsAnswers)) {
                foreach ($request->questionsAnswers as $key => $value) {
                    $questionAnswer = new ExhibitionQuestionAnswer;
                    $questionAnswer->questionID = $question->id;
                    $questionAnswer->title = $value['title'];
                    $questionAnswer->state = $value['state'];
                    $questionAnswer->save();
                }
            }

            return $this->sendResponse(new QuestionResource($question), 'Add ExhibitionQuestion successfully.');
        }
    }

    /**
     * @OA\put(
     ** path="/api/ExhibitionQuestion/editExhibitionQuestion",
     *   tags={"ExhibitionQuestion"},
     *   summary="edit for new ExhibitionQuestion ",
     *   operationId="2-editExhibitionQuestion",
     *   security={{ "bearer_token":{} }},
     *   description="",
     *
     *    @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\property(property="id", type="number", example=1),
     *       @OA\property(property="exhibitionId", type="number", example=1),
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
                'exhibitionId' => 'required',
                'title' => 'required',
                'type' => 'required',
                'required' => 'required|boolean'
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $question = ExhibitionQuestion::where('id', $request->id)->first();
        if (is_null($question)) {
            return $this->sendError('Not Found ExhibitionQuestion.');
        } else {
            $question->title = $request->title;
            $question->type = $request->type;
            $question->required = $request->required;
            $question->save();

            if (isset($request->questionsAnswers)) {
                foreach ($request->questionsAnswers as $key => $value) {
                    $questionAnswer = ExhibitionQuestionAnswer::where('id', $value['id'])->first();
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
     ** path="/api/ExhibitionQuestion/deleteExhibitionQuestion/{id}",
     *   tags={"ExhibitionQuestion"},
     *   summary="delet from ExhibitionQuestion by id",
     *   operationId="3-deleteExhibitionQuestion",
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
        $question = ExhibitionQuestion::find($id);
        if (is_null($question)) {
            return $this->sendError('Not Found ExhibitionQuestion.');
        } else {
            $question->delete();
            return $this->sendSuccess('delete ExhibitionQuestion successfully.');
        }
    }

    /**
     * @OA\Get(
     ** path="/api/ExhibitionQuestion/showExhibitionQuestionsByExhibitionId/{id}",
     *   tags={"ExhibitionQuestion"},
     *   summary="show from show Exhibition Questions By ExhibitionID ",
     *   operationId="4-showExhibitionQuestions",
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
    public function showQuestionsByExhibitionID($exhibitionId)
    {
        $question = ExhibitionQuestion::where('exhibitionId', $exhibitionId)->get();

        return $this->sendResponse(QuestionResource::collection($question), 'We Found it.');

    }

    /**
     * @OA\Get(
     ** path="/api/ExhibitionQuestion/showAllExhibitionQuestions",
     *   tags={"ExhibitionQuestion"},
     *   summary="show from ExhibitionQuestion ",
     *   operationId="5-showAllExhibitionQuestions",
     *   security={{ "bearer_token":{} }},
     *   description="Returns list of all ExhibitionQuestions",
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
        $products = ExhibitionQuestion::all();

        return $this->sendResponse(QuestionResource::collection($products), 'successfully.');
    }
}
