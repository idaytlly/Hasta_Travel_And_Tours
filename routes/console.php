<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cars:update-status')->daily();
