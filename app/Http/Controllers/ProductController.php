<?php

namespace App\Http\Controllers;

use Validator;
use App\Import;
use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Traits\UploadFile;
use Cache;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    use UploadFile;

    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }

    public function index(Request $request)
    {
        $products = Product::with('category')->latest('id')->paginate(10);

        return ProductResource::collection($products);
    }

    function list(Request $request) {
        $this->authorize('viewAny', Product::class);

        $products = Product::with('category');

        if ($request->has('search')) {
            $products->where('name', 'LIKE', '%' . $request->get('search') . '%');
        }

        return ProductResource::collection($products->orderBy('name')->paginate(10));
    }

    public function show(Request $request, Product $product)
    {
        $this->authorize('view', $product);

        return response()->json([
            'status' => 'success',
            'data'   => $product->load('category'),
        ], 200);
    }

    public function edit(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'product'    => $product->load('category'),
                'categories' => $categories,
            ],
        ], 200);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $v = Validator::make($request->all(), [
            'name'        => 'required|string|min:3|max:64|unique:products,name,' . $product->id,
            'price'       => 'required|numeric|min:5',
            'description' => 'required|min:12',
            'category_id' => 'required|integer|exists:categories,id',
            'file'        => 'nullable|image|max:2048',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors(),
            ], 422);
        }

        try {

            $data = $request->except('file');

            if ($request->has('file')) {
                $image              = $this->handleUploadFile($request->file);
                $data['image_path'] = $image;
            }

            $product->fill($data);
            $product->save();

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'data'   => $product,
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $v = Validator::make($request->all(), [
            'name'        => 'required|string|min:3|max:64|unique:products,name',
            'price'       => 'required|numeric|min:5',
            'description' => 'required|min:12',
            'category_id' => 'required|integer|exists:categories,id',
            'file'        => 'required|image|max:2048',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors(),
            ], 422);
        }

        \DB::beginTransaction();

        try {

            $image = $this->handleUploadFile($request->file);

            $product = Product::create(array_merge($request->except('file'), ['image_path' => $image]));

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'data'   => $product,
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => 'error',
                'error'  => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function import(Request $request)
    {
        $this->authorize('import', Product::class);

        $v = Validator::make($request->all(), [
            'imported_file' => 'required|file|mimes:csv,txt',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors(),
            ], 422);
        }

        \DB::beginTransaction();

        try {
            $file = $this->handleUploadFile($request->imported_file, '/imports');

            $import = Import::create(['file' => $file, 'user_id' => \Auth::id()]);

            \DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Imported File: ' . $file . ' created',
            ]);

        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
