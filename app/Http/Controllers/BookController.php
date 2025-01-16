<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use App\Events\AttachmentEvent;
use App\Http\Resources\BookResource;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected AttachmentService $attachmentService){}
    public function index()
    {
        $books = Book::with('images')->paginate(5);
        return $this->responsePagination($books,BookResource::collection($books));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookStoreRequest $request)
    {
        $book = Book::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        event(new AttachmentEvent($request->file('images'), $book->images()));
        return $this->success(new BookResource($book->load('images')));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return $this->success(new BookResource($book->load('images')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, string $id)
    {
        $book = Book::findOrFail($id);
        $book->update([
            'name'=> $request->name,
            'description'=> $request->description,
        ]);
        if ($request->has('images')) {
            if ($book->images()->exists()) {
                $this->attachmentService->destroy($book->images);
            }
            event(new AttachmentEvent($request->images, $book->images()));
        }
        return $this->success(new BookResource($book->load('images')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $this->attachmentService->destroy($book->images);
        $book->delete();
        return $this->success([]);
    }
}
