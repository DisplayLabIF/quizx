<?php

namespace App\Schedule;

use Zenstruck\ScheduleBundle\Schedule;
use Zenstruck\ScheduleBundle\Schedule\ScheduleBuilder;

class AppScheduleBuilder implements ScheduleBuilder
{
    public function buildSchedule(Schedule $schedule): void
    {
        $schedule
            ->timezone('UTC')
            ->environments('prod');

        $schedule->addCommand('app:relatorio:semanal')
            ->description('Send the weekly report to users.')
            ->thursdays()
            ->at(1);
    }
}
