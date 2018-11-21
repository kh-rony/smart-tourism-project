<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use JWTAuth;

class FriendController extends Controller
{
    public function searchNewPeople($text)
    {
        $user = JWTAuth::parseToken()->toUser();
        $peoples = User::search($text)->get();
        $filteredPeoples = array();
        foreach ($peoples as $person) {
            if (!($user->friendsRelationByMe->contains($person) || $user->friendsRelationToMe->contains($person)
                || $person->id === $user->id)) {
                $person['name'] = $person->name;
                array_push($filteredPeoples, $person);
            }
        }
        return response()->json(['peoples' => $filteredPeoples], 200);
    }

    public function getRandomNewPeople()
    {
        $user = JWTAuth::parseToken()->toUser();

        $allPeoples = User::all()->where('verified', '=', 1);

        $peoples = $allPeoples->random(User::all()->count() > 12 ? 12: User::all()->where('verified', '=', 1)->count());
        $filteredPeoples = array();
        foreach ($peoples as $person) {
            if (!($user->friendsRelationByMe->contains($person) || $user->friendsRelationToMe->contains($person)
                || $person->id === $user->id)) {
                $person['name'] = $person->name;
                array_push($filteredPeoples, $person);
            }
        }
        return response()->json(['peoples' => $filteredPeoples], 200);
    }

    public function searchFriend($text)
    {
        $user = JWTAuth::parseToken()->toUser();
        $peoples = User::search($text)->get();
        $friends = array();
        foreach ($peoples as $person) {
            if (($user->friendsByMe->contains($person) || $user->friendsToMe->contains($person))
                && $person->id !== $user->id) {
                $person['name'] = $person->name;
                array_push($friends, $person);
            }
        }
        return response()->json(['friends' => $friends], 200);
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->toUser();

        $friends = $user->friendsByMe;
        $friends = $friends->merge($user->friendsToMe);
        $sentRequests = $user->sentFriendRequests;
        $receivedRequests = $user->receivedFriendRequests;
        $blockedByMe = $user->friendsByMeBlockedByMe;
        $blockedByMe = $blockedByMe->merge($user->friendsToMeBlockedByMe);
        $blockedMe = $user->friendsByMeBlockedMe;
        $blockedMe = $blockedMe->merge($user->friendsToMeBlockedMe);

        return response()
            ->json([
                'friends' => $friends,
                'sentRequests' => $sentRequests,
                'receivedRequests' => $receivedRequests,
                'blockedByMe' => $blockedByMe,
                'blockedMe' => $blockedMe
            ], 200);
    }

    public function addFriendRequest(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $friend = $this->findUser($request);

        if ($friend && !$user->friendsRelationByMe->contains($friend) && !$user->friendsRelationToMe->contains($friend)) {
            $success = $user->addFriend($friend);
            if ($success)
                return response()->json(['message' => 'Request sent'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        }
        else return response()->json(['error' => 'Bad request'], 400);

    }

    public function acceptFriendRequest(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $friend = $this->findUser($request);

        if ($friend  && !$user->friendsRelationByMe->contains($friend) && $user->friendsRelationToMe->contains($friend)) {
            $success = $user->acceptFriend($friend);
            if ($success)
                return response()->json(['message' => 'Request accepted'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        }
        else return response()->json(['error' => 'Bad request'], 400);
    }

    public function denyFriendRequest(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $friend = $this->findUser($request);

        if ($friend  && !$user->friendsRelationByMe->contains($friend) && $user->friendsRelationToMe->contains($friend)) {
            $success = $user->denyFriend($friend);
            if ($success)
                return response()->json(['message' => 'Denied'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        }
        else return response()->json(['error' => 'Bad request'], 400);
    }

    public function cancelFriendRequest(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $friend = $this->findUser($request);

        if ($friend  && $user->friendsRelationByMe->contains($friend) && !$user->friendsRelationToMe->contains($friend)) {
            $success = $user->cancelFriend($friend);
            if ($success)
                return response()->json(['message' => 'Request sent'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        }
        else return response()->json(['error' => 'Bad request'], 400);
    }

    public function unfriendRequest(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $friend = $this->findUser($request);

        if ($friend  && ( $user->friendsByMe->contains($friend) || $user->friendsToMe->contains($friend) )) {
            $success = $user->unfriend($friend);
            if ($success)
                return response()->json(['message' => 'Request sent'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        }
        else return response()->json(['error' => 'Bad request'], 400);
    }

    public function blockFriendRequest(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $friend = $this->findUser($request);

        if ($friend  && ( $user->friendsByMe->contains($friend) || $user->friendsToMe->contains($friend) )) {
            $success = $user->blockFriend($friend);
            if ($success)
                return response()->json(['message' => 'Request sent'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        }
        else return response()->json(['error' => 'Bad request'], 400);
    }

    public function unblockFriendRequest(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $friend = $this->findUser($request);

        if ($friend  && ( $user->friendsRelationByMe->contains($friend) || $user->friendsRelationToMe->contains($friend) )) {
            $success = $user->unblockFriend($friend);
            if ($success)
                return response()->json(['message' => 'Request sent'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        }
        else return response()->json(['error' => 'Bad request'], 400);
    }

    public function countFriends()
    {
        $user = JWTAuth::parseToken()->toUser();

        return response()->json(['totalFriends' => $user->friendsByMe->count() + $user->friendsToMe->count()], 200);
    }



    protected function findUser($request)
    {
        $this->validate($request, [
            'user' => 'required'
        ]);

        return User::whereSlug($request['user'])->first();
    }

}
