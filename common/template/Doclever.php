<?php

namespace common\template;

class Doclever
{

    public $template;

    public function __construct()
    {
        $this->template = <<<EOF
{
    "flag": "SBDoc",
    "param": [
        {
            "before": {
                "mode": 0,
                "code": ""
            },
            "after": {
                "mode": 0,
                "code": ""
            },
            "name": "参数",
            "id": "aa220bcb-e9e8-4f03-bbd6-e1faeec9cfec",
            "remark": "",
            "header": [
                {
                    "name": "Content-Type",
                    "value": "application/x-www-form-urlencoded",
                    "remark": ""
                }
            ],
            "queryParam": [<queryParam>],
            "bodyParam": [<bodyParam>],
            "bodyInfo": {
                "type": 0,
                "rawType": 0,
                "rawTextRemark": "",
                "rawFileRemark": "",
                "rawText": ""
            },
            "outParam": [
                {
                    "name": "code",
                    "type": 1,
                    "remark": "",
                    "must": 1,
                    "mock": "0"
                },
                {
                    "name": "message",
                    "type": 0,
                    "remark": "",
                    "must": 1,
                    "mock": "success"
                },
                {
                    "name": "data",
                    "type": 4,
                    "remark": "",
                    "must": 1,
                    "mock": "",
                    "data": [
                        {
                            "name": "list",
                            "type": 3,
                            "remark": "列表",
                            "must": 1,
                            "mock": "",
                            "data": [
                                {
                                    "name": null,
                                    "type": 4,
                                    "remark": "",
                                    "must": 1,
                                    "mock": "",
                                    "data": [<outParam>]
                                }
                            ]
                        }
                    ]
                }
            ],
            "outInfo": {
                "type": 0,
                "rawRemark": "",
                "rawMock": "",
                "jsonType": 0
            },
            "restParam": []
        }
    ],
    "finish": 0,
    "sort": 0,
    "name": "开通套餐",
    "url": "package/savePackageInfo",
    "remark": "",
    "method": "POST",
    "createdAt": "2021-03-04T02:38:51.695Z",
    "updatedAt": "2021-03-04T03:19:56.825Z"
}
EOF;
    }
}
