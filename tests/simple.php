<?php
require_once "../vendor/autoload.php";


$builder = new \Vanqard\Jform\Builder();

$spec = [
    "schema" => "user.profile.json",
    "meta" => [
        "mask" => ['reports_to','job_title']
    ],
    "fields" => [
        [
            "name" => "username",
            "value" => "",
            "attributes" => [
                "id" => "username",
                "title" => "username",
                "placeholder" => "username"
            ],
            "validators" => []
        ],
        [
            "name" => "password",
            "value" => "",
            "attributes" => [
                "id" => "password",
                "type" => "password",
                "title" => "password"
            ],
            "validators" => []
        ],
        [
            "name" => "reports_to",
            "value" => "",
            "attributes" => [
                "id" => "reports_to",
                "type" => "number",
                "title" => "Reports to"
            ],
            "validators" => []
        ]
    ]
];

$jsonspec = json_encode($spec, JSON_PRETTY_PRINT);

echo $jsonspec;

$form = $builder->build($jsonspec);

print_r($form->getMeta());


$recompiled = json_encode($form, JSON_PRETTY_PRINT);

echo $recompiled;

