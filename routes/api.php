<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//User Auth
Route::group(['namespace' => 'User\Auth', 'middleware' => 'api'], function () {

    // Registration Routes...
    Route::post('register', 'RegistrationController@register');
    Route::put('register/{email}', 'SocialAuthController@register');

    // Authentication Routes...
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

    // Password Reset Routes...
    Route::post('password/email', 'PasswordResetController@sendResetLinkEmail');
    Route::post('password/reset', 'PasswordResetController@reset');

});

use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

Route::middleware('api')->group(function () {
    Route::namespace('Forum')->group(function () {
        Route::apiResources([
            'question' => 'QuestionController',
            'reply' => 'ReplyController'
        ]);

        Route::post('question-reply', 'ReplyController@storeQuestionReply');

    });

    Route::get('article', 'User\ArticleController@index');
    Route::get('article/{slug}', 'User\ArticleController@show');

    Route::get('tour-package', 'User\TourPackageController@index');
    Route::get('tour-package/{slug}', 'User\TourPackageController@show');

    Route::namespace('User')->group(function () {
        Route::get('search-place/{text}', 'PlaceController@search');
        Route::get('place/{slug}', 'PlaceController@show');
        Route::get('weather-status/{lat}/{lon}', 'PlaceController@getWeatherStatus');

        Route::post('geolocation', 'PlaceController@reverseGeocode');
        Route::post('geocode', 'PlaceController@geocode');
        Route::post('autocomplete', 'PlaceController@autocomplete');
    });
});

Route::group(['namespace' => 'User', 'middleware' => 'auth.jwt'], function () {
    Route::get('user/friends', 'FriendController@index');
    Route::post('user/add-friend-request', 'FriendController@addFriendRequest');
    Route::post('user/accept-friend-request', 'FriendController@acceptFriendRequest');
    Route::post('user/deny-friend-request', 'FriendController@denyFriendRequest');
    Route::post('user/cancel-friend-request', 'FriendController@cancelFriendRequest');
    Route::post('user/unfriend-request', 'FriendController@unfriendRequest');
    Route::post('user/block-friend-request', 'FriendController@blockFriendRequest');
    Route::post('user/unblock-friend-request', 'FriendController@unblockFriendRequest');
    Route::get('user/search/{text}', 'FriendController@searchNewPeople');
    Route::get('user/search-friend/{text}', 'FriendController@searchFriend');
    Route::get('user/total-friends', 'FriendController@countFriends');
    Route::get('user/random-people', 'FriendController@getRandomNewPeople');

    Route::get('conversation', 'ConversationController@index');
    Route::post('conversation', 'ConversationController@createConversation');
    Route::get('conversation/{id}', 'ConversationController@show');
    Route::post('conversation/add-user', 'ConversationController@addUser');
    Route::post('conversation/remove-user', 'ConversationController@removeUser');
    Route::post('conversation/leave', 'ConversationController@leaveConversation');
    Route::post('conversation/message', 'ConversationController@sendMessage');
    Route::post('conversation/mark-as-read', 'ConversationController@markUnreadMessagesAsRead');
    Route::get('conversation/search-people/{text}', 'ConversationController@searchPeople');
    Route::get('total-unread', 'ConversationController@totalUnreadMessages');

    Route::get('tour', 'TourController@index');
    Route::post('tour', 'TourController@store');
    Route::get('tour/{id}', 'TourController@show');
    Route::get('tour/{id}/search-friend/{text}', 'TourController@searchFriend');
    Route::post('tour/add-mate', 'TourController@addMate');
    Route::post('tour/accept-mate-request', 'TourController@acceptMateRequest');
    Route::post('tour/deny-mate-request', 'TourController@denyMateRequest');
    Route::put('tour/{id}/make-public', 'TourController@makeTourPublic');
    Route::put('tour/{id}/add-public-mate', 'TourController@addPublicMate');
    Route::put('tour/{id}/accept-public-mate', 'TourController@acceptPublicMateRequest');
    Route::put('tour/{id}/deny-public-mate', 'TourController@denyPublicMateRequest');
});