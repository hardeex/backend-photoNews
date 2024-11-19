<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use App\Models\Category;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;




class NewsPostController extends Controller
{



    public function submitCategory(Request $request)
    {
        Log::info('Create Category Request Received', ['request' => $request->all()]);

        try {
            // Authenticate the user via JWT
            $user = JWTAuth::parseToken()->authenticate();

            // Validate request parameters and assign to $validated
            $validated = $request->validate([
                'category_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:categories,id',
            ]);

            // Access validated data (if needed)
            $categoryName = $validated['category_name'];
            $description = $validated['description'];
            $parentId = $validated['parent_id'];

            // Check if category already exists (case-insensitive)
            $existingCategory = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();

            if ($existingCategory) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category already exists',
                ], 409); // 409 Conflict
            }

            // Create the new category with created_by
            $category = Category::create([
                'name' => $categoryName,
                'description' => $description,
                'parent_id' => $parentId,
                'created_by' => $user->id, // Add the authenticated user's ID
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully.',
                'data' => $category,
            ], 201); // 201 Created

        } catch (JWTException $e) {
            // Handle any JWT-related errors, like expired or invalid token
            Log::error('JWT Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Token is invalid or expired. Please log in again.',
            ], 401); // 401 Unauthorized
        } catch (TokenExpiredException $e) {
            // Handle specific case when token has expired
            Log::error('Token Expired: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Your session has expired. Please log in again.',
            ], 401); // 401 Unauthorized
        } catch (TokenInvalidException $e) {
            // Handle case when token is invalid
            Log::error('Token Invalid: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid token. Please log in again.',
            ], 401); // 401 Unauthorized
        } catch (\Exception $e) {
            // Generic error handling for unexpected issues
            Log::error('Error creating category: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating category.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500); // 500 Internal Server Error
        }
    }

    public function listCategories(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 50);
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');

            // Paginate the categories based on per_page and sort parameters
            $categories = Category::orderBy($sortBy, $sortOrder)->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Categories fetched successfully.',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching categories.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
