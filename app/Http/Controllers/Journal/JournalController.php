<?php

namespace App\Http\Controllers\Journal;

use App\Actions\Options\GetAccountOptions;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Journals\Journal\CreateJournalRequest;
use App\Http\Requests\Journals\Journal\UpdateJournalRequest;
use App\Http\Resources\Journal\JournalListResource;
use App\Http\Resources\SubmitDefaultResource;
use App\Services\Journal\JournalService;
use Illuminate\Http\Request;
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

    public function getData(Request $request)
    {
        try {
            $data = $this->journalServices->getData($request);
            $result = new JournalListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
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

    public function edit($id)
    {
        $data = $this->journalServices->getDataById($id);
        return Inertia::render($this->source . 'journal/journal/create', [
            'title' => 'Create Journal | Jurnalin',
            'additional' => [
                'account_options' => $this->getAccountOptions->handle(),
                'data' => $data
            ]
        ]);
    }

    public function updateData($id, UpdateJournalRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->journalServices->updateData($id, $request);
            $result = new SubmitDefaultResource($data, 'Success update journal');
            DB::commit();

            return $this->respond($result);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id)
    {
        try {
            $data = $this->journalServices->destroy($id);
            $result = new SubmitDefaultResource($data, 'Success delete journal');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
