<?php

namespace App\Http\Controllers\API;

use App\Models\CourseAnswers;
use App\Models\CourseLoginRequests;
use App\Models\CourseQuestion;
use App\Models\Courses;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
//use Illuminate\Http\Resources\Json\JsonResource as courseLoginRequest;
use App\Http\Resources\courseLoginRequest as courseLoginRequest;

class CourseLoginRequestController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/courseLoginRequest/add",
     *   tags={"CourseLoginRequests"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add CourseLoginRequest",
     *   operationId="1-Add CourseLoginRequest",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass CourseLoginRequest data",
     *    @OA\JsonContent(
     *
     *       @OA\Property(property="studentID", type="number", example=1),
     *       @OA\Property(property="courseID", type="number", example=1),
     *       @OA\Property(property="state", type="number", example=1),
     *      @OA\Property(
     *                  property="Answers",
     *                  type="array" ,
     *                  @OA\Items(
     *                      @OA\Property(property="questionID", type="number", example=1),
     *                      @OA\Property(property="answer", type="string", example=1),
     *                  ),
     *              ),
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

    public function addCourseLoginRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'courseID' => 'required|numeric',
                'state' => 'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $studentId = Student::where('userID', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $courseId = Courses::where('id', $request->courseID)->first();
        if (is_null($courseId)) {
            return $this->sendError('courseID Not Found');
        }


        $loginRequest = CourseLoginRequests::where('courseID', $request->courseID)->where('studentID', $studentId->id)->first();
        if ($loginRequest) {
            return $this->sendError('Cant login request in course you are already exist');
        }

        if (isset($request->Answers)) {
            foreach ($request->Answers as $key => $value) {
                $answers = new CourseAnswers();
                $questionid = CourseQuestion::where('id', $value['questionID'])->first();
                if (is_null($questionid)) {
                    return $this->sendError('questionID Not Found');
                }
                $answers->studentID = $studentId->id;
                $answers->questionID = $value['questionID'];
                $answers->answer = $value['answer'];
                $answers->save();
            }
        }
        $loginRequest = new CourseLoginRequests();
        $loginRequest->studentID = $studentId->id;
        $loginRequest->courseID = $request->courseID;
        $loginRequest->state = $request->state;
        $loginRequest->save();

        return $this->sendResponse(new courseLoginRequest($loginRequest), 'LoginRequest Added successfully.');

    }

    /**
     * @OA\Put(
     ** path="/api/courseLoginRequest/edit",
     *   tags={"CourseLoginRequests"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit CourseLoginRequest",
     *   operationId="2-Edit CourseLoginRequest",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass CourseLoginRequest data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="studentID", type="number", example=1),
     *       @OA\Property(property="courseID", type="number", example=1),
     *       @OA\Property(property="state", type="number", example=1),
     *          @OA\Property(
     *                  property="Answers",
     *                  type="array" ,
     *                  @OA\Items(
     *          *       @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="questionID", type="number", example=1),
     *                      @OA\Property(property="answer", type="string", example=1),
     *                  ),
     *              ),
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

    public function editCourseLoginRequest(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'courseID' => 'required|numeric',
                'state' => 'required|numeric',

            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $loginRequest = CourseLoginRequests::where('id', $request->id)->first();
        if (is_null($loginRequest)) {
            return $this->sendError('LoginRequest Not Found');
        }
        $studentId = Student::where('id', $request->studentID)->first();
        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }
        $courseId = Courses::where('id', $request->courseID)->first();
        if (is_null($courseId)) {
            return $this->sendError('courseID Not Found');
        }



        if (isset($request->Answers)) {
            foreach ($request->Answers as $key => $value) {
                $answers = CourseAnswers::where('id', $value['id'])->first();
                if (is_null($answers)) {
                    return $this->sendError('answers Not Found');
                }
                $questionid = CourseQuestion::where('id', $value['questionID'])->first();
                if (is_null($questionid)) {
                    return $this->sendError('questionID Not Found');
                }
                $answers->studentID = $request->studentID;
                $answers->questionID = $value['questionID'];
                $answers->answer = $value['answer'];
                $answers->save();
            }
        }
        $loginRequest->studentID = $request->studentID;
        $loginRequest->courseID = $request->courseID;
        $loginRequest->state = $request->state;
        $loginRequest->save();
        return $this->sendResponse(new courseLoginRequest($loginRequest), 'LoginRequest Edit successfully.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/courseLoginRequest/delete/{id}",
     *   tags={"CourseLoginRequests"},
     *   summary="Delete from CourseLoginRequest ",
     *   operationId="3-Delete from CourseLoginRequest",
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

    public function deleteCourseLoginRequest($id)
    {
        $loginRequest = CourseLoginRequests::find($id);
        if (is_null($loginRequest)) {
            return $this->sendError('deleted Unsuccessfully.');
        } else {
            $loginRequest->delete();
            return $this->sendSuccess('not Found Deleted successfully.');
        }
    }

    /**
     * @OA\Get (
     ** path="/api/courseLoginRequest/showAcceptedLogs/{courseID}",
     *   tags={"CourseLoginRequests"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show Accepted LoginRequests from LoginRequest ",
     *   operationId="4-show Accepted LoginRequests from LoginRequest",
     *
     * description="Returns info Accepted LoginRequests",
     *     @OA\Parameter(
     *     name="courseID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
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

    public function showAcceptedLoginRequest($courseID)
    {
        $state = 3;
        $loginRequest = CourseLoginRequests::where('state', $state)->where('courseID', $courseID)->get();

        return $this->sendResponse(courseLoginRequest::collection($loginRequest), 'CourseLoginRequests retrieved successfully.');
    }


    /**
     * @OA\Get (
     ** path="/api/courseLoginRequest/showRejectedLogs",
     *   tags={"CourseLoginRequests"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from  Rejected LoginRequest ",
     *   operationId="5-show Rejected LoginRequests from LoginRequest",
     *
     * description="Returns Rejected LoginRequest",
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

    public function showRejectedLoginRequest()
    {
        $state = 2;
        $loginRequest = CourseLoginRequests::where('state', $state)->get();

        return $this->sendResponse(courseLoginRequest::collection($loginRequest), 'CourseLoginRequests retrieved successfully.');
    }


    /**
     * @OA\Get(
     *      path="/api/courseLoginRequest/all",
     *      operationId="6-Get A List Of LoginRequest",
     *      tags={"CourseLoginRequests"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of CourseLoginRequests",
     *      description="Returns A List Of CourseLoginRequests",
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

    public function getAllCourseLoginRequests()
    {
        $loginRequest = CourseLoginRequests::all();
        return $this->sendResponse(courseLoginRequest::collection($loginRequest), 'CourseLoginRequests retrieved successfully.');

    }


    /**
     * @OA\Put   (
     ** path="/api/courseLoginRequest/acceptLoginRequest/{studentID}/{courseID}",
     *   tags={"CourseLoginRequests"},
     *   summary="Accept CourseLoginRequest for specific Student",
     *   operationId="7-Accept CourseLoginRequest for specific Student",
     *security={
     *  {"bearer_token":{}},
     *   },
     *
     *   @OA\Parameter(
     *     name="studentID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     *     @OA\Parameter(
     *     name="courseID",
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
    public function acceptCourseLoginRequest($student, $courseID)
    {
        $loginRequest = CourseLoginRequests::where('StudentID', $student)->where('courseID', $courseID)->first();
        if (is_null($loginRequest))
            return $this->sendError('CourseLoginRequests Not Found');
        else {
            $loginRequest->state = 3;
            $loginRequest->save();
            return $this->sendResponse(new courseLoginRequest($loginRequest), 'CourseLoginRequests Has been Accepted.');
        }
    }


    /**
     * @OA\Put   (
     ** path="/api/courseLoginRequest/rejectedLoginRequest/{studentID}/{courseID}",
     *   tags={"CourseLoginRequests"},
     *   summary=" Reject CourseLoginRequest for specific Student",
     *   operationId="7- Reject CourseLoginRequest for specific Student",
     *security={
     *  {"bearer_token":{}},
     *   },
     *
     *   @OA\Parameter(
     *     name="studentID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ), @OA\Parameter(
     *     name="courseID",
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
    public function rejectCourseLoginRequest($student, $courseID)
    {
        $loginRequest = CourseLoginRequests::where('StudentID', $student)->where('courseID', $courseID)->first();
        if (is_null($loginRequest))
            return $this->sendError('CourseLoginRequests Not Found');
        else {
            $loginRequest->state = 2;
            $loginRequest->save();
            return $this->sendResponse(new courseLoginRequest($loginRequest), 'CourseLoginRequests Has been Rejected.');
        }
    }

    /**
     * @OA\Get(
     *      path="/api/courseLoginRequest/showCourseByID/{courseID}",
     *      operationId="4-Show CourseLoginRequests By CoursesID",
     *      tags={"CourseLoginRequests"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get CourseLoginRequests By ID Courses ",
     *      description="Returns info spcific CourseLoginRequests",
     *     @OA\Parameter(
     *     name="courseID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
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
    public function getAllLoginRequestsByCourseID($courseID)
    {

        $loginRequests = CourseLoginRequests::where('courseID', $courseID)->get();

        return $this->sendResponse(courseLoginRequest::collection($loginRequests), 'CourseLoginRequests Has been Rejected.');
    }

    /**
     * @OA\Get(
     *      path="/api/courseLoginRequest/showStudentByID/{studentID}",
     *      operationId="4-Show CourseLoginRequests By studentID",
     *      tags={"CourseLoginRequests"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get CourseLoginRequests By ID studentID ",
     *      description="Returns info spcific CourseLoginRequests",
     *     @OA\Parameter(
     *     name="studentID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
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
    public function getAllLoginRequestsByStudentID($studentID)
    {
        $studentId = Student::where('userID', $studentID)->first();

        if (is_null($studentId)) {
            return $this->sendError('studentID Not Found');
        }

        $loginRequests = CourseLoginRequests::where('studentID', $studentId->id)->get();

        return $this->sendResponse(courseLoginRequest::collection($loginRequests), 'CourseLoginRequests Has been Rejected.');

    }


}


