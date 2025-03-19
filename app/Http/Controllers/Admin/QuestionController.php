<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::paginate(PAGINATION_COUNT);
        return view('admin.questions.index', ['questions' => $questions]);
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        $question = new Question();
        $question->question = $request->input('question');
        $question->answer = $request->input('answer');
        $question->save();

        return redirect()->route('questions.index',);
    }

    public function edit($id)
    {
        $data = Question::findOrFail($id);
        return view('admin.questions.edit', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->question = $request->input('question');
        $question->answer = $request->input('answer');
        $question->save();

        return redirect()->route('questions.index',);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions.index');
    }
}
