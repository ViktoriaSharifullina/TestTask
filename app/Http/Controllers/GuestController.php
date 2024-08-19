<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Guest;
use App\Http\Services\GuestService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="Guest Service API",
 *     version="1.0.0",
 *     description="API documentation for Guest Service",
 * )
 */
class GuestController extends Controller
{
    private GuestService $guestService;

    public function __construct(GuestService $guestService)
    {
        $this->guestService = $guestService;
    }

    /**
     * @OA\Get(
     *     path="/api/guests",
     *     summary="List all guests",
     *     tags={"Guests"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Guest"))
     *     )
     * )
     */
    public function index(): Collection
    {
        return Guest::all();
    }

    /**
     * @OA\Post(
     *     path="/api/guests",
     *     summary="Create a new guest",
     *     tags={"Guests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GuestInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Guest created",
     *         @OA\JsonContent(ref="#/components/schemas/Guest")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Validation failed"
     *              ),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  additionalProperties={
     *                      "type": "array",
     *                      "items": {
     *                          "type": "string"
     *                      }
     *                  },
     *                  example={
     *                      "first_name": {"The first name field is required."},
     *                      "email": {"The email has already been taken."}
     *                  }
     *              )
     *          )
     *      )
     * )
     */
    public function store(StoreGuestRequest $request): JsonResponse
    {
        try {
            $guest = $this->guestService->createGuest($request->validated());
            return response()->json($guest, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/guests/{id}",
     *     summary="Get a single guest",
     *     tags={"Guests"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Guest ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Guest")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Guest not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $guest = Guest::findOrFail($id);
            return response()->json($guest, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Guest not found'], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/guests/{id}",
     *     summary="Update an existing guest",
     *     tags={"Guests"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Guest ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GuestInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guest updated",
     *         @OA\JsonContent(ref="#/components/schemas/Guest")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Validation failed"
     *              ),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  additionalProperties={
     *                      "type": "array",
     *                      "items": {
     *                          "type": "string"
     *                      }
     *                  },
     *                  example={
     *                      "first_name": {"The first name field is required."},
     *                      "email": {"The email has already been taken."}
     *                  }
     *              )
     *          )
     *      )
     * )
     */
    public function update(UpdateGuestRequest $request, $id): JsonResponse
    {
        try {
            $guest = Guest::findOrFail($id);
            $updatedGuest = $this->guestService->updateGuest($guest, $request->validated());
            return response()->json($updatedGuest, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/guests/{id}",
     *     summary="Delete a guest",
     *     tags={"Guests"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Guest ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Guest deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Guest not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $guest = Guest::find($id);

        if ($guest) {
            $guest->delete();
            return response()->json([
                'message' => 'Guest successfully deleted.'
            ], 200);
        }

        return response()->json([
            'message' => 'Guest not found.',
        ], 404);
    }
}
