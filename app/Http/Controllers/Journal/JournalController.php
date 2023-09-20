<?php

namespace App\Http\Controllers\Journal;

use App\Actions\Options\GetAccountOptions;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Journals\Journal\CreateJournalRequest;
use App\Http\Resources\SubmitDefaultResource;
use App\Services\Journal\JournalService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class JournalController extends AdminBaseController
{
    public function __construct(JournalService $journalService, GetAccountOptions $getAccountOptions)
    {
        $this->journalServices = $journalService;
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

    public function store(CreateJournalRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->journalServices->createData($request);
            $result = new SubmitDefaultResource($data, 'Success create journal');
            DB::commit();

            return $this->respond($result);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage());
        }
    }
}
