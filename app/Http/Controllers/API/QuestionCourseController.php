<?php

namespace App\Http\Controllers\API;

use App\Models\CourseQuestion;
use App\Models\CourseQuestionAnswer;
use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
//use Illuminate\Http\Resources\Json\JsonResource as QuestionResource;
use App\Http\Resources\Question as QuestionResource;

class QuestionCourseController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/question/add",
     *   tags={"QuestionCourse"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Qusetion For Course",
     *   operationId="1- Add Qusetion For Course",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass Question Course data",
     *    @OA\JsonContent(
     *       @OA\Property(property="CourseID", type="number", example=1),
     *       @OA\Property(property="title", type="string", example="String"),
     *       @OA\Property(property="type", type="number", example=1),
     *       @OA\Property(property="required", type="boolean", example=true),
     *        @OA\Property(
     *                  property="questionsAnswers",
     *                  type="array" ,
     *                  @OA\Items(
     *                      @OA\Property(property="title", type="string", example="title12"),
     *                      @OA\Property(property="state", type="number", example=1),
     *                  ),
     *              ),
     * ),
     *
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
    public function addQuestion(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'CourseID' => 'required',
                'title' => 'required|unique:course_questions',
                'type' => 'required',
                'required' => 'required|boolean',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $exsit = Courses::where('id', $request->CourseID)->first();
        if (is_null($exsit)) {
            return $this->sendError('Courses Not Found');
        }

        $question = new CourseQuestion();
        $question->CourseID = $request->CourseID;
        $question->title = $request->title;
        $question->type = $request->type;
        $question->required = $request->required;
        $question->save();

        if (isset($request->questionsAnswers)) {
            foreach ($request->questionsAnswers as $key => $value) {
                $questionAnswer = new CourseQuestionAnswer();
                $questionAnswer->questionID = $question->id;
                $questionAnswer->title = $value['title'];
                $questionAnswer->state = $value['state'];
                $questionAnswer->save();
            }
        }
        return $this->sendResponse(new QuestionResource($question), 'Added successfully.');
    }


    /**
     * @OA\Put (
     ** path="/api/question/edit",
     *   tags={"QuestionCourse"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Question From Course Questions",
     *   operationId="2-Edit From Course Questions",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Pass Question Course data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="CourseID", type="string", example=1),
     *       @OA\Property(property="title", type="string", example="String"),
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
     * ),
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
    public function editQuestion(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'CourseID' => 'required',
                'title' => 'required|string',
                'type' => 'required',
                'required' => 'required|boolean',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $question = CourseQuestion::where('id', $request->id)->first();
        if (is_null($question)) {
            return $this->sendError('CourseQuestion Not Found');
        }
        $exsit = Courses::where('id', $request->CourseID)->first();
        if (is_null($exsit)) {
            return $this->sendError('Courses Not Found');
        }
        $question->CourseID = $request->CourseID;
        $question->title = $request->title;
        $question->type = $request->type;
        $question->required = $request->required;
        $question->save();

        if (isset($request->questionsAnswers)) {
            foreach ($request->questionsAnswers as $key => $value) {
                $questionAnswer = CourseQuestionAnswer::where('id', $value['id'])->first();
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

    /**
     * @OA\Delete  (
     ** path="/api/question/delete/{id}",
     *   tags={"QuestionCourse"},
     *   summary="Delete from course Question ",
     *   operationId="3-delete",
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

    public function deleteQuestion($id)
    {
        $question = CourseQuestion::find($id);
        if (is_null($question)) {
            return $this->sendError('Not Found');
        }
        $question->delete();
        return $this->sendSuccess('CourseQuestion deleted successfully.');
    }


    /**
     * @OA\Get(
     *      path="/api/question/showByID/{id}",
     *      operationId="4-Show All Questions Related On Specific Course",
     *      tags={"QuestionCourse"},
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
     *      summary="Get list of Questions Related On Specific Course",
     *      description="Returns list of Questions Related On Specific Course",
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
    public function showCourseQuestions($CourseID)
    {
        $question = CourseQuestion::where('CourseID', $CourseID)->get();

        return $this->sendResponse(QuestionResource::collection($question), 'We Found it.');

    }

    /**
     * @OA\Get(
     *      path="/api/question/all",
     *      operationId="5-Get List Of Questions",
     *      tags={"QuestionCourse"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get A list of Questions",
     *      description="Returns list of Questions",
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
    function getAllQuestions()
    {
        $question = CourseQuestion::all();

        return $this->sendResponse(QuestionResource::collection($question), 'CourseQuestion retrieved successfully.');
    }


}
