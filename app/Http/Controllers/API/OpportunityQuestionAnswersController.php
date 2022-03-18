<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityQuestionAnswers as QuestionAnswersResources;
use App\Models\OpportunityQuestion;
use App\Models\OpportunityQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpportunityQuestionAnswersController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/opportunityQuestionAnswer/addAnswer",
     *   tags={"OpportunityQuestionAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Opportunity Qusetion Answer For Question",
     *   operationId="1- Add Opportunity Qusetion Answer For Question",
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
    public function addOpportunityQuestionAnswer(Request $request)
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
        $exsit = OpportunityQuestion::where('id', $request->questionID)->first();
        if (is_null($exsit)) {
            return $this->sendError('Not Found OpportunityQuestion.');
        } else {

            $answerQuestion = new OpportunityQuestionAnswer;
            $answerQuestion->questionID = $request->questionID;
            $answerQuestion->title = $request->title;
            $answerQuestion->state = $request->state;
            $answerQuestion->save();
            return $this->sendResponse(new QuestionAnswersResources($answerQuestion), 'Add OpportunityQuestionAnswer successfully.');
        }
    }

    /**
     * @OA\Put(
     ** path="/api/opportunityQuestionAnswer/editAnswer",
     *   tags={"OpportunityQuestionAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Opportunity Qusetion Answer For Question",
     *   operationId="2- Edit Opportunity Qusetion Answer For Question",
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
    public function editOpportunityQuestionAnswer(Request $request)
    {
        $answerQuestion = OpportunityQuestionAnswer::find($request->id);
        if (is_null($answerQuestion)) {
            return $this->sendError('Not Found OpportunityQuestionAnswer');
        } else {
            $answerQuestion->title = $request->title;
            $answerQuestion->state = $request->state;
            $answerQuestion->save();
            return $this->sendResponse(new QuestionAnswersResources($answerQuestion), 'The Edit has been Done.');
        }
    }

    /**
     * @OA\Delete  (
     ** path="/api/opportunityQuestionAnswer/deleteAnswer/{id}",
     *   tags={"OpportunityQuestionAnswers"},
     *   summary="Delete from Opportunity Qusetion Answer ",
     *   operationId="3-Delete from Opportunity Qusetion Answer",
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

    public function deleteOpportunityQuestionAnswer($id)
    {
        $answerQuestion = OpportunityQuestionAnswer::find($id);
        if (!is_null($answerQuestion)) {
            $answerQuestion->delete();
            return $this->sendSuccess('delete OpportunityQuestionAnswer successfully.');
        }
        return $this->sendError('Not Found OpportunityQuestionAnswer.');
    }

    /**
     * @OA\Get(
     *      path="/api/opportunityQuestionAnswer/showAnswersByQuestionID/{id}",
     *      operationId="4-Show All Answers Related On Specific Question",
     *      tags={"OpportunityQuestionAnswers"},
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
    public function showOpportunityQuestionAnswerById($questionID)
    {
        $answerQuestion = OpportunityQuestionAnswer::where('questionID', $questionID)->get();
        $size = sizeof($answerQuestion);

        return $this->sendResponse(QuestionAnswersResources::collection($answerQuestion), 'We Found it.');

    }

    /**
     * @OA\Get(
     *      path="/api/opportunityQuestionAnswer/showAllAnswer",
     *      operationId="5-Get List Of Questions Answers",
     *      tags={"OpportunityQuestionAnswers"},
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

    public
    function showAllOpportunityQuestionAnswer()
    {
        $answerQuestion = OpportunityQuestionAnswer::all();

        return $this->sendResponse(QuestionAnswersResources::collection($answerQuestion), 'Opportunity retrieved successfully.');
    }


    /**
     * @OA\Put   (
     ** path="/api/opportunityQuestionAnswer/changeState/{id}/{state}",
     *   tags={"OpportunityQuestionAnswers"},
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
    public
    function changeStateOpportunityQuestionAnswerById($id, $state)
    {
        $answerQuestion = OpportunityQuestionAnswer::find($id);
        if (is_null($answerQuestion)) {
            return $this->sendError('Not Found OpportunityQuestionAnswer.');
        } else {
            $answerQuestion->state = $state;
            $answerQuestion->save();
            return $this->sendResponse(new QuestionAnswersResources($answerQuestion), 'State Has been Changed.');
        }
    }
}
