<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExhibitionAnswer as ExhibitionAnswersResource;
use App\Models\ExhibitionQuestion;
use App\Models\ExhibitionAnswer;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExhibitionAnswerController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/ExhibitionAnswer/add",
     *   tags={"ExhibitionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add ExhibitionAnswer",
     *   operationId="1-Add ExhibitionAnswer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ExhibitionAnswer data",
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

    public function addExhibitionAnswer(Request $request)
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
        $exhibitionQuestion = ExhibitionQuestion::where('id', $request->questionID)->first();
        if (is_null($exhibitionQuestion)) {
            return $this->sendError('questionID Not Found');
        }
        $answer = new ExhibitionAnswer();
        $answer->studentID = $request->studentID;
        $answer->questionID = $request->questionID;
        $answer->answer = $request->answer;
        $answer->save();
        return $this->sendResponse(new ExhibitionAnswersResource($answer), 'ExhibitionAnswer Added successfully.');
    }


    /**
     * @OA\Put(
     ** path="/api/ExhibitionAnswer/edit",
     *   tags={"ExhibitionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit ExhibitionAnswer",
     *   operationId="2-Edit ExhibitionAnswer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass ExhibitionAnswer data",
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
    public function editExhibitionAnswer(Request $request)
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
        $answer = ExhibitionAnswer::where('id', $request->id)->first();
        if (is_null($answer)) {
            return $this->sendError('ExhibitionAnswer Not Found');
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $exhibitionQuestion = ExhibitionAnswer::where('id', $request->questionID)->first();
        if (is_null($exhibitionQuestion)) {
            return $this->sendError('questionID Not Found');
        }
        $answer->studentID = $request->studentID;
        $answer->questionID = $request->questionID;
        $answer->answer = $request->answer;
        $answer->save();
        return $this->sendResponse(new ExhibitionAnswersResource($answer), 'The Edit has been Done.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/ExhibitionAnswer/delete/{id}",
     *   tags={"ExhibitionAnswer"},
     *   summary="Delete from ExhibitionAnswer ",
     *   operationId="3-delete ExhibitionAnswer",
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

    public function deleteExhibitionAnswer($id)
    {
        $answer = ExhibitionAnswer::find($id);
        if (is_null($answer)) {
            return $this->sendError('Not Found Deleted Unsuccessfully.' );
        } else {
            $answer->delete();
            return $this->sendSuccess('deleted successfully.');
        }
    }


    /**
     * @OA\Get (
     ** path="/api/ExhibitionAnswer/showByID/{id}",
     *   tags={"ExhibitionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from ExhibitionAnswer ",
     *   operationId="4-Show ExhibitionAnswer From ExhibitionAnswers",
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
    public function showExhibitionAnswerByID($id)
    {
        $answer = ExhibitionAnswer::find($id);

        return $this->sendResponse(new ExhibitionAnswersResource($answer), 'We Found it.');
    }

    /**
     * @OA\Get(
     *      path="/api/ExhibitionAnswer/all",
     *      operationId="5-Get A List Of ExhibitionAnswer",
     *      tags={"ExhibitionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of ExhibitionAnswer",
     *      description="Returns A List Of ExhibitionAnswer",
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

    public function getAllExhibitionAnswer()
    {
        $answer = ExhibitionAnswer::all();
        return $this->sendResponse(ExhibitionAnswersResource::collection($answer), 'ExhibitionAnswers retrieved successfully.');

    }


    /**
     * @OA\Get (
     ** path="/api/ExhibitionAnswer/showStudentAnswers/{id}",
     *   tags={"ExhibitionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show  ExhibitionAnswer For Specific Student ",
     *   operationId="6-Show ExhibitionAnswer From Specific Student",
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
        $answer = ExhibitionAnswer::where('studentID', $studentID)->get();

        return $this->sendResponse(ExhibitionAnswersResource::collection($answer), 'ExhibitionAnswer retrieved successfully.');
    }


    /**
     * @OA\Get (
     ** path="/api/ExhibitionAnswer/showStudentAnswersInSpecificQuestion/{studentId}/{questionId}",
     *   tags={"ExhibitionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from ExhibitionAnswer For Specific Student ",
     *   operationId="6-Show ExhibitionAnswer From Specific Student",
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
        $stdAnswers=ExhibitionAnswer::where('studentID',$studentId)->where('questionID',$questionId)->get();

        return $this->sendResponse(ExhibitionAnswersResource::collection($stdAnswers), 'ExhibitionAnswer retrieved successfully.');

    }
}
