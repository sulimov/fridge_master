<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    protected BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @param BookRequest $request
     * @return JsonResponse
     */
    public function book(BookRequest $request): JsonResponse
    {
        try {
            $result = $this->bookService->store($request);
            return new JsonResponse($result);
        } catch (\Throwable $exception) {
            return new JsonResponse($exception->getMessage(), 422);
        }
    }
}
