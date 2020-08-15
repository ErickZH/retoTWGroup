<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Notifications\NewComment;
use App\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $publications = Publication::with('comments')->get();

        return view('publications.index', compact('publications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('publications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
           'title' => 'required|string|max:255',
           'content' => 'required|string',
        ]);

        Publication::create([
            'title' => trim($request->input('title')),
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->to(route('publications.index'))->with('success', 'Publication successfully registered');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Publication  $publication
     * @return \Illuminate\View\View
     */
    public function show(Publication $publication)
    {
        return view('publications.show', compact('publication'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Publication  $publication
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comment(Request $request, Publication $publication)
    {
        $request->validate(['content' => 'required|string']);

        $comment = Comment::create([
            'publication_id' => $publication->id,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'status' => 'Approved',
        ]);

        $publication->author->notify(new NewComment($comment));

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
