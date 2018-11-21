<?php

namespace App\Http\Controllers\Forum;

use App\Events\NewReply;
use App\Model\Forum\Reply;
use App\Model\Forum\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.jwt')->except( 'index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeQuestionReply(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();
        $question = Question::whereId($request['id'])->first();

        if ($question && $user) {

            $reply = $question->replies()->create([
                'body' => $request['body']
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $imgUrl = $image->store('public/photos/forum');
                    $reply->photos()->create(['img_url' => $imgUrl]);
                }
            }

            $reply->user_id = $user->id;
            $reply->save();

            $reply = Reply::whereId($reply->id)->with('user', 'photos')->first();
            $reply['parentReplyId'] = null;

            broadcast(new NewReply($reply, null, $request['questionId']))->toOthers();

            return response()->json(['reply' => $reply], 201);
        }
        else return response()->json(['error' => 'Bad Request'], 400);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();
        $existentReply = Reply::whereId($request['id'])->first();

        if ($existentReply && $user) {

            $reply = $existentReply->replies()->create([
                'body' => $request['body']
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $imgUrl = $image->store('public/photos/forum');
                    $reply->photos()->create(['img_url' => $imgUrl]);
                }
            }

            $reply->user_id = $user->id;
            $reply->save();

            $reply = Reply::whereId($reply->id)->with('user', 'photos')->first();
            $reply['parentReplyId'] = $existentReply->id;

            broadcast(new NewReply($reply, $existentReply->id, $request['questionId']))->toOthers();

            return response()->json(['reply' => $reply], 201);
        }
        else return response()->json(['error' => 'Bad Request'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::whereId($id)->first();
        if ($question) {
            $replies = $question->replies()->with('user','photos')->latest()->get();
            foreach ($replies as $reply)
                $this->showRecursiveReplies($reply);
            return response()->json(['replies' => $replies], 200);
        }
        return response()->json(['error' => 'Bad Request'], 400);
    }

    public function showRecursiveReplies($reply)
    {
        $replies = $reply->replies()->with('user', 'photos')->get();
        if ($replies->count()) {
            $reply['replies'] = $replies;
            foreach ($replies as $chainReply)
            {
                $this->showRecursiveReplies($chainReply);
            }
        }
        else return;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
