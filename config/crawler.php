<?php

return [
    "host" => env("CRAWLER_HOST", "http://127.0.0.1:8000"),
    "token" => env("CRAWLER_TOKEN", "key"),
    "enable_connection" => env("CRAWLER_ENABLE_CONNECTION", false),
];
