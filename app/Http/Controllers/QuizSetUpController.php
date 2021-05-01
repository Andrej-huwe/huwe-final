<?php

namespace App\Http\Controllers;

use App\QuizSetUp;
use Illuminate\Http\Request;

class QuizSetUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuizSetUp::latest() -> get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        QuizSetUp::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\QuizSetUp  $quizSetUp
     * @return \Illuminate\Http\Response
     */
    public function show(QuizSetUp $quizSetUp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\QuizSetUp  $quizSetUp
     * @return \Illuminate\Http\Response
     */
    public function edit(QuizSetUp $quizSetUp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QuizSetUp  $quizSetUp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuizSetUp $quizSetUp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\QuizSetUp  $quizSetUp
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuizSetUp $quizSetUp)
    {
        //
    }
}
