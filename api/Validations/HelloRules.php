<?php

namespace Api\Validations;

class HelloRules
{
    const SELECT = '
        {
            "type": "object",
            "properties": {
                "data": {
                    "oneOf": [
                        { "$ref": "#/definitions/integerData" },
                        { "$ref": "#/definitions/stringData" }
                    ]
                }
            },
            "required": ["data"],
            "definitions": {
                "integerData" : {
                    "type": "integer",
                    "minimum" : 0
                },
                "stringData" : {
                    "type": "string"
                }
            }
        }
    ';

    const INSERT = '
        {
            "type": "object",
            "properties": {
                "data": {
                    "oneOf": [
                        { "$ref": "#/definitions/integerData" },
                        { "$ref": "#/definitions/stringData" }
                    ]
                }
            },
            "required": ["data"],
            "definitions": {
                "integerData" : {
                    "type": "integer",
                    "minimum" : 0
                },
                "stringData" : {
                    "type": "string"
                }
            }
        }
    ';

    const UPDATE = '
        {
            "type": "object",
            "properties": {
                "data": {
                    "oneOf": [
                        { "$ref": "#/definitions/integerData" },
                        { "$ref": "#/definitions/stringData" }
                    ]
                }
            },
            "required": ["data"],
            "definitions": {
                "integerData" : {
                    "type": "integer",
                    "minimum" : 0
                },
                "stringData" : {
                    "type": "string"
                }
            }
        }
    ';

    const DELETE = '
        {
            "type": "object",
            "properties": {
                "data": {
                    "oneOf": [
                        { "$ref": "#/definitions/integerData" },
                        { "$ref": "#/definitions/stringData" }
                    ]
                }
            },
            "required": ["data"],
            "definitions": {
                "integerData" : {
                    "type": "integer",
                    "minimum" : 0
                },
                "stringData" : {
                    "type": "string"
                }
            }
        }
    ';
}