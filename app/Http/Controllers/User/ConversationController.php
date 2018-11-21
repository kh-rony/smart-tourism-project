<?php

namespace App\Http\Controllers\User;

use App\Events\AddUser;
use App\Events\MarkRead;
use App\Events\MessageArrived;
use App\Events\NewConversation;
use App\Events\NewMessage;
use App\Events\RemoveConversation;
use App\Events\RemoveUser;
use App\Model\Messaging\Conversation;
use App\Model\Messaging\Message;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

class ConversationController extends Controller
{
    public function searchPeople($text)
    {
        $user = JWTAuth::parseToken()->toUser();
        $peoples = User::search($text)->get();
        $filteredPeoples = array();
        foreach ($peoples as $person) {
            if (!($user->friendsByMeBlockedByMe->contains($person) || $user->friendsToMeBlockedByMe->contains($person)
                || $user->friendsByMeBlockedMe->contains($person) || $user->friendsToMeBlockedMe->contains($person)
                || $person->id === $user->id)) {
                $person['name'] = $person->name;
                array_push($filteredPeoples, $person);
            }
        }
        return response()->json(['peoples' => $filteredPeoples], 200);
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->toUser();
        $conversations = array();

        foreach ($user->conversations as $conversation) {
            $conversation = Conversation::whereId($conversation->id)->with('users')->first();
            $conversation['unread_messages'] = $conversation->readers->where('user_id', '=', $user->id)
                ->where('read_at', '=', null)
                ->count();
            unset($conversation['readers']);
            array_push($conversations, $conversation);
        }

        return response()->json(['conversations' => $conversations], 200);
    }

