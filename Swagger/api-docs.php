<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="PeAPIniere API",
 *         version="1.0.0",
 *         description="API for managing plants, orders, and categories",
 *         @OA\Contact(
 *             email="support@example.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000",
 *         description="Local server"
 *     )
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter JWT token in the format 'Bearer {token}'"
 * )
 */

/**
 * @OA\Tag(name="Authentication", description="User authentication endpoints")
 * @OA\Tag(name="Categories", description="Category management for admins")
 * @OA\Tag(name="Plants", description="Plant management and retrieval")
 */

/**
 * @OA\Post(
 *     path="/api/auth/login",
 *     tags={"Authentication"},
 *     summary="Login user and return JWT token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="email", type="string", example="admin@example.com"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbG..."),
 *             @OA\Property(property="token_type", type="string", example="bearer"),
 *             @OA\Property(property="expires_in", type="integer", example=3600)
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */

/**
 * @OA\Post(
 *     path="/api/admin/categories",
 *     tags={"Categories"},
 *     summary="Create a new category",
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="category_name", type="string", example="Herbes")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Category created",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="category_name", type="string", example="Herbes"),
 *             @OA\Property(property="created_at", type="string", example="2025-03-27T12:00:00Z"),
 *             @OA\Property(property="updated_at", type="string", example="2025-03-27T12:00:00Z")
 *         )
 *     ),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */

/**
 * @OA\Put(
 *     path="/api/admin/categories/{id}",
 *     tags={"Categories"},
 *     summary="Update a category",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="category_name", type="string", example="Herbes Aromatiques")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="category_name", type="string", example="Herbes Aromatiques"),
 *             @OA\Property(property="created_at", type="string", example="2025-03-27T12:00:00Z"),
 *             @OA\Property(property="updated_at", type="string", example="2025-03-27T12:01:00Z")
 *         )
 *     ),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Category not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */

/**
 * @OA\Delete(
 *     path="/api/admin/categories/{id}",
 *     tags={"Categories"},
 *     summary="Delete a category",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category deleted",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Category deleted")
 *         )
 *     ),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Category not found")
 * )
 */

/**
 * @OA\Get(
 *     path="/api/plants/{slug}",
 *     tags={"Plants"},
 *     summary="Retrieve a plant by slug",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="slug",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", example="basilic-aromatique")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Plant details",
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Basilic Aromatique"),
 *             @OA\Property(property="slug", type="string", example="basilic-aromatique"),
 *             @OA\Property(property="description", type="string", example="Herbe savoureuse"),
 *             @OA\Property(property="price", type="number", format="float", example=2.99),
 *             @OA\Property(property="images", type="array", @OA\Items(type="string", example="url")),
 *             @OA\Property(property="category_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Plant not found")
 * )
 */