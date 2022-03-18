<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Resources\Json\JsonResource as EventResource;
use Illuminate\Support\Facades\Validator;

class EventController extends BaseController
{

    /**
     * @OA\Post(
     ** path="/api/event/add",
     *   tags={"Events"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Add Event",
     *   operationId="1-Add Event",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Event data",
     *    @OA\JsonContent(
     *       @OA\property(property="title", type="string", example="title"),
     *       @OA\Property(property="type", type="string", example="Football"),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="coachName", type="string", example="Ahmad"),
     *       @OA\Property(property="startDate", type="date", example="2020/02/01"),
     *       @OA\Property(property="endDate", type="date", example="2020/04/01"),
     *
     *      @OA\Property(property="groups", type="boolean", example=1),
     *      @OA\Property(property="cost", type="number", example=1000000),
     *      @OA\Property(property="currentNumber", type="number", example=0),
     *      @OA\Property(property="maxNumber", type="number", example=200),
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

    public function addEvent(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|string|unique:events',
                'type' => 'required|string',
                'location' => 'required|string',
                'coachName' => 'required|string',
                'startDate' => 'required|date_format:' .config('app.dateFormat'),
                'endDate' => 'required|date_format:' . config('app.dateFormat'),
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $event = new Event();
        $event->title = $request->title;
        $event->groups = isset($request->groups) ? $request->groups : null;
        $event->type = $request->type;
        $event->location = $request->location;
        $event->cost = isset($request->cost) ? $request->cost : null;
        $event->coachName = $request->coachName;
        $event->startDate = $request->startDate;
        $event->endDate = $request->endDate;
        $event->currentNumber = isset($request->currentNumber) ? $request->currentNumber : null;
        $event->maxNumber = isset($request->maxNumber) ? $request->maxNumber : null;
        $event->description = isset($request->description) ? $request->description : null;
        $event->save();
        return $this->sendResponse(new EventResource($event), 'Event Added successfully.');
    }

    /**
     * @OA\Delete  (
     ** path="/api/event/delete/{id}",
     *   tags={"Events"},
     *   summary="Delete from events ",
     *   operationId="2-delete",
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

    public function deleteEvent($id)
    {
        $event = Event::find($id);
        if (is_null($event)) {
            return $this->sendError('deleted Unsuccessfully.');
        } else {
            $event->delete();
            return $this->sendSuccess('deleted successfully.');
        }
    }


    /**
     * @OA\Put(
     ** path="/api/event/edit",
     *   tags={"Events"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="Edit Event",
     *   operationId="1-Edit Event",
     *   @OA\RequestBody(
     *    required=false,
     *    description="Pass Event data",
     *    @OA\JsonContent(
     *      @OA\Property(property="id", type="number", example=1),
     *       @OA\property(property="title", type="string", example="title"),
     *       @OA\Property(property="type", type="string", example="Football"),
     *       @OA\Property(property="location", type="string", example="Damas"),
     *       @OA\Property(property="coachName", type="string", example="Ahmad"),
     *       @OA\Property(property="startDate", type="date", example="2020/02/01"),
     *       @OA\Property(property="endDate", type="date", example="2020/04/01"),
     *
     *      @OA\Property(property="groups", type="boolean", example=1),
     *      @OA\Property(property="cost", type="number", example=1000000),
     *      @OA\Property(property="currentNumber", type="number", example=0),
     *      @OA\Property(property="maxNumber", type="number", example=200),
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
    public function editEvent(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|string',
                'type' => 'required|string',
                'location' => 'required|string',
                'coachName' => 'required|string',
                'startDate' => 'required|date_format:' . config('app.dateFormat'),
                'endDate' => 'required|date_format:' . config('app.dateFormat')
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }
        $event = Event::where('id', $request->id)->first();
        if (is_null($event)) {
            return $this->sendError('Event Not Found');
        } else {
            $event->title = $request->title;
            $event->groups = isset($request->groups) ? $request->groups : null;
            $event->type = $request->type;
            $event->location = $request->location;
            $event->cost = isset($request->cost) ? $request->cost : null;
            $event->coachName = $request->coachName;
            $event->startDate = $request->startDate;
            $event->endDate = $request->endDate;
            $event->currentNumber = isset($request->currentNumber) ? $request->currentNumber : null;
            $event->maxNumber = isset($request->maxNumber) ? $request->maxNumber : null;
            $event->description = isset($request->description) ? $request->description : null;
            $event->save();
            return $this->sendResponse(new EventResource($event), 'The Edit has been Done.');
        }

    }

    /**
     * @OA\Get (
     ** path="/api/event/showByID/{id}",
     *   tags={"Events"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *   summary="show from event ",
     *   operationId="4-Show Event From Events",
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
    public function showEventByID($id)
    {
        $event = Event::find($id);
        if (is_null($event))
            return $this->sendError('Event Not Found ');
        return $this->sendResponse($event, 'We Found it.');
    }

    /**
     * @OA\Get(
     *      path="/api/event/all",
     *      operationId="5-Get A List Of Events",
     *      tags={"Events"},
     * security={
     *  {"bearer_token":{}},
     *   },
     *      summary="Get list of events",
     *      description="Returns A List Of Events",
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

    public function showEvents()
    {
        $event = Event::all();
        return $this->sendResponse(EventResource::collection($event), 'Event retrieved successfully.');

    }


}
