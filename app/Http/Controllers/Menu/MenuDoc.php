<?php

/**
 * @OA\Get(
 *     path="/api/menu",
 *     tags={"get method"},
 *     summary="get menu list",
 *     description="get menu list",
 *     security={
 *          {
 *              "Bearer":{}
 *          }
 *      },
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
 *                  @OA\Property(
 *                      type="string",
 *                      property="mainList",
 *                      description="mainList"
 *                  ),
 *                  @OA\Property(
 *                      oneOf={
 *                          @OA\Property(ref="#/components/schemas/subList"),
 *                          @OA\Property(ref="#/components/schemas/tabList"),
 *                          @OA\Property(
 *                              allOf={
 *                                  @OA\Property(ref="#/components/schemas/subList"),
 *                                  @OA\Property(ref="#/components/schemas/tabList"),
 *                              }
 *                          )
 *                      },
 *                  )
 *          )
 *     )
 * )
 */

/**
 * @OA\Schema(
 *      schema="subList",
 *      @OA\Property(
 *          property="subList",
 *          type="object",
 *          @OA\Property(
 *              type="string",
 *              property="permission name",
 *              description="permission name"
 *          ),
 *     )
 * )
 */

/**
 * @OA\Schema(
 *      schema="tabList",
 *      @OA\Property(
 *          property="tabList",
 *          type="object",
 *          oneOf={
 *              @OA\Property(
 *                  type="string",
 *                  property="permission name",
 *                  description="permission name"
 *              ),
 *              @OA\Property(
 *                  type="object",
 *                  property="permission name",
 *                  description="permission name",
 *                  @OA\Property(
 *                      type="string",
 *                      property="permission name",
 *                      description="permission name"
 *                  ),
 *              ),
 *          }
 *     )
 * )
 */
