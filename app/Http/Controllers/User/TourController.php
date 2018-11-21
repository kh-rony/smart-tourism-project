<?php

namespace App\Http\Controllers\User;

use App\Model\Tour;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

class TourController extends Controller
{
    public function index()
    {
        $user = JWTAuth::parseToken()->toUser();
        $organizedTours = $user->organizedTours;
        $requestedTours = $user->requestedTours;
        $interestedPublicTours = $user->interestedPublicTours;
        $publicTours = array();

        $tours = Tour::where('accept_public', '=', 1)->get();
        foreach ($tours as $tour) {
            if (!$tour->users->contains($user))
                array_push($publicTours, $tour);
        }

        foreach ($organizedTours as $tour) {
            $organizer = User::whereId($tour->user_id)->first();
            $tour['organizer'] = $organizer;
            $tour->mates;
            $tour->invitedMates;
            $tour->notGoingMates;
            $tour->requestedPublic;

            $tour->description = json_decode($tour->description);
        }

        foreach ($requestedTours as $tour) {
            $organizer = User::whereId($tour->user_id)->first();
            $tour['organizer'] = $organizer;
            $tour->mates;
            $tour->invitedMates;
            $tour->notGoingMates;
            $tour->requestedPublic;

            $tour->description = json_decode($tour->description);
        }

        foreach ($interestedPublicTours as $tour) {
            $organizer = User::whereId($tour->user_id)->first();
            $tour['organizer'] = $organizer;
            $tour->mates;
            $tour->invitedMates;
            $tour->notGoingMates;
            $tour->requestedPublic;

            $tour->description = json_decode($tour->description);
        }

        foreach ($publicTours as $tour) {
            $organizer = User::whereId($tour->user_id)->first();
            $tour['organizer'] = $organizer;
            $tour->mates;
            $tour->invitedMates;
            $tour->notGoingMates;
            $tour->requestedPublic;

            $tour->description = json_decode($tour->description);
        }

        return response()->json([
            'organizedTours' => $organizedTours,
            'invitedTours' => $requestedTours,
            'interestedPublicTours' => $interestedPublicTours,
            'publicTours' => $publicTours
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'origin' => 'required',
            'destination' => 'required',
            'checkIn' => 'required',
            'checkOut' => 'required',
            'name' => 'required',
            'days' => 'required',
            'person' => 'required',
            'places' => 'required',
            'selectedPlaces' => 'required',
        ]);

        $user = JWTAuth::parseToken()->toUser();

        $description = array();
        $description['origin'] = $request['origin'];
        $description['destination'] = $request['destination'];
        $description['checkIn'] = $request['checkIn'];
        $description['checkOut'] = $request['checkOut'];
        $description['days'] = $request['days'];
        $description['places'] = $request['places'];
        $description['selectedPlaces'] = $request['selectedPlaces'];
        $description = json_encode($description);

        $tour = Tour::create([
            'name' => $request['name'],
            'description' => $description,
            'user_id' => $user->id
        ]);

        $user->tours()->attach($tour->id, ['relation' => 1]);

        $user->goingTour($tour->id, $request['person']);

        return response()->json(['tour' => $tour], 201);
    }

    public function show($id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $tour = Tour::where('id', '=', $id)->first();
        if (!$tour || (!$tour->accept_public && !$tour->users->contains($user)))
            return response()->json(['error' => 'Bad request'], 400);
        else {
            $organizer = User::whereId($tour->user_id)->first();
            $tour['organizer'] = $organizer;
            $tour->mates;
            $tour->invitedMates;
            $tour->notGoingMates;
            $tour->requestedPublic;

            $tour->description = json_decode($tour->description);

            return response()->json(['tour' => $tour], 200);
        }

    }

    public function addMate(Request $request)
    {
        $this->validate($request, [
            'users' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();
        $tour = $user->matesOfTour->where('id', $request['id'])->first();
        if ($tour) {
            $users = array();
            $addedUsers = array();
            foreach ($request['users'] as $userSlug) {
                $searchUser = $this->findUser($userSlug);
                if ($searchUser && !$tour->users->contains($searchUser->id)) {
                    array_push($users, $searchUser->id);
                    $searchUser['name'] = $searchUser->name;
                    array_push($addedUsers, $searchUser);
                } else return response()->json(['error' => 'Bad request'], 400);
            }
            $tour->users()->attach($users, ['relation' => 1]);
            return response()->json(['addedUsers' => $addedUsers], 201);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function addPublicMate(Request $request, $id)
    {
        $this->validate($request, [
            'person' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();
        $tour = Tour::whereId($id)->where('accept_public', '=', 1)->first();
        if ($tour && !$tour->users->contains($user->id)) {
            $tour->users()->attach($user, ['person' => $request['person']]);
            return response()->json(['message' => 'Request added'], 201);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function acceptMateRequest(Request $request)
    {
        $this->validate($request, [
            'person' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();
        $tour = $user->tours->where('id', $request['id'])->first();
        if ($tour) {
            $success = $user->goingTour($tour->id, $request['person']);
            if ($success)
                return response()->json(['message' => 'Request accepted'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function acceptPublicMateRequest(Request $request, $id)
    {
        $this->validate($request, [
            'user' => 'required'
        ]);
        $user = JWTAuth::parseToken()->toUser();
        $tour = $user->tours->where('id', '=', $id)->first();
        $publicUser = $this->findUser($request['user']);
        if ($tour && $publicUser) {
            $tour->users()->updateExistingPivot($publicUser, ['going' => 2]);
            return response()->json(['message' => 'Public mate added'], 201);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function denyMateRequest(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $tour = $user->tours->where('id', $request['id'])->first();
        if ($tour) {
            $success = $user->notGoingTour($tour->id);
            if ($success)
                return response()->json(['message' => 'Denied'], 201);
            else return response()->json(['error' => 'Something went wrong, try later'], 500);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function denyPublicMateRequest(Request $request, $id)
    {
        $this->validate($request, [
            'user' => 'required'
        ]);
        $user = JWTAuth::parseToken()->toUser();
        $tour = $user->tours->where('id', '=', $id)->first();
        $publicUser = $this->findUser($request['user']);
        if ($tour && $publicUser) {
            $tour->users()->updateExistingPivot($publicUser, ['going' => 1]);
            return response()->json(['message' => 'Public mate denied'], 201);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function makeTourPublic($id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $tour = $user->tours->where('id', $id)->first();
        if ($tour->user_id === $user->id) {
            $tour->accept_public = 1;
            $tour->save();
            return response()->json(['message' => 'Tour is now public']);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    public function searchFriend($id, $text)
    {
        $user = JWTAuth::parseToken()->toUser();
        $tour = $user->tours->where('id', $id)->first();
        if ($tour) {
            $peoples = User::search($text)->get();
            $friends = array();
            foreach ($peoples as $person) {
                if (($user->friendsByMe->contains($person) || $user->friendsToMe->contains($person))
                    && $person->id !== $user->id && !$tour->users->contains($person)) {
                    $person['name'] = $person->name;
                    array_push($friends, $person);
                }
            }
            return response()->json(['friends' => $friends], 200);
        } else return response()->json(['error' => 'Bad request'], 400);
    }

    protected function findUser($userSlug)
    {
        return User::whereSlug($userSlug)->first();
    }
}
