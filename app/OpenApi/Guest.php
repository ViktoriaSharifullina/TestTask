<?php

namespace App\OpenApi;

/**
 * @OA\Schema(
 *     schema="Guest",
 *     type="object",
 *     title="Guest",
 *     description="A guest record",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="country", type="string", example="US")
 * )
 */
class Guest
{
}

/**
 * @OA\Schema(
 *     schema="GuestInput",
 *     type="object",
 *     title="GuestInput",
 *     description="Input data for creating or updating a guest",
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *     @OA\Property(property="phone", type="string", example="+1234567890"),
 *     @OA\Property(property="country", type="string", example="US")
 * )
 */
class GuestInput
{
}
