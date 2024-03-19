<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ParseService;

class ParseController extends Controller
{
    public function __construct(private readonly ParseService $service)
    {
    }

    public function all(): string
    {
        return $this->service->all();
    }
}
