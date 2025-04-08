# parser-open-rfm-full-list

Библиотека [LIB-a] парсирования открытого перечня РФМ

Пример использования либы в composer.lock:

`
	"packages": [

        {

            "name": "vlad/open-rfm",

            "version": "1.1.3",

            "source": {

                "type": "git",

                "url": "https://github.com/vladislavpetrov4311/parser-open-rfm-full-list.git",

                "reference": "186050f"

            },

            "dist": {

                "type": "zip",

                "url": "https://api.github.com/repos/vladislavpetrov4311/parser-open-rfm-full-list/zipball/186050f",

                "reference": "186050f",

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

            "description": "Преобразователь дат в определенный формат",

            "support": {

                "issues": "https://github.com/vladislavpetrov4311/parser-open-rfm-full-list/issues",

                "source": "https://github.com/vladislavpetrov4311/parser-open-rfm-full-list/tree/1.1.3"

            },

            "time": "2022-11-01T06:48:30+00:00"

        }

]`


В composer.json указываем так:

`

	"require": {

        "vlad/open-rfm": "^1.1.3"

    }`