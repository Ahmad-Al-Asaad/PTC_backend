<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityAnswer as OpportunityAnswersResource;
use App\Models\OpportunityQuestion;
use App\Models\OpportunityAnswer;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpportunityAnswerController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/OpportunityAnswer/add",
     *   tags={"OpportunityAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add OpportunityAnswer",
     *   operationId="1-Add OpportunityAnswer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass OpportunityAnswer data",
     *    @OA\JsonContent(
     *      @OA\Property(property="studentID", type="number", example=1),
     *      @OA\Property(property="questionID", type="number", example=1),
     *       @OA\property(property="answer", type="string", example="title"),
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

    public function addOpportunityAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'questionID' => 'required|numeric',
                'answer' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $opportunityQuestion = OpportunityQuestion::where('id', $request->questionID)->first();
        if (is_null($opportunityQuestion)) {
            return $this->sendError('questionID Not Found');
        }
        $answer = new OpportunityAnswer();
        $answer->studentID = $request->studentID;
        $answer->questionID = $request->questionID;
        $answer->answer = $request->answer;
        $answer->save();
        return $this->sendResponse(new OpportunityAnswersResource($answer), 'OpportunityAnswer Added successfully.');
    }


    /**
     * @OA\Put(
     ** path="/api/OpportunityAnswer/edit",
     *   tags={"OpportunityAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit OpportunityAnswer",
     *   operationId="2-Edit OpportunityAnswer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass OpportunityAnswer data",
     *    @OA\JsonContent(
     *     @OA\Property(property="id", type="number", example=1),
     *     @OA\Property(property="studentID", type="number", example=1),
     *      @OA\Property(property="questionID", type="number", example=1),
     *       @OA\property(property="answer", type="string", example="title"),
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
    public function editOpportunityAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'questionID' => 'required|numeric',
                'answer' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $answer = OpportunityAnswer::where('id', $request->id)->first();
        if (is_null($answer)) {
            return $this->sendError('OpportunityAnswer Not Found');
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $opportunityQuestion = OpportunityAnswer::where('id', $request->questionID)->first();
        if (is_null($opportunityQuestion)) {
            return $this->sendError('questionID Not Found');
        }
        $answer->studentID = $request->studentID;
        $answer->questionID = $request->questionID;
        $answer->answer = $request->answer;
        $answer->save();
        return $this->sendResponse(new OpportunityAnswersResource($answer), 'The Edit has been Done.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/OpportunityAnswer/delete/{id}",
     *   tags={"OpportunityAnswer"},
     *   summary="Delete from OpportunityAnswer ",
     *   operationId="3-delete OpportunityAnswer",
     *security={
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

    public function deleteOpportunityAnswer($id)
    {
        $answer = OpportunityAnswer::find($id);
        if (is_null($answer)) {
            return $this->sendError('Not Found Deleted Unsuccessfully.' );
        } else {
            $answer->delete();
            return $this->sendSuccess('deleted successfully.');
        }
    }


    /**
     * @OA\Get (
     ** path="/api/OpportunityAnswer/showByID/{id}",
     *   tags={"OpportunityAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from OpportunityAnswer ",
     *   operationId="4-Show OpportunityAnswer From OpportunityAnswers",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     * description="Returns info spcific event",
     * *   @OA\Response(
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
     */
    public function showOpportunityAnswerByID($id)
    {
        $answer = OpportunityAnswer::find($id);

        return $this->sendResponse(new OpportunityAnswersResource($answer), 'We Found it.');
    }

    /**
     * @OA\Get(
     *      path="/api/OpportunityAnswer/all",
     *      operationId="5-Get A List Of OpportunityAnswer",
     *      tags={"OpportunityAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of OpportunityAnswer",
     *      description="Returns A List Of OpportunityAnswer",
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

    public function getAllOpportunityAnswer()
    {
        $answer = OpportunityAnswer::all();
        return $this->sendResponse(OpportunityAnswersResource::collection($answer), 'OpportunityAnswers retrieved successfully.');

    }


    /**
     * @OA\Get (
     ** path="/api/OpportunityAnswer/showStudentAnswers/{id}",
     *   tags={"OpportunityAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show  OpportunityAnswer For Specific Student ",
     *   operationId="6-Show OpportunityAnswer From Specific Student",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     * description="Returns info spcific event",
     * *   @OA\Response(
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
     */
    public function getAllStudentAnswers($studentID)
    {
        $answer = OpportunityAnswer::where('studentID', $studentID)->get();

        return $this->sendResponse(OpportunityAnswersResource::collection($answer), 'OpportunityAnswer retrieved successfully.');
        // return $this->sendResponse($answer, 'We Found it.');
    }


    /**
     * @OA\Get (
     ** path="/api/OpportunityAnswer/showStudentAnswersInSpecificQuestion/{studentId}/{questionId}",
     *   tags={"OpportunityAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from OpportunityAnswer For Specific Student ",
     *   operationId="6-Show OpportunityAnswer From Specific Student",
     *
     *   @OA\Parameter(
     *     name="studentId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     *      @OA\Parameter(
     *     name="questionId",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     * description="Returns info spcific event",
     * *   @OA\Response(
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
     */
    public function showStudentAnswersInSpecificQuestion($studentId, $questionId)
    {
        $stdAnswers=OpportunityAnswer::where('studentID',$studentId)->where('questionID',$questionId)->get();

        return $this->sendResponse(OpportunityAnswersResource::collection($stdAnswers), 'OpportunityAnswer retrieved successfully.');

    }
}
