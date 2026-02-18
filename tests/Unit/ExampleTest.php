<?php

it('has environment variables set', function () {
    $this->assertTrue(strlen(config('app.key')) > 0);
});
