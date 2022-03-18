<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExhibitionQuestionAnswers as QuestionAnswersResources;
use App\Models\ExhibitionQuestion;
use App\Models\ExhibitionQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExhibitionQuestionAnswerController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/ExhibitionQuestionAnswer/addAnswer",
     *   tags={"ExhibitionQuestionAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Exhibition Qusetion Answer For Question",
     *   operationId="1- Add Exhibition Qusetion Answer For Question",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Question Answer  data",
     *    @OA\JsonContent(
     *       @OA\property(property="questionID", type="number", example=2),
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="state", type="number", example=1),
     *    ),
     * ),
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
    public function addExhibitionQuestionAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'questionID' => 'required',
                'title' => 'required',
                'state' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $exsit = ExhibitionQuestion::where('id', $request->questionID)->first();
        if (is_null($exsit)) {
            return $this->sendError('Not Found ExhibitionQuestion.');
        } else {

            $answerQuestion = new ExhibitionQuestionAnswer;
            $answerQuestion->questionID = $request->questionID;
            $answerQuestion->title = $request->title;
            $answerQuestion->state = $request->state;
            $answerQuestion->save();
            return $this->sendResponse(new QuestionAnswersResources($answerQuestion), 'Add ExhibitionQuestionAnswer successfully.');
        }
    }

    /**
     * @OA\Put(
     ** path="/api/ExhibitionQuestionAnswer/editAnswer",
     *   tags={"ExhibitionQuestionAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Exhibition Qusetion Answer For Question",
     *   operationId="2- Edit Exhibition Qusetion Answer For Question",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Question Answer  data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="state", type="number", example=1),
     *    ),
     * ),
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
    public function editExhibitionQuestionAnswer(Request $request)
    {
        $answerQuestion = ExhibitionQuestionAnswer::find($request->id);
        if (is_null($answerQuestion)) {
            return $this->sendError('Not Found ExhibitionQuestionAnswer');
        } else {
            $answerQuestion->title = $request->title;
            $answerQuestion->state = $request->state;
            $answerQuestion->save();
            return $this->sendResponse(new QuestionAnswersResources($answerQuestion), 'The Edit has been Done.');
        }
    }

    /**
     * @OA\Delete  (
     ** path="/api/ExhibitionQuestionAnswer/deleteAnswer/{id}",
     *   tags={"ExhibitionQuestionAnswers"},
     *   summary="Delete from Exhibition Qusetion Answer ",
     *   operationId="3-Delete from Exhibition Qusetion Answer",
     ** security={
     *  {"bearer_token":{}},
     *   },
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

    public function deleteExhibitionQuestionAnswer($id)
    {
        $answerQuestion = ExhibitionQuestionAnswer::find($id);
        if (!is_null($answerQuestion)) {
            $answerQuestion->delete();
            return $this->sendSuccess('delete ExhibitionQuestionAnswer successfully.');
        }
        return $this->sendError('Not Found ExhibitionQuestionAnswer.');
    }

    /**
     * @OA\Get(
     *      path="/api/ExhibitionQuestionAnswer/showAnswersByQuestionID/{id}",
     *      operationId="4-Show All Answers Related On Specific Question",
     *      tags={"ExhibitionQuestionAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     *      summary="Get list of Answers Related On Specific Question",
     *      description="Returns list of Answers Related On Specific Question",
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
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function showExhibitionQuestionAnswerById($questionID)
    {
        $answerQuestion = ExhibitionQuestionAnswer::where('questionID', $questionID)->get();

        return $this->sendResponse(QuestionAnswersResources::collection($answerQuestion), 'We Found it.');

    }

    /**
     * @OA\Get(
     *      path="/api/ExhibitionQuestionAnswer/showAllAnswer",
     *      operationId="5-Get List Of Questions Answers",
     *      tags={"ExhibitionQuestionAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get List Of Questions Answers",
     *      description="Returns List Of Questions Answers",
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
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */

    public function showAllExhibitionQuestionAnswer()
    {
        $answerQuestion = ExhibitionQuestionAnswer::all();

        return $this->sendResponse(QuestionAnswersResources::collection($answerQuestion), 'Exhibitions retrieved successfully.');
    }


    /**
     * @OA\Put   (
     ** path="/api/ExhibitionQuestionAnswer/changeState/{id}/{state}",
     *   tags={"ExhibitionQuestionAnswers"},
     *   summary="Change Answer Question State",
     *   operationId="6-Change Answer Question State",
     *
     *     security={
     *  {"bearer_token":{}},
     *   },
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     *      @OA\Parameter(
     *     name="state",
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
    public function changeStateExhibitionQuestionAnswerById($id, $state)
    {
        $answerQuestion = ExhibitionQuestionAnswer::find($id);
        if (is_null($answerQuestion)) {
            return $this->sendError('Not Found ExhibitionQuestionAnswer.');
        } else {
            $answerQuestion->state = $state;
            $answerQuestion->save();
            return $this->sendResponse(new QuestionAnswersResources($answerQuestion), 'State Has been Changed.');
        }
    }
}
