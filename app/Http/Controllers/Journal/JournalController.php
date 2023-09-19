<?php

namespace App\Http\Controllers\Journal;

use App\Actions\Options\GetAccountOptions;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JournalController extends AdminBaseController
{
    public function __construct(GetAccountOptions $getAccountOptions)
    {
        $this->getAccountOptions = $getAccountOptions;
    }
    public function journalIndex()
    {
        return Inertia::render($this->source . 'journal/journal/index', [
            'title' => 'Journal | Jurnalin'
        ]);
    }

    public function create()
    {
        return Inertia::render($this->source . 'journal/journal/create', [
            'title' => 'Create Journal | Jurnalin',
            'additional' => [
                'account_options' => $this->getAccountOptions->handle()
            ]
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
