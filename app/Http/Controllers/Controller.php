<?php

namespace BookStack\Http\Controllers;

use BookStack\Auth\User;
use BookStack\Entities\Entity;
use BookStack\Ownable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use BookStack\Orz\Message;
use BookStack\Orz\Space;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * @var User static
     */
    protected $currentUser;
    /**
     * @var bool
     */
    protected $signedIn;

    protected $cache_expire = 600;
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            // Get a user instance for the current user
            $user = user();

            // Share variables with controllers
            $this->currentUser = $user;
            $this->signedIn = auth()->check();

            // Share variables with views
            view()->share('signedIn', $this->signedIn);
            view()->share('currentUser', $user);
    
            //my private space and created by myself
            $space = Cache::get('all_space');
            if (!$space) {
                $space = Space::where(['created_by' => $user->id])->get();
                Cache::put('all_space', $space, $this->cache_expire);
            }
            view()->share('all_space', $space);
            
            //invited space
            $invited_space = Cache::get('invited_space');
            if (!$invited_space) {
                $ids = DB::table('space_user')->where('user_id', $user->id)->pluck('space_id')->all();
                $invited_space = Space::whereIn('id', $ids)->get();
                Cache::put('invited_space', $invited_space, $this->cache_expire);
            }
            view()->share('invited_space', $invited_space);
            
            
            $message_count = Message::where(['to'=>$user->id])->where(['status'=>0])->count();
            view()->share('message_count', $message_count);

            return $next($request);
        });
    }

    /**
     * Stops the application and shows a permission error if
     * the application is in demo mode.
     */
    protected function preventAccessForDemoUsers()
    {
        if (config('app.env') === 'demo') {
            $this->showPermissionError();
        }
    }

    /**
     * Adds the page title into the view.
     * @param $title
     */
    public function setPageTitle($title)
    {
        view()->share('pageTitle', $title);
    }

    /**
     * On a permission error redirect to home and display.
     * the error as a notification.
     */
    protected function showPermissionError()
    {
        if (request()->wantsJson()) {
            $response = response()->json(['error' => trans('errors.permissionJson')], 403);
        } else {
            $response = redirect('/');
            session()->flash('error', trans('errors.permission'));
        }

        throw new HttpResponseException($response);
    }

    /**
     * Checks for a permission.
     * @param string $permissionName
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    protected function checkPermission($permissionName)
    {
        if (!user() || !user()->can($permissionName)) {
            $this->showPermissionError();
        }
        return true;
    }

    /**
     * Check the current user's permissions against an ownable item.
     * @param $permission
     * @param Ownable $ownable
     * @return bool
     */
    protected function checkOwnablePermission($permission, Ownable $ownable)
    {
        if (userCan($permission, $ownable)) {
            return true;
        }
        return $this->showPermissionError();
    }

    /**
     * Check if a user has a permission or bypass if the callback is true.
     * @param $permissionName
     * @param $callback
     * @return bool
     */
    protected function checkPermissionOr($permissionName, $callback)
    {
        $callbackResult = $callback();
        if ($callbackResult === false) {
            $this->checkPermission($permissionName);
        }
        return true;
    }

    /**
     * Check if the current user has a permission or bypass if the provided user
     * id matches the current user.
     * @param string $permissionName
     * @param int $userId
     * @return bool
     */
    protected function checkPermissionOrCurrentUser(string $permissionName, int $userId)
    {
        return $this->checkPermissionOr($permissionName, function () use ($userId) {
            return $userId === $this->currentUser->id;
        });
    }

    /**
     * Send back a json error message.
     * @param string $messageText
     * @param int $statusCode
     * @return mixed
     */
    protected function jsonError($messageText = "", $statusCode = 500)
    {
        return response()->json(['message' => $messageText], $statusCode);
    }

    /**
     * Create the response for when a request fails validation.
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if ($request->expectsJson()) {
            return response()->json(['validation' => $errors], 422);
        }

        return redirect()->to($this->getRedirectUrl())
            ->withInput($request->input())
            ->withErrors($errors, $this->errorBag());
    }

    /**
     * Create a response that forces a download in the browser.
     * @param string $content
     * @param string $fileName
     * @return \Illuminate\Http\Response
     */
    protected function downloadResponse(string $content, string $fileName)
    {
        return response()->make($content, 200, [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }
    
    public function checkIfRedirect(Entity $entity = null)
    {
        $url = '';
        $space = cache('current_space');
        if ($space) {
            $url .= '/space/'.$space->id;
            if ($entity) 
                $url .= '/'.$entity->getClassName().'/'.$entity->id;
    
            $response = redirect($url);
            throw new HttpResponseException($response);
        }
    
    }
}
