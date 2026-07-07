<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function index(): View
    {
        return view('products.index', [
            'catalog' => $this->productService->getProducts(),
        ]);
    }

    public function show(string $slug): View
    {
        $product = $this->productService->getProduct($slug);

        abort_if($product === null, 404);

        return view('products.show', [
            'product' => $product,
            'catalog' => $this->productService->getProducts(),
        ]);
    }
}
