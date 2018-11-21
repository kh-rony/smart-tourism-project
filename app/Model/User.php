<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasSlug;
    use Searchable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'gender', 'phone', 'email', 'password', 'provider', 'provider_id', 'email_token', 'slug', 'occupation'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'phone', 'email_token', 'provider', 'provider_id', 'verified', 'updated_at',
    ];

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function searchableAs()
    {
        return 'users_index';
    }

    public function getScoutKey()
    {
        return $this->slug;
    }

    public function toSearchableArray()
    {
        $array = $this->only('name', 'email');

        // Customize array...
        $array['id'] = $this->id;
        $array['email'] = $this->email;

        return $array;
    }

    public function questions()
    {
        return $this->hasMany('App\Model\Forum\Question');
    }

    public function replies()
    {
        return $this->hasMany('App\Model\Forum\Reply');
    }

    /**
     *  friends relations
     */
    public function friendsRelationByMe()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'user_id', 'friend_id')->withTimestamps();
    }

    public function friendsRelationToMe()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'friend_id', 'user_id')->withTimestamps();
    }

    //friends
    public function friendsByMe()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'user_id', 'friend_id')
            ->wherePivot('status', '=', '1');
    }

    public function friendsToMe()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'friend_id', 'user_id')
            ->wherePivot('status', '=', 1);
    }

    //end friends

    public function sentFriendRequests()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'user_id', 'friend_id')
            ->wherePivot('status', '=', 0);
    }

    public function receivedFriendRequests()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'friend_id', 'user_id')
            ->wherePivot('status', '=', 0);
    }

    //blocked friends by me
    public function friendsByMeBlockedByMe()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'user_id', 'friend_id')
            ->wherePivot('status', '=', '3')
            ->wherePivot('impactor_id', '=', $this->id);
    }

    public function friendsToMeBlockedByMe()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'friend_id', 'user_id')
            ->wherePivot('status', '=', '3')
            ->wherePivot('impactor_id', '=', $this->id);
    }
    //end blocked friends by me

    //friends blocked me
    public function friendsByMeBlockedMe()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'user_id', 'friend_id')
            ->wherePivot('status', '=', '3')
            ->wherePivot('impactor_id', '<>', $this->id);
    }

    public function friendsToMeBlockedMe()
    {
        return $this->belongsToMany('App\Model\User', 'friend_user', 'friend_id', 'user_id')
            ->wherePivot('status', '=', '3')
            ->wherePivot('impactor_id', '<>', $this->id);
    }

    //end friends blocked me

    public function addFriend($friend)
    {
        return $this->friendsRelationByMe()->save($friend, [
            'impactor_id' => $this->id
        ]);
    }

    public function acceptFriend($friend)
    {
        return $this->friendsRelationToMe()
            ->wherePivot('user_id', '=', $friend->id)
            ->wherePivot('friend_id', '=', $this->id)
            ->wherePivot('status', '=', 0)
            ->updateExistingPivot($friend->id, [
                'status' => 1,
                'impactor_id' => $this->id
            ]);
    }

    public function denyFriend($friend)
    {
        return $this->friendsRelationToMe()
            ->wherePivot('user_id', '=', $friend->id)
            ->wherePivot('friend_id', '=', $this->id)
            ->wherePivot('status', '=', 0)
            ->updateExistingPivot($friend->id, [
                'status' => 2,
                'impactor_id' => $this->id
            ]);
    }

    public function cancelFriend($friend)
    {
        return $this->friendsRelationByMe()
            ->wherePivot('user_id', '=', $this->id)
            ->wherePivot('friend_id', '=', $friend->id)
            ->wherePivot('status', '=', 0)
            ->detach();
    }

    public function unfriend($friend)
    {
        $unfriend = $this->friendsRelationByMe()
            ->wherePivot('user_id', '=', $this->id)
            ->wherePivot('friend_id', '=', $friend->id)
            ->wherePivot('status', '=', 1)
            ->detach();
        if (!$unfriend) {
            $unfriend = $this->friendsRelationToMe()
                ->wherePivot('user_id', '=', $friend->id)
                ->wherePivot('friend_id', '=', $this->id)
                ->wherePivot('status', '=', 1)
                ->detach();
        }
        return $unfriend;
    }

    public function blockFriend($friend)
    {
        $block = $this->friendsRelationByMe()
            ->wherePivot('user_id', '=', $this->id)
            ->wherePivot('friend_id', '=', $friend->id)
            ->wherePivot('status', '=', 1)
            ->updateExistingPivot($friend->id, [
                'status' => 3,
                'impactor_id' => $this->id
            ]);
        if (!$block) {
            $block = $this->friendsRelationToMe()
                ->wherePivot('user_id', '=', $friend->id)
                ->wherePivot('friend_id', '=', $this->id)
                ->wherePivot('status', '=', 1)
                ->updateExistingPivot($friend->id, [
                    'status' => 3,
                    'impactor_id' => $this->id
                ]);
        }
        return $block;
    }

    public function unblockFriend($friend)
    {
        $unblock = $this->friendsRelationByMe()
            ->wherePivot('user_id', '=', $this->id)
            ->wherePivot('friend_id', '=', $friend->id)
            ->wherePivot('status', '=', 3)
            ->wherePivot('impactor_id', '=', $this->id)
            ->updateExistingPivot($friend->id, [
                'status' => 1
            ]);
        if (!$unblock) {
            $unblock = $this->friendsRelationToMe()
                ->wherePivot('user_id', '=', $friend->id)
                ->wherePivot('friend_id', '=', $this->id)
                ->wherePivot('status', '=', 3)
                ->wherePivot('impactor_id', '=', $this->id)
                ->updateExistingPivot($friend->id, [
                    'status' => 1
                ]);
        }
        return $unblock;
    }

    /**
     * end friends relations
     */

    public function conversations()
    {
        return $this->belongsToMany('App\Model\Messaging\Conversation')->withTimestamps()->latest('updated_at');
    }

    public function messages()
    {
        return $this->hasMany('App\Model\Messaging\Message');
    }

    public function tours()
    {
        return $this->belongsToMany('App\Model\Tour')->withTimestamps();
    }

    public function organizedTours()
    {
        return $this->belongsToMany('App\Model\Tour')
            ->where('tours.user_id', '=', $this->id);
    }

    public function requestedTours()
    {
        return $this->belongsToMany('App\Model\Tour')
            ->where('tours.user_id', '<>', $this->id)
            ->wherePivot('relation', '=', 1);
    }

    public function interestedPublicTours()
    {
        return $this->belongsToMany('App\Model\Tour')
            ->wherePivot('relation', '=', 0);
    }

    public function goingTour($tourId, $person)
    {
        return $this->tours()
            ->wherePivot('tour_id', '=', $tourId)
            ->wherePivot('user_id', '=', $this->id)
            ->updateExistingPivot($tourId, [
                'going' => 2,
                'person' => $person
            ]);
    }

    public function notGoingTour($tourId)
    {
        return $this->tours()
            ->wherePivot('tour_id', '=', $tourId)
            ->wherePivot('user_id', '=', $this->id)
            ->updateExistingPivot($tourId, [
                'going' => 1
            ]);
    }

    public function matesOfTour()
    {
        return $this->tours()
            ->wherePivot('going', '=', 2);
    }
}
