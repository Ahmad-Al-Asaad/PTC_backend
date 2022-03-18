<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as StudentResource;
use Illuminate\Support\Facades\Validator;

class StudentController extends BaseController
{

    /**
     * @OA\Post(
     ** path="/api/add",
     *   tags={"Students"},
     *   summary="Add Students",
     *   operationId="1-Add Students",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Students data",
     *    @OA\JsonContent(
     *       @OA\Property(property="userName", type="string", example="KAIS"),
     *       @OA\Property(property="firstName", type="string", example="kais"),
     *       @OA\Property(property="lastName", type="string", example="na"),
     *       @OA\Property(property="email", type="string", example="kais@gmail.com"),
     *       @OA\Property(property="password", type="number", example=123456789),
     *       @OA\Property(property="c_password", type="number", example=123456789),
     *
     *
     *      @OA\Property(property="specialization", type="string", example="Java"),
     *      @OA\Property(property="year", type="number", example=1000000),
     *      @OA\Property(property="image", type="string", example="11212"),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *      @OA\Property(property="studentInfo", type="string", example="csdcsd"),
     *      @OA\Property(property="collage", type="string", example=" UK "),
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


    public function addStudent(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|numeric',
                'c_password' => 'required|same:password|numeric',
                'specialization' => 'required|string',
                'year' => 'required|numeric',
                'image' => 'required|string',
                'studentInfo' => 'required|string',
                'collage' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $user = new User();
        $user->name = $request->firstName . $request->lastName;
        $user->user_name = $request->userName;
        $user->email = $request->email;
//        $user->password = $request->password;
//        $user->c_password = $request->c_password;
        $user->password = bcrypt($request->password);
        $user->c_password = bcrypt($request->c_password);
        $user->type = 2;
        $user->save();

        $student = new Student();
        $student->userID = $user->id;
        $student->firstName = $request->firstName;
        $student->lastName = $request->lastName;
        $student->specialization = $request->specialization;
        $student->location = $request->location;
        $student->year = $request->year;
        $student->image = isset($request['image']) ? $request['image'] : null;
        $student->collage = isset($request['collage']) ? $request['collage'] : null;
        $student->studentInfo = isset($request['studentInfo']) ? $request['studentInfo'] : null;
        $student->save();
        return $this->sendResponse(new StudentResource($student), 'Added successfully.');

    }

    /**
     * @OA\Put (
     ** path="/api/student/edit",
     *   tags={"Students"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Students",
     *   operationId="1-Edit Students",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Students data",
     *    @OA\JsonContent(
     *       @OA\property(property="studentID", type="number", example=1),
     *        @OA\property(property="userID", type="number", example=1),
     *       @OA\Property(property="userName", type="string", example="KAIS"),
     *       @OA\Property(property="firstName", type="string", example="kais"),
     *       @OA\Property(property="lastName", type="string", example="Na"),
     *       @OA\Property(property="email", type="string", example="kais@gmail.com"),
     *       @OA\Property(property="password", type="number", example=123456789),
     *       @OA\Property(property="c_password", type="number", example=123456789),
     *
     *
     *      @OA\Property(property="specialization", type="string", example="Java"),
     *      @OA\Property(property="year", type="number", example=1000000),
     *      @OA\Property(property="image", type="string", example="11212"),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *      @OA\Property(property="studentInfo", type="string", example="csdcsd"),
     *      @OA\Property(property="collage", type="string", example=" UK "),
     *      ),
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

    public function editStudent(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'studentID' => 'required|numeric',
                'userID' => 'required|numeric',
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|numeric',
                'c_password' => 'required|same:password|numeric',
                'specialization' => 'required|string',
                'year' => 'required|numeric',
                'image' => 'required|string',
                'studentInfo' => 'required|string',
                'collage' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $student = Student::where('id', $request->studentID)->first();
        if (is_null($student)) {
            return $this->sendError('Student Not Found');
        }
        $user = User::where('id', $request->userID)->first();
        if (is_null($user)) {
            return $this->sendError('UserID Dose\'nt exist');
        }

        $user->name = $request->firstName . $request->lastName;
        $user->user_name = $request->userName;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->c_password = $request->c_password;
        $user->save();

        $student->userID = $user->id;
        $student->firstName = $request->firstName;
        $student->lastName = $request->lastName;
        $student->specialization = $request->specialization;
        $student->location = $request->location;
        $student->year = $request->year;
        $student->image = isset($request['image']) ? $request['image'] : null;
        $student->collage = isset($request['collage']) ? $request['collage'] : null;
        $student->studentInfo = isset($request['studentInfo']) ? $request['studentInfo'] : null;
        $student->save();
        return $this->sendResponse(new StudentResource($student), 'Edit successfully.');


    }

    /**
     * @OA\Delete  (
     ** path="/api/student/delete/{id}",
     *   tags={"Students"},
     *   summary="Delete from Students ",
     *   operationId="3-Delete from Students",
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

    public function deleteStudent($id)
    {
        $student = Student::find($id);
        if (is_null($student)) {
            return $this->sendError('Student deleted Unsuccessfully.');
        } else {
            $student->delete();
            return $this->sendSuccess('Student deleted successfully.');
        }
    }

    /**
     * @OA\Get (
     ** path="/api/student/showByID/{id}",
     *   tags={"Students"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from Students ",
     *   operationId="4-show from Students",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *         type="number"
     *     )
     *   ),
     * description="Returns info spcific from LoginRequest",
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

    public function showStudentByID($id)
    {
        $loginRequest = Student::find($id);
        if (is_null($loginRequest))
            return $this->sendError(' StudentNot Found ');
        return $this->sendResponse($loginRequest, 'We Found it.');
    }


    /**
     * @OA\Get(
     *      path="/api/student/all",
     *      operationId="5-Get A List Of Students",
     *      tags={"Students"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of Students",
     *      description="Returns A List Of Students",
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
    public function getAllStudents()
    {
        $loginRequest = Student::all();
        return $this->sendResponse(StudentResource::collection($loginRequest), 'Student retrieved successfully.');

    }


}
