# lib-parser-open-rfm-full-list

Библиотека [LIB-a] парсирования открытого перечня РФМ

Пример использования либы в composer.lock:



	"packages": [

        {

            "name": "vlad/parser-open-rfm-full-list",

            "version": "1.2.0",

            "source": {

                "type": "git",

                "url": "https://github.com/vladislavpetrov4311/parser-open-rfm-full-list.git",

                "reference": "6c7700a"

            },

            "dist": {

                "type": "zip",

                "url": "https://api.github.com/repos/vladislavpetrov4311/parser-open-rfm-full-list/zipball/6c7700a",

                "reference": "6c7700a",

                "shasum": ""

            },

            "require": {

                "php": "^8.0",

                "ruslanovich111/date-converter": "1.1.*"

            },

            "type": "library",

            "autoload": {

                "psr-4": {

                    "IncidentCenter\\RL\\CloudFunctions\\ParserOpenRfmFullList\\Service\\Parser\\": "src"

                }

            },

            "notification-url": "https://packagist.org/downloads/",

            "authors": [

                {

                    "name": "Ruslan",

                    "email": "ruslan@mail.ru"

                }

            ],

            "description": "Либа парсера открытого перечня РФМ",

            "support": {

                "issues": "https://github.com/vladislavpetrov4311/parser-open-rfm-full-list/issues",

                "source": "https://github.com/vladislavpetrov4311/parser-open-rfm-full-list/tree/1.2.0"

            },

            "time": "2022-11-01T06:48:30+00:00"

        }

	]


В composer.json указываем так:



	"require": {

        "vlad/parser-open-rfm-full-list": "^1.2.0"

    }