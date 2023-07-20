<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


/**
 * Class TopController
 * トップページコントローラー
 * @package App\Http\Controllers
 */
class TopController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Top');
    }
}
