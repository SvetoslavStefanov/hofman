<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller {

  /**
   * Store a newly created product in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request) {

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'price' => 'required|numeric|min:0',
      'sku' => 'required|string|max:255|unique:products',
      'category' => 'required|in:Home,Garden,Kitchen'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->errors(),
      ], 401);
    }

    $product = Product::create($validator->validated());

    return response()->json($product, 201);
  }

  /**
   * Display the specified product.
   *
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function show(Product $product) {
    return response()->json($product);
  }

  /**
   * Retrieve a paginated list of products.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request) {
    $products = Product::paginate($request->query('per_page', 10));

    return response()->json($products);
  }

  /**
   * Update the specified product in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, Product $product) {
    $validator = Validator::make($request->all(), [
      'name' => 'sometimes|required|string|max:255',
      'description' => 'nullable|string|max:1000',
      'price' => 'sometimes|required|numeric|min:0',
      'sku' => 'sometimes|required|string|max:255|unique:products,sku,' . $product->id,
      'category' => 'sometimes|required|in:Home,Garden,Kitchen'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'errors' => $validator->errors(),
      ], 401);
    }

    $product->update($validator->validated());

    return response()->json($product);
  }

  /**
   * Remove the specified product from storage.
   *
   * @param \App\Models\Product $product
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(Product $product) {
    $product->delete();

    return response()->json(['message' => 'Product deleted successfully.']);
  }
}
