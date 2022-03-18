<?php

namespace App\Http\Controllers\API;

use App\Models\CourseQuestion;
use App\Models\Courses;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Course as CourseResource;
use App\Models\Student;
use App\Models\Trainer;
use Illuminate\Http\Resources\Json\JsonResource as CourseQuestionAnswerResource;
use Illuminate\Http\Resources\Json\JsonResource as QuestionResource;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\Course;

use Illuminate\Http\Request;

class CoursesController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/course/add",
     *   tags={"Courses"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Course",
     *   operationId="1-Add Course",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Course data",
     *    @OA\JsonContent(
     *       @OA\property(property="title", type="string", example="title"),
     *       @OA\Property(property="state", type="number", example=1),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="startTime", type="time", example="00:00:00"),
     *       @OA\Property(property="endTime", type="time", example="00:00:00"),
     *       @OA\Property(property="startDate", type="date", example="2020/01/20"),
     *       @OA\Property(property="endDate", type="date", example="2020/03/20"),
     *
     *      @OA\Property(property="coachID", type="number", example=1),
     *      @OA\Property(property="Duration", type="number", example=150),
     *      @OA\Property(property="maxStudents", type="number", example=200),
     *      @OA\Property(property="CurrentStudents", type="number", example=0),
     *      @OA\Property(property="cost", type="number", example=10000000),
     *      @OA\Property(property="description", type="string", example="   "),
     *
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

    public function addCourse(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(),
            [
                'title' => 'required|string|unique:courses',
                'location' => 'required|string',
                'coachID' => 'required|numeric',
                'state' => 'required|numeric',
                'startTime' => 'required',
                'endTime' => 'required',
                'startDate' => 'required|dateFormat:' . config('app.dateFormat'),
                'endDate' => 'required|date_format:' . config('app.dateFormat'),
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $coachId = Trainer::where('id', $request->coachID)->first();
        if (is_null($coachId)) {
            return $this->sendError('coachID Not Found');
        }
        $course = new Courses();
        $course->state = $request->state;
        $course->title = $request->title;
        $course->coachID = $request->coachID;
        $course->location = $request->location;
        $course->Duration = isset($request->Duration) ? $request->Duration : null;
        $course->startTime = $request->startTime;
        $course->endTime = $request->endTime;
        $course->startDate = $request->startDate;
        $course->endDate = $request->endDate;
        $course->maxStudents = isset($request->maxStudents) ? $request->maxStudents : null;
        $course->CurrentStudents = isset($request->CurrentStudents) ? $request->CurrentStudents : null;
        $course->cost = isset($request->cost) ? $request->cost : null;
        $course->description = isset($request->description) ? $request->description : null;
        $course->save();

        return $this->sendResponse(new CourseResource($course), 'Added successfully.');
    }

    /**
     * @OA\Delete  (
     ** path="/api/course/delete/{id}",
     *   tags={"Courses"},
     *   summary="Delete from course ",
     *   operationId="2-Delete From Course",
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
    public function deleteCourse($id)
    {
        $course = Courses::find($id);
        if (is_null($course)) {
            return $this->sendError('Course Not Found');
        } else {
            $course->state = '3';
            $course->save();
            return $this->sendResponse($course, 'Delete Course has been done.');
        }
    }

    /**
     * @OA\Put(
     ** path="/api/course/edit",
     *   tags={"Courses"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Course",
     *   operationId="3-Edit Course",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Course data",
     *    @OA\JsonContent(
     *       @OA\property(property="id", type="number", example=1),
     *       @OA\property(property="title", type="string", example="title"),
     *       @OA\Property(property="state", type="number", example=1),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="startTime", type="time", example="00:00:00"),
     *       @OA\Property(property="endTime", type="time", example="00:00:00"),
     *       @OA\Property(property="startDate", type="date", example="2020/01/20"),
     *       @OA\Property(property="endDate", type="date", example="2020/03/20"),
     *
     *      @OA\Property(property="coachID", type="number", example=1),
     *      @OA\Property(property="Duration", type="number", example=150),
     *      @OA\Property(property="maxStudents", type="number", example=200),
     *      @OA\Property(property="CurrentStudents", type="number", example=0),
     *      @OA\Property(property="cost", type="number", example=10000000),
     *      @OA\Property(property="description", type="string", example="   "),
     *
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
    public function editCourse(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|string',
                'location' => 'required|string',
                'coachID' => 'required|numeric',
                'state' => 'required',
                'startTime' => 'required',
                'endTime' => 'required',
                'startDate' => 'required|dateFormat:' . config('app.dateFormat'),
                'endDate' => 'required|date_format:' . config('app.dateFormat'),
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $course = Courses::find($request->id);
        if (is_null($course)) {
            return $this->sendError('Course Not Found');
        }
        $coachId = Trainer::find($request->coachID);
        if (is_null($coachId)) {
            return $this->sendError('coachID Not Found');
        }
        $course->state = $request->state;
        $course->title = $request->title;
        $course->coachID = $request->coachID;
        $course->location = $request->location;
        $course->Duration = isset($request->Duration) ? $request->Duration : null;
        $course->startTime = $request->startTime;
        $course->endTime = $request->endTime;
        $course->startDate = $request->startDate;
        $course->endDate = $request->endDate;
        $course->maxStudents = isset($request->maxStudents) ? $request->maxStudents : null;
        $course->CurrentStudents = isset($request->CurrentStudents) ? $request->CurrentStudents : null;
        $course->cost = isset($request->cost) ? $request->cost : null;
        $course->description = isset($request->description) ? $request->description : null;

        $course->save();
        return $this->sendResponse(new CourseResource($course), 'The Edit has been Done.');


    }

    /**
     * @OA\Get(
     *      path="/api/course/showByID/{id}",
     *      operationId="4-Show Course From Courses By ID",
     *      tags={"Courses"},
     *     security={
     *     {"bearer_token":{}},
     *     },
     *      summary="Get Course From Courses By ID",
     *      description="Returns info spcific course",
     *     @OA\Parameter(
     *     name="id",
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
    public function showCourseByID($id)
    {
        $course = Courses::find($id);
        if (is_null($course)) {
            return $this->sendError('Not Found ');
        }
        return $this->sendResponse(new CourseResource($course), 'We Found it.');
    }


    /**
     * @OA\Get(
     *      path="/api/course/all",
     *      operationId="5-Get A list Of Courses",
     *      tags={"Courses"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get a list of courses",
     *      description="Returns a list of courses",
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

    public function getAllCourses()
    {
        $course = Courses::all();
        return $this->sendResponse(CourseResource::collection($course), 'Courses retrieved successfully.');
    }

    /**
     * @OA\Put   (
     ** path="/api/course/changeState/{id}/{state}",
     *   tags={"Courses"},
     *   summary="Change Course State",
     *   operationId="6-Change Course State",
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
    public function changeState($id, $state)
    {
        $course = Courses::find($id);
        if (is_null($course)) {
            return $this->sendError('Not Found');
        } else {
            $course->state = $state;
            $course->save();
            return $this->sendResponse(new CourseResource($course), 'State Has been Changed.');
        }
    }

    /**
     * @OA\Get(
     *      path="/api/course/courseQuestionAnswers/{id}",
     *      operationId="6-Get All Course Question And Questions Answers",
     *      tags={"Courses"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get All Course Question And It's Questions Answers",
     *      description="Returns All Course Question And Questions Answers",
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
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

    public function getQuestionAndItsAnswersQuestionsForSpecificCourse($id)
    {
        $course = Courses::find($id);
        if (is_null($course)) {
            return $this->sendError('Not Found');
        }
        return $this->sendResponse(new CourseResource($course), 'Course Retrieved successfully.');
    }
//
//    public function getAllStudentForSpecificCourse($courseID)
//    {
//        $student=Student::where('$courseID')
//    }
}

