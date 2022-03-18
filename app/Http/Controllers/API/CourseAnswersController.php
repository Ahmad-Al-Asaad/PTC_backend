<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CourseAnswers;
use App\Models\CourseQuestion;
use App\Models\CourseQuestionAnswer;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\CourseAnswers as CourseAnswersResource;
use Illuminate\Support\Facades\Validator;

class CourseAnswersController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/courseAnswer/add",
     *   tags={"CourseAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add CourseAnswer",
     *   operationId="1-Add CourseAnswer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass CourseAnswer data",
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

    public function addCourseAnswers(Request $request)
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
        $courseQuestion = CourseQuestion::where('id', $request->questionID)->first();
        if (is_null($courseQuestion)) {
            return $this->sendError('questionID Not Found');
        }
        $answer = new CourseAnswers();
        $answer->studentID = $request->studentID;
        $answer->questionID = $request->questionID;
        $answer->answer = $request->answer;
        $answer->save();
        return $this->sendResponse(new CourseAnswersResource($answer), 'CourseAnswers Added successfully.');
    }


    /**
     * @OA\Put(
     ** path="/api/courseAnswer/edit",
     *   tags={"CourseAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit CourseAnswer",
     *   operationId="2-Edit CourseAnswer",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass CourseAnswer data",
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
    public function editCourseAnswers(Request $request)
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
        $answer = CourseAnswers::where('id', $request->id)->first();
        if (is_null($answer)) {
            return $this->sendError('CourseAnswers Not Found');
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $courseQuestion = CourseQuestion::where('id', $request->questionID)->first();
        if (is_null($courseQuestion)) {
            return $this->sendError('questionID Not Found');
        }
        $answer->studentID = $request->studentID;
        $answer->questionID = $request->questionID;
        $answer->answer = $request->answer;
        $answer->save();
        return $this->sendResponse(new CourseAnswersResource($answer), 'The Edit has been Done.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/courseAnswer/delete/{id}",
     *   tags={"CourseAnswers"},
     *   summary="Delete from CourseAnswers ",
     *   operationId="3-delete CourseAnswer",
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

    public function deleteCourseAnswer($id)
    {
        $answer = CourseAnswers::find($id);
        if (is_null($answer)) {
            return $this->sendError('Not Found Deleted Unsuccessfully.' );
        } else {
            $answer->delete();
            return $this->sendSuccess('deleted successfully.');
        }
    }


    /**
     * @OA\Get (
     ** path="/api/courseAnswer/showByID/{id}",
     *   tags={"CourseAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from CourseAnswers ",
     *   operationId="4-Show CourseAnswer From CourseAnswers",
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
    public function showCourseAnswerByID($id)
    {
        $answer = CourseAnswers::find($id);
        if (is_null($answer))
            return $this->sendError('CourseAnswers Not Found ');
        return $this->sendResponse(new CourseAnswersResource($answer), 'We Found it.');
    }

    /**
     * @OA\Get(
     *      path="/api/courseAnswer/all",
     *      operationId="5-Get A List Of CourseAnswers",
     *      tags={"CourseAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of CourseAnswers",
     *      description="Returns A List Of CourseAnswers",
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

    public function getAllCourseAnswers()
    {
        $answer = CourseAnswers::all();
        return $this->sendResponse(CourseAnswersResource::collection($answer), 'CourseAnswers retrieved successfully.');

    }


    /**
     * @OA\Get (
     ** path="/api/courseAnswer/showStudentAnswers/{id}",
     *   tags={"CourseAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show  CourseAnswers For Specific Student ",
     *   operationId="6-Show CourseAnswer From Specific Student",
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
        $answer = CourseAnswers::where('studentID', $studentID)->get();

        return $this->sendResponse(CourseAnswersResource::collection($answer), 'CourseAnswers retrieved successfully.');
        // return $this->sendResponse($answer, 'We Found it.');
    }


    /**
     * @OA\Get (
     ** path="/api/courseAnswer/showStudentAnswersInSpecificQuestion/{studentId}/{questionId}",
     *   tags={"CourseAnswers"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from CourseAnswers For Specific Student ",
     *   operationId="6-Show CourseAnswer From Specific Student",
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
        $stdAnswers=CourseAnswers::where('studentID',$studentId)->where('questionID',$questionId)->get();

        return $this->sendResponse(CourseAnswersResource::collection($stdAnswers), 'CourseAnswers retrieved successfully.');

    }
}
