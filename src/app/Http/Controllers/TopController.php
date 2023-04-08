<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;


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
     * @return \Inertia\Response
     */
    public function __invoke(Request $request): \Inertia\Response
    {
        return Inertia::render('Top');
    }
}
