<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('prices', function () {
    return true;
});
