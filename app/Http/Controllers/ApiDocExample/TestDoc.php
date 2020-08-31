<?php

// get 範例
/**
 * @OA\Get(
 *     path="/api/path/to/api/route",
 *     tags={"get method"},
 *     summary="get",
 *     description="get",
 *     security={
 *          {
 *              "Bearer":{}
 *          }
 *      },
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         description="ID",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              allOf={
 *                  @OA\Schema(ref="#/components/schemas/getResponse")
 *              }
 *          )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity",
 *         @OA\JsonContent(ref="#/components/schemas/unprocessableEntity")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="ERROR",
 *         @OA\JsonContent(ref="#/components/schemas/serverError")
 *     )
 * )
 */

/**
 * @OA\Schema(
 *      schema="getResponse",
 *      @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(
 *              @OA\Property(
 *                  type="integer",
 *                  property="id",
 *                  description="ID"
 *              ),
 *              @OA\Property(
 *                  type="string",
 *                  property="name",
 *                  description="名稱"
 *              ),
 *              @OA\Property(
 *                  type="integer",
 *                  property="status",
 *                  description="是否啟用"
 *              )
 *          )
 *     )
 * )
 */

// post 範例

/**
 * @OA\Post(
 *     path="/api/path/to/api/route",
 *     tags={"post method"},
 *     summary="post",
 *     description="post",
 *     security={
 *          {
 *              "Bearer":{}
 *          }
 *      },
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                  @OA\Property(
 *                      property="id",
 *                      type="integer",
 *                      description="ID",
 *                  ),
 *                 @OA\Property(
 *                      property="name",
 *                      type="integer",
 *                      description="名稱",
 *                  ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(ref="#/components/schemas/postResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity",
 *         @OA\JsonContent(ref="#/components/schemas/unprocessableEntity")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="ERROR",
 *         @OA\JsonContent(ref="#/components/schemas/serverError")
 *     )
 * )
 */

/**
 * @OA\Schema(
 *      schema="postResponse",
 *      type="object",
 *      @OA\Property(
 *          type="string",
 *          property="data",
 *          description="response message"
 *      ),
 *      @OA\Property(
 *          type="string",
 *          property="uuid",
 *          description="response order uuid"
 *      )
 * )
 */
