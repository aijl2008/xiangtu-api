<?php

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {


    try {

        $fileId = '5285890783585503878';

        $vod = new \App\Models\Vod();
        $task = $vod->createSnapshotByTimeOffsetAsCover($fileId, 1);
//        $task = $vod->runProcedure(
//            [
//                "inputType" => "SingleFile",
//                "file.id" => $fileId,
//                "file.startTimeOffset" => 1,
//                "file.endTimeOffset" => 2,
//                "procedure" => "setCoverBySnapshot"
//            ]
//        );
        dump($task);
        sleep(1);
        dump($vod->getTaskInfo($task->vodTaskId));
        // dd($vod->getTaskInfo($task->vodTaskId), $vod->getFinishedTaskList($fileId), $vod->getVideoInfo($fileId));

    } catch (\Exception $e) {
        $this->error($e->getMessage());
    }


})->describe('Display an inspiring quote');
