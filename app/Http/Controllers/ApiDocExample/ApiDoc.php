<?php

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="gm98backend-Api",
 *      description="GM9988後台 API"
 * )
 */

// auth key
/**
 * @OA\SecurityScheme(
 *      securityScheme="Bearer",
 *      in="header",
 *      name="Authorization",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Authorization"
 * ),
 */

// login
/**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"login method"},
 *     summary="user login",
 *     description="user login",
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
 *                      property="username",
 *                      type="string",
 *                      description="username",
 *                  ),
 *                 @OA\Property(
 *                      property="password",
 *                      type="string",
 *                      description="password",
 *                  ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              @OA\property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(
 *                      type="object",
 *                      property="user",
 *                      description="user data",
 *                      @OA\Property(
 *                          type="string",
 *                          property="identity",
 *                          description="identity"
 *                      ),
 *                      @OA\Property(
 *                          type="string",
 *                          property="username",
 *                          description="username"
 *                      ),
 *                      @OA\Property(
 *                          type="string",
 *                          property="nickname",
 *                          description="nickname"
 *                      ),
 *                      @OA\Property(
 *                          type="string",
 *                          property="image",
 *                          description="image"
 *                      )
 *                  )
 *             ),
 *             @OA\Property(
 *                 type="string",
 *                 property="token",
 *                 description="token"
 *             )
 *         )
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


// http status 400
/**
 * @OA\Schema(
 *      schema="badRequest",
 *      type="object",
 *      @OA\Property(
 *          type="string",
 *          property="error",
 *          description="錯誤訊息"
 *      )
 * )
 */

// http status 422
/**
 * @OA\Schema(
 *      schema="unprocessableEntity",
 *      oneOf={
 *          @OA\Schema(
 *              @OA\Property(
 *                  type="object",
 *                  property="error",
 *                  description="validate error",
 *                  allOf={
 *                      @OA\Schema(
 *                          @OA\Property(
 *                              property="column_name",
 *                              type="array",
 *                              @OA\Items(type="string")
 *                          )
 *                      )
 *                  }
 *              )
 *          ),
 *          @OA\Schema(
 *              @OA\Property(
 *                  type="string",
 *                  property="error",
 *                  description="validate error"
 *              )
 *          ),
 *      }
 * )
 */

 // http status 500
 /**
 * @OA\Schema(
 *      schema="serverError",
 *      type="object",
 *      @OA\Property(
 *          type="string",
 *          property="error",
 *          description="錯誤訊息"
 *      ),
 * )
 */
