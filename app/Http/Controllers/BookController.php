<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class BookController extends Controller
{

    // BOOK LIST

    public function index()
    {
        return view('books-list');
    }

    // ADD NEW BOOK
    public function addBook(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'title' => 'required|unique:books',
            'author' => 'required',
        ]);

        if (!$validator->passes()) {

            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $book = Book::create($request->all());

            if (!$book) {
                return response()->json(['code' => 0, 'msg' => 'Someting went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'New Book has beem successfully saved']);
            }
        }
    }


    // GET ALL BOOKS
    public function getBooksList()
    {
        $books = Book::all();

        return DataTables::of($books)
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editBookBtn">Update</button>
                <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteBookBtn">Delete</button>
          </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    // GET BOOK DETAILS
    public function getBookDetails(Request $request)
    {
        $book_id = $request->book_id;
        $bookDetails = Book::find($book_id);
        return response()->json(['details' => $bookDetails]);
    }


    // UPDATE BOOK DETAILS
    public function updateBookDetails(Request $request)
    {
        $book_id = $request->bid;

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:books,title,' . $book_id,
            'author' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $book = Book::find($book_id);

            $book->update($request->all());

            if ($book) {
                return response()->json(['code' => 1, 'msg' => 'Book Details have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }


    // DELETE BOOK RECORD
    public function deleteBook(Request $request)
    {
        $book_id = $request->book_id;
        $book = Book::find($book_id);
        $book->delete();

        if ($book) {
            return response()->json(['code' => 1, 'msg' => 'Book has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
