<?php

namespace App\Http\Controllers\API;

use App\Models\CourseQuestion;
use App\Models\CourseQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Resources\Json\JsonResource as CourseQuestionAnswerResource;

class CourseQuestionAnswerController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/api/courseQuestionAnswer/add",
     *   tags={"CourseQuestionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Course Qusetion Answer For Question",
     *   operationId="1- Add Course Qusetion Answer For Question",
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
    public function addCourseQuestionAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'questionID' => 'required|numeric',
                'title' => 'required|string',
                'state' => 'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $exsit = CourseQuestion::where('id', $request->questionID)->first();
        if (is_null($exsit)) {
            return $this->sendError('questionID Found');
        }
        $answerQuestion = new CourseQuestionAnswer();
        $answerQuestion->questionID = $request->questionID;
        $answerQuestion->title = $request->title;
        $answerQuestion->state = $request->state;
        $answerQuestion->save();


        return $this->sendResponse(new CourseQuestionAnswerResource($answerQuestion), 'Added successfully.');

    }

    /**
     * @OA\Put(
     ** path="/api/courseQuestionAnswer/edit",
     *   tags={"CourseQuestionAnswer"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Course Qusetion Answer For Question",
     *   operationId="2- Edit Course Qusetion Answer For Question",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Question Answer  data",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="number", example=1),
     *       @OA\Property(property="questionID", type="number", example=1),
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
    public function editCourseQuestionAnswer(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'questionID' => 'required|numeric',
                'title' => 'required|string',
                'state' => 'required|numeric',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $answerQuestion = CourseQuestionAnswer::where('id', $request->id)->first();
        if (is_null($answerQuestion)) {
            return $this->sendError('CourseQuestionAnswer Found');
        }
        $exsit = CourseQuestion::where('id', $request->questionID)->first();
        if (is_null($exsit)) {
            return $this->sendError('questionID Found');
        }
        $answerQuestion->title = $request->title;
        $answerQuestion->questionID = $request->questionID;
        $answerQuestion->state = $request->state;
        $answerQuestion->save();
        return $this->sendResponse(new CourseQuestionAnswerResource($answerQuestion), 'The Edit has been Done.');

    }

    /**
     * @OA\Delete  (
     ** path="/api/courseQuestionAnswer/delete/{id}",
     *   tags={"CourseQuestionAnswer"},
     *   summary="Delete from Course Qusetion Answer ",
     *   operationId="3-Delete from Course Qusetion Answer",
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

    public function deleteCourseQuestionAnswer($id)
    {
        $answerQuestion = CourseQuestionAnswer::find($id);
        if (!is_null($answerQuestion)) {
            $answerQuestion->delete();
            return $this->sendSuccess('deleted successfully.');
        }
        return $this->sendError('Not Found');
    }

    /**
     * @OA\Get(
     *      path="/api/courseQuestionAnswer/showByID/{id}",
     *      operationId="4-Show All Answers Related On Specific Question",
     *      tags={"CourseQuestionAnswer"},
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
    public function showCourseQuestionAnswerById($questionID)
    {
        $answerQuestion = CourseQuestionAnswer::where('questionID', $questionID)->get();

        return $this->sendResponse(CourseQuestionAnswerResource::collection($answerQuestion), 'We Found it.');

    }

    /**
     * @OA\Get(
     *      path="/api/courseQuestionAnswer/all",
     *      operationId="5-Get List Of Questions Answers",
     *      tags={"CourseQuestionAnswer"},
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

    public function getAllCourseQuestionAnswer()
    {
        $answerQuestion = CourseQuestionAnswer::all();

        return $this->sendResponse(CourseQuestionAnswerResource::collection($answerQuestion), 'CourseQuestionAnswer retrieved successfully.');
    }


    /**
     * @OA\Put   (
     ** path="/api/courseQuestionAnswer/changeState/{id}/{state}",
     *   tags={"CourseQuestionAnswer"},
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
    public function changeStateCourseQuestionAnswerById($id, $state)
    {
        $answerQuestion = CourseQuestionAnswer::find($id);
        if (is_null($answerQuestion)) {
            return $this->sendError('CourseQuestionAnswer Found');
        }
        $answerQuestion->state = $state;
        $answerQuestion->save();
        return $this->sendResponse(new CourseQuestionAnswerResource($answerQuestion), 'State Has been Changed.');
    }

}