    public function createConversation(Request $request)
    {
        $this->validate($request, [
            'users' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();
        $users = array();
        array_push($users, $user->id);

        foreach ($request['users'] as $userSlug) {
            $searchUser = $this->searchUser($userSlug);
            if ($searchUser)
                array_push($users, $searchUser->id);
            else return response()->json(['error' => 'Bad request'], 400);
        }

        $conversation = new Conversation;
        if ($request['name'])
            $conversation->name = $request['name'];
        $conversation->save();

        $conversation->users()->attach($users);

        $conversation = Conversation::whereId($conversation->id)->with('users')->first();
        $conversation['unread_messages'] = 0;

        foreach ($conversation->users as $conversationUser) {
            if ($conversationUser->slug !== $user->slug)
                broadcast(new NewConversation($conversation, $conversationUser->slug));
        }

        return response()->json(['conversation' => $conversation], 201);
    }

    public function show($id)
    {
        $user = JWTAuth::parseToken()->toUser();

        $conversation = $user->conversations->where('id', '=', $id)->first();
        if ($conversation) {
            $conversation = Conversation::whereId($conversation->id)->with('users', 'messages')->first();
            foreach ($conversation->messages as $message) {
                $message['readers'] = $message->readers;
                $message['user'] = $message->user;
            }
            foreach ($conversation->users as $conversationUser) {
                $conversationUser['name'] = $conversationUser->name;
            }
            return response()->json(['conversation' => $conversation], 200);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function addUser(Request $request)
    {
        $this->validate($request, [
            'users' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();

        $conversation = $user->conversations->where('id', '=', $request['id'])->first();
        if ($conversation) {
            $users = array();
            $addedUsers = array();
            foreach ($request['users'] as $userSlug) {
                $searchUser = $this->searchUser($userSlug);
                if ($searchUser && !$conversation->users->contains($searchUser->id) && $searchUser->id !== $user->id) {
                    array_push($users, $searchUser->id);
                    $searchUser['name'] = $searchUser->name;
                    array_push($addedUsers, $searchUser);
                } else return response()->json(['error' => 'Bad request'], 400);
            }
            $conversation->users()->attach($users);

            broadcast(new AddUser($addedUsers, $conversation->id))->toOthers();

            $conversation = Conversation::whereId($conversation->id)->with('users')->first();
            $conversation['unread_messages'] = 0;
            foreach ($addedUsers as $addedUser)
                broadcast(new NewConversation($conversation, $addedUser->slug));

            return response()->json(['addedUsers' => $addedUsers], 201);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function removeUser(Request $request)
    {
        $this->validate($request, [
            'users' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();

        $conversation = $user->conversations->where('id', '=', $request['id'])->first();

        if ($conversation) {
            $users = array();
            $removedUsers = array();
            foreach ($request['users'] as $userSlug) {
                $searchUser = $this->searchUser($userSlug);
                if ($searchUser && $conversation->users->contains($searchUser->id) && $searchUser->id !== $user->id) {
                    array_push($users, $searchUser->id);
                    array_push($removedUsers, $searchUser->slug);
                } else return response()->json(['error' => 'Bad request'], 400);
            }

            if ($conversation->users->count() - count($users) > 1) {
                $conversation->users()->detach($users);

                broadcast(new RemoveUser($removedUsers, $conversation->id))->toOthers();

                return response()->json(['message' => 'Users removed'], 201);
            } else return response()->json(['message' => 'You have two keep at least two users'], 406);
        } else return response()->json(['error' => 'Bad request'], 400);

    }

    public function leaveConversation(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();

        $conversation = $user->conversations->where('id', '=', $request['id'])->first();

        if ($conversation) {
            $conversation->users()->detach($user->id);

            if ($conversation->users->count() === 1) {
                foreach ($conversation->users as $conversationUser)
                    broadcast(new RemoveConversation($conversation->id, $conversationUser->slug));
                $conversation->delete();
            }
            else {
                $removedUsers = array();
                array_push($removedUsers, $user->slug);
                broadcast(new RemoveUser($removedUsers, $conversation->id));
            }
            return response()->json(['message' => 'You left successfully'], 201);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);
        $user = JWTAuth::parseToken()->toUser();

        $conversation = $user->conversations->where('id', '=', $request['id'])->first();

        if ($conversation) {
            $message = $user->messages()->create(['body' => $request['body']]);
            $message->conversation_id = $conversation->id;
            $message->save();

            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $imgUrl = $image->store('public/photos/Messaging/' . $message->id);
                    $message->photos()->create(['img_url' => $imgUrl]);
                }
            }

            foreach ($conversation->users as $conversationUser) {
                if ($conversationUser->id !== $user->id) {
                    $message->readers()->create(['user_id' => $conversationUser->id]);
                    broadcast(new MessageArrived($conversationUser->slug));
                }
            }

            $message = Message::whereId($message->id)->with('readers', 'user')->first();

            broadcast(new NewMessage($message, $conversation->id))->toOthers();

            return response()->json(['message' => $message], 201);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function markUnreadMessagesAsRead(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();

        $conversation = $user->conversations->where('id', '=', $request['id'])->first();

        if ($conversation) {
            $conversationReaders = $conversation->readers->where('read_at', '=', null)
                ->where('user_id', '=', $user->id);

            foreach ($conversationReaders as $reader) {
                $reader->read_at = Carbon::now();
                $reader->save();
            }

            broadcast(new MarkRead($conversation->id, $user->slug, Carbon::now()))->toOthers();

            $totalUnreadMessages = 0;
            foreach ($user->conversations as $conversation) {
                foreach ($conversation->readers as $reader) {
                    if ($reader->user_id === $user->id && !$reader->read_at)
                        $totalUnreadMessages++;
                }
            }

            return response()->json(['totalUnreadMessages' => $totalUnreadMessages], 201);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function totalUnreadMessages()
    {
        $user = JWTAuth::parseToken()->toUser();
        $totalUnreadMessages = 0;
        foreach ($user->conversations as $conversation) {
            foreach ($conversation->readers as $reader) {
                if ($reader->user_id === $user->id && !$reader->read_at)
                    $totalUnreadMessages++;
            }
        }

        return response()->json(['totalUnreadMessages' => $totalUnreadMessages], 200);
    }


    protected function searchUser($userSlug)
    {
        return User::whereSlug($userSlug)->first();
    }
}
