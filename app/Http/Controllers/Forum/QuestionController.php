<?php

namespace App\Http\Controllers\Forum;

use App\Events\NewQuestion;
use App\Model\Forum\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.jwt')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index()
    {
        return response()->json(['questions' => Question::with('user')->latest()->get()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();
        if ($user) {
            $question = Question::create([
                'title' => $request['title'],
                'body' => $request['body']
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $imgUrl = $image->store('public/photos/forum');
                    $question->photos()->create(['img_url' => $imgUrl]);
                }
            }

            $question->user_id = $user->id;
            $question->save();

            $question = Question::whereId($question->id)->with('photos')->first();

            broadcast(new NewQuestion($question))->toOthers();

            return response()->json(['question' => $question], 201);
        }
        return response()->json(['error' => 'Bad request'], 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::whereId($id)->with('user', 'photos')->first();
        if ($question)
            return response()->json(['question' => $question], 200);
        else return response()->json(['error' => 'Not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
