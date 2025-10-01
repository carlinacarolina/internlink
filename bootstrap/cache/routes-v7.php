<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2sIwIh1TRxN6Lew3',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/introduction' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RfXEUcElE7UmGVd1',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'POST' => 1,
            'HEAD' => 2,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/signup' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'signup',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'POST' => 1,
            'HEAD' => 2,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jYr66o2xLjuglRyP',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/developers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QR1dz7SRScpWOOjZ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ah2VAB1YyfmRjGvM',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/developers/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5toId36fLrSRT6dY',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/schools' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FomGs93OLnaWoEqb',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8n23Jdz2Pok8Cz1F',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/schools/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::nexAwZDBt0HlKDZp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/developers/([^/]++)(?|/(?|read(*:38)|update(*:51))|(*:59))|/schools/([^/]++)(?|/(?|read(*:95)|update(*:108))|(*:117))|/([^/]++)(?|(*:138)|/(?|s(?|ettings(?|(*:164)|/(?|profile(?|(*:186))|security(?|(*:206))|environments(*:227)))|tudents(?|(*:247)|/(?|create(*:265)|([^/]++)(?|/(?|read(*:292)|update(*:306))|(*:315)))|(*:325))|upervisors(?|(*:347)|/(?|create(*:365)|([^/]++)(?|/(?|read(*:392)|update(*:406))|(*:415)))|(*:425)))|m(?|ajor\\-contacts(?|(*:456)|/(?|create(*:474)|([^/]++)(?|/update(*:500)|(*:508)))|(*:518))|onitorings(?|(*:540)|/(?|create(*:558)|([^/]++)(?|/(?|read(*:585)|update(*:599))|(*:608)))|(*:618))|eta/(?|monitor\\-types(*:648)|supervisors(*:667)))|a(?|dmins(?|(*:689)|/(?|create(*:707)|([^/]++)(?|/(?|read(*:734)|update(*:748))|(*:757)))|(*:767))|pplications(?|(*:790)|/(?|create(*:808)|([^/]++)(?|/(?|read(*:835)|pdf(?|(*:849)|/print(*:863))|update(*:878))|(*:887)))|(*:897)))|in(?|stitutions(?|(*:925)|/(?|create(*:943)|([^/]++)(?|/(?|read(*:970)|update(*:984))|(*:993)))|(*:1003))|ternships(?|(*:1025)|/(?|create(*:1044)|([^/]++)(?|/(?|read(*:1072)|update(*:1087))|(*:1097)))|(*:1108)))))|/storage/(.*)(*:1134))/?$}sDu',
    ),
    3 => 
    array (
      38 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BtrgES8llaUMtGBf',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      51 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'developers.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      59 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6EWJKKNFhWjM1zZ7',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iaBTcZgbNcjFrKmp',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      95 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OgHKzDEFEmmo0GNh',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      108 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IGN191D7lwkMXJXU',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      117 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::X4zOBS0GTflm42EY',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MHuBEJVVuJfGzDmT',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      138 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'school.dashboard',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      164 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'settings.index',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      186 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'settings.profile',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'settings.profile.update',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      206 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'settings.security',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'settings.security.update',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      227 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'settings.environments',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      247 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DkjXhAkCk6KdOdZW',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      265 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OU4vd1u0tot58CUy',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      292 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::S1eZlNYWoXGP3GFI',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      306 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'students.edit',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      315 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::h48pHxDQqG7Vy2wN',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wHUFUo6M8IyT8Knm',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      325 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YQqS0DcnS9o5xi93',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      347 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gPDmhHUxxxUoinva',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      365 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::g4gYPpXFInxuKkYl',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      392 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AvwzmTYiUSrq18qK',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      406 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'supervisors.edit',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      415 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aUk8ATA8MF9CkBhh',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mKjpKCIvKwkiEI5r',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      425 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EnC2RbhSlNfJWW89',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      456 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wJFhIyLPlZe4NjyD',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      474 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2CxCVoQIoOEBxFWp',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      500 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DQt6k6ipgGp9vcCQ',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      508 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iatlCkBmrz6PlE3s',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5UJdsXX6mnKOxehd',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      518 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LeeRfSiQxbKDaGtI',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      540 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PtY9UGw44UZA8hQT',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      558 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zkvVAZeRLIvc0jUu',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      585 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FOoXn9XhI6KczXuj',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      599 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rP89QbP9GUbzkXsu',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      608 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XKrYNcSWO0X0lubG',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::m77of9XJUxCke7Uc',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      618 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6cPZIej6Hxi16OYf',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      648 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0G4XspaSQEkYOp6Z',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      667 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3bX02FoQ4MeXaGYu',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      689 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::paHNYRdjv3Y1YyHy',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      707 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qxdCJEaEkdrtnaXX',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      734 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OBJKK9FphvaD4lwd',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      748 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admins.edit',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      757 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tynTwhabGMxzJsMu',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Zjkeo3WFFqwS2ZN4',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      767 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Tav74faj7OcIvh4w',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      790 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0w835yLKnSy20ItC',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      808 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::n4SPBR1UsrdR9Alv',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      835 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::alNGc2ZZ26A5HDK2',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      849 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pRzV0w8aCGInnV4I',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      863 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::alniPnHXvTStfjwY',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      878 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::r18ZfyZ37hpI5KdU',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      887 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::y5CXukxt5FUOoWzL',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tYZY0iV6qea0GzZq',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      897 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tlGjEOu3okKInj8p',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      925 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Tb1dRmkypvKVLftc',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      943 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pkZXQR82OJbyk9kr',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      970 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wlvj9I5rbnk1CZTa',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      984 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PU8DBXJh1fWZ7D08',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      993 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rEpwe6F5mdUfIHxB',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aoKI29BJ27heAGqi',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1003 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::j16457XM13gKRKDQ',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1025 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::e28e6PRqV0dWQfYT',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1044 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::owo3Huw7uwjzH4x0',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1072 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1bRVSC5dy3gyXPGr',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1087 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LcFntHoQ53321Dl7',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1097 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BahTPqxsMDYZUMmo',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::at79SlFzRcFovzpR',
          ),
          1 => 
          array (
            0 => 'school',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1108 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iBk0JGMBhdWIDXiA',
          ),
          1 => 
          array (
            0 => 'school',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1134 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'generated::2sIwIh1TRxN6Lew3' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:807:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'/var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"00000000000003620000000000000000";}}',
        'as' => 'generated::2sIwIh1TRxN6Lew3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RfXEUcElE7UmGVd1' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'introduction',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => '\\Illuminate\\Routing\\ViewController@__invoke',
        'controller' => '\\Illuminate\\Routing\\ViewController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::RfXEUcElE7UmGVd1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
        'view' => 'introduction',
        'data' => 
        array (
        ),
        'status' => 200,
        'headers' => 
        array (
        ),
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'POST',
        2 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\AuthController@login',
        'controller' => 'App\\Http\\Controllers\\AuthController@login',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'signup' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'POST',
        2 => 'HEAD',
      ),
      'uri' => 'signup',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\AuthController@signup',
        'controller' => 'App\\Http\\Controllers\\AuthController@signup',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'signup',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\AuthController@logout',
        'controller' => 'App\\Http\\Controllers\\AuthController@logout',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jYr66o2xLjuglRyP' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:602:"function () {
        if (\\session(\'role\') === \'developer\') {
            return \\view(\'dashboard\');
        }

        $schoolCode = \\session(\'school_code\');
        if (!$schoolCode) {
            $schoolId = \\session(\'school_id\');
            \\abort_if(!$schoolId, 403, \'School assignment missing.\');
            $school = \\App\\Models\\School::find($schoolId);
            \\abort_if(!$school, 403, \'School assignment invalid.\');
            $schoolCode = $school->code;
            \\session([\'school_code\' => $schoolCode]);
        }

        return \\redirect(\'/\' . \\rawurlencode($schoolCode));
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000003670000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::jYr66o2xLjuglRyP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QR1dz7SRScpWOOjZ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'developers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\DeveloperController@index',
        'controller' => 'App\\Http\\Controllers\\DeveloperController@index',
        'namespace' => NULL,
        'prefix' => '/developers',
        'where' => 
        array (
        ),
        'as' => 'generated::QR1dz7SRScpWOOjZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5toId36fLrSRT6dY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'developers/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\DeveloperController@create',
        'controller' => 'App\\Http\\Controllers\\DeveloperController@create',
        'namespace' => NULL,
        'prefix' => '/developers',
        'where' => 
        array (
        ),
        'as' => 'generated::5toId36fLrSRT6dY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ah2VAB1YyfmRjGvM' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'developers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\DeveloperController@store',
        'controller' => 'App\\Http\\Controllers\\DeveloperController@store',
        'namespace' => NULL,
        'prefix' => '/developers',
        'where' => 
        array (
        ),
        'as' => 'generated::ah2VAB1YyfmRjGvM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BtrgES8llaUMtGBf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'developers/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
          3 => 'developer.self',
        ),
        'uses' => 'App\\Http\\Controllers\\DeveloperController@show',
        'controller' => 'App\\Http\\Controllers\\DeveloperController@show',
        'namespace' => NULL,
        'prefix' => '/developers',
        'where' => 
        array (
        ),
        'as' => 'generated::BtrgES8llaUMtGBf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'developers.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'developers/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
          3 => 'developer.self',
        ),
        'uses' => 'App\\Http\\Controllers\\DeveloperController@edit',
        'controller' => 'App\\Http\\Controllers\\DeveloperController@edit',
        'namespace' => NULL,
        'prefix' => '/developers',
        'where' => 
        array (
        ),
        'as' => 'developers.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6EWJKKNFhWjM1zZ7' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'developers/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
          3 => 'developer.self',
        ),
        'uses' => 'App\\Http\\Controllers\\DeveloperController@update',
        'controller' => 'App\\Http\\Controllers\\DeveloperController@update',
        'namespace' => NULL,
        'prefix' => '/developers',
        'where' => 
        array (
        ),
        'as' => 'generated::6EWJKKNFhWjM1zZ7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iaBTcZgbNcjFrKmp' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'developers/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
          3 => 'developer.self',
        ),
        'uses' => 'App\\Http\\Controllers\\DeveloperController@destroy',
        'controller' => 'App\\Http\\Controllers\\DeveloperController@destroy',
        'namespace' => NULL,
        'prefix' => '/developers',
        'where' => 
        array (
        ),
        'as' => 'generated::iaBTcZgbNcjFrKmp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FomGs93OLnaWoEqb' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'schools',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\SchoolController@index',
        'controller' => 'App\\Http\\Controllers\\SchoolController@index',
        'namespace' => NULL,
        'prefix' => '/schools',
        'where' => 
        array (
        ),
        'as' => 'generated::FomGs93OLnaWoEqb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::nexAwZDBt0HlKDZp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'schools/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\SchoolController@create',
        'controller' => 'App\\Http\\Controllers\\SchoolController@create',
        'namespace' => NULL,
        'prefix' => '/schools',
        'where' => 
        array (
        ),
        'as' => 'generated::nexAwZDBt0HlKDZp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8n23Jdz2Pok8Cz1F' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'schools',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\SchoolController@store',
        'controller' => 'App\\Http\\Controllers\\SchoolController@store',
        'namespace' => NULL,
        'prefix' => '/schools',
        'where' => 
        array (
        ),
        'as' => 'generated::8n23Jdz2Pok8Cz1F',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OgHKzDEFEmmo0GNh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'schools/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\SchoolController@show',
        'controller' => 'App\\Http\\Controllers\\SchoolController@show',
        'namespace' => NULL,
        'prefix' => '/schools',
        'where' => 
        array (
        ),
        'as' => 'generated::OgHKzDEFEmmo0GNh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IGN191D7lwkMXJXU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'schools/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\SchoolController@edit',
        'controller' => 'App\\Http\\Controllers\\SchoolController@edit',
        'namespace' => NULL,
        'prefix' => '/schools',
        'where' => 
        array (
        ),
        'as' => 'generated::IGN191D7lwkMXJXU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::X4zOBS0GTflm42EY' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'schools/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\SchoolController@update',
        'controller' => 'App\\Http\\Controllers\\SchoolController@update',
        'namespace' => NULL,
        'prefix' => '/schools',
        'where' => 
        array (
        ),
        'as' => 'generated::X4zOBS0GTflm42EY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MHuBEJVVuJfGzDmT' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'schools/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'developer',
        ),
        'uses' => 'App\\Http\\Controllers\\SchoolController@destroy',
        'controller' => 'App\\Http\\Controllers\\SchoolController@destroy',
        'namespace' => NULL,
        'prefix' => '/schools',
        'where' => 
        array (
        ),
        'as' => 'generated::MHuBEJVVuJfGzDmT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'school.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:62:"function () {
            return \\view(\'dashboard\');
        }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000036b0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '/{school}',
        'where' => 
        array (
        ),
        'as' => 'school.dashboard',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'settings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SettingController@redirect',
        'controller' => 'App\\Http\\Controllers\\SettingController@redirect',
        'as' => 'settings.index',
        'namespace' => NULL,
        'prefix' => '{school}/settings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'settings.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/settings/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SettingController@editProfile',
        'controller' => 'App\\Http\\Controllers\\SettingController@editProfile',
        'as' => 'settings.profile',
        'namespace' => NULL,
        'prefix' => '{school}/settings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'settings.profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/settings/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SettingController@updateProfile',
        'controller' => 'App\\Http\\Controllers\\SettingController@updateProfile',
        'as' => 'settings.profile.update',
        'namespace' => NULL,
        'prefix' => '{school}/settings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'settings.security' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/settings/security',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SettingController@editSecurity',
        'controller' => 'App\\Http\\Controllers\\SettingController@editSecurity',
        'as' => 'settings.security',
        'namespace' => NULL,
        'prefix' => '{school}/settings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'settings.security.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/settings/security',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SettingController@updateSecurity',
        'controller' => 'App\\Http\\Controllers\\SettingController@updateSecurity',
        'as' => 'settings.security.update',
        'namespace' => NULL,
        'prefix' => '{school}/settings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'settings.environments' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/settings/environments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SettingController@environments',
        'controller' => 'App\\Http\\Controllers\\SettingController@environments',
        'as' => 'settings.environments',
        'namespace' => NULL,
        'prefix' => '{school}/settings',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DkjXhAkCk6KdOdZW' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/students',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\StudentController@index',
        'controller' => 'App\\Http\\Controllers\\StudentController@index',
        'namespace' => NULL,
        'prefix' => '{school}/students',
        'where' => 
        array (
        ),
        'as' => 'generated::DkjXhAkCk6KdOdZW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OU4vd1u0tot58CUy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/students/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\StudentController@create',
        'controller' => 'App\\Http\\Controllers\\StudentController@create',
        'namespace' => NULL,
        'prefix' => '{school}/students',
        'where' => 
        array (
        ),
        'as' => 'generated::OU4vd1u0tot58CUy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YQqS0DcnS9o5xi93' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{school}/students',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\StudentController@store',
        'controller' => 'App\\Http\\Controllers\\StudentController@store',
        'namespace' => NULL,
        'prefix' => '{school}/students',
        'where' => 
        array (
        ),
        'as' => 'generated::YQqS0DcnS9o5xi93',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::S1eZlNYWoXGP3GFI' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/students/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'student.self',
        ),
        'uses' => 'App\\Http\\Controllers\\StudentController@show',
        'controller' => 'App\\Http\\Controllers\\StudentController@show',
        'namespace' => NULL,
        'prefix' => '{school}/students',
        'where' => 
        array (
        ),
        'as' => 'generated::S1eZlNYWoXGP3GFI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'students.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/students/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'student.self',
        ),
        'uses' => 'App\\Http\\Controllers\\StudentController@edit',
        'controller' => 'App\\Http\\Controllers\\StudentController@edit',
        'namespace' => NULL,
        'prefix' => '{school}/students',
        'where' => 
        array (
        ),
        'as' => 'students.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::h48pHxDQqG7Vy2wN' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/students/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'student.self',
        ),
        'uses' => 'App\\Http\\Controllers\\StudentController@update',
        'controller' => 'App\\Http\\Controllers\\StudentController@update',
        'namespace' => NULL,
        'prefix' => '{school}/students',
        'where' => 
        array (
        ),
        'as' => 'generated::h48pHxDQqG7Vy2wN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wHUFUo6M8IyT8Knm' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '{school}/students/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'student.self',
        ),
        'uses' => 'App\\Http\\Controllers\\StudentController@destroy',
        'controller' => 'App\\Http\\Controllers\\StudentController@destroy',
        'namespace' => NULL,
        'prefix' => '{school}/students',
        'where' => 
        array (
        ),
        'as' => 'generated::wHUFUo6M8IyT8Knm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gPDmhHUxxxUoinva' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/supervisors',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SupervisorController@index',
        'controller' => 'App\\Http\\Controllers\\SupervisorController@index',
        'namespace' => NULL,
        'prefix' => '{school}/supervisors',
        'where' => 
        array (
        ),
        'as' => 'generated::gPDmhHUxxxUoinva',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::g4gYPpXFInxuKkYl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/supervisors/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SupervisorController@create',
        'controller' => 'App\\Http\\Controllers\\SupervisorController@create',
        'namespace' => NULL,
        'prefix' => '{school}/supervisors',
        'where' => 
        array (
        ),
        'as' => 'generated::g4gYPpXFInxuKkYl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EnC2RbhSlNfJWW89' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{school}/supervisors',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\SupervisorController@store',
        'controller' => 'App\\Http\\Controllers\\SupervisorController@store',
        'namespace' => NULL,
        'prefix' => '{school}/supervisors',
        'where' => 
        array (
        ),
        'as' => 'generated::EnC2RbhSlNfJWW89',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AvwzmTYiUSrq18qK' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/supervisors/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'supervisor.self',
        ),
        'uses' => 'App\\Http\\Controllers\\SupervisorController@show',
        'controller' => 'App\\Http\\Controllers\\SupervisorController@show',
        'namespace' => NULL,
        'prefix' => '{school}/supervisors',
        'where' => 
        array (
        ),
        'as' => 'generated::AvwzmTYiUSrq18qK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'supervisors.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/supervisors/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'supervisor.self',
        ),
        'uses' => 'App\\Http\\Controllers\\SupervisorController@edit',
        'controller' => 'App\\Http\\Controllers\\SupervisorController@edit',
        'namespace' => NULL,
        'prefix' => '{school}/supervisors',
        'where' => 
        array (
        ),
        'as' => 'supervisors.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aUk8ATA8MF9CkBhh' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/supervisors/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'supervisor.self',
        ),
        'uses' => 'App\\Http\\Controllers\\SupervisorController@update',
        'controller' => 'App\\Http\\Controllers\\SupervisorController@update',
        'namespace' => NULL,
        'prefix' => '{school}/supervisors',
        'where' => 
        array (
        ),
        'as' => 'generated::aUk8ATA8MF9CkBhh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mKjpKCIvKwkiEI5r' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '{school}/supervisors/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'supervisor.self',
        ),
        'uses' => 'App\\Http\\Controllers\\SupervisorController@destroy',
        'controller' => 'App\\Http\\Controllers\\SupervisorController@destroy',
        'namespace' => NULL,
        'prefix' => '{school}/supervisors',
        'where' => 
        array (
        ),
        'as' => 'generated::mKjpKCIvKwkiEI5r',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wJFhIyLPlZe4NjyD' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/major-contacts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@index',
        'controller' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@index',
        'namespace' => NULL,
        'prefix' => '{school}/major-contacts',
        'where' => 
        array (
        ),
        'as' => 'generated::wJFhIyLPlZe4NjyD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2CxCVoQIoOEBxFWp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/major-contacts/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@create',
        'controller' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@create',
        'namespace' => NULL,
        'prefix' => '{school}/major-contacts',
        'where' => 
        array (
        ),
        'as' => 'generated::2CxCVoQIoOEBxFWp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LeeRfSiQxbKDaGtI' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{school}/major-contacts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@store',
        'controller' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@store',
        'namespace' => NULL,
        'prefix' => '{school}/major-contacts',
        'where' => 
        array (
        ),
        'as' => 'generated::LeeRfSiQxbKDaGtI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DQt6k6ipgGp9vcCQ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/major-contacts/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@edit',
        'controller' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@edit',
        'namespace' => NULL,
        'prefix' => '{school}/major-contacts',
        'where' => 
        array (
        ),
        'as' => 'generated::DQt6k6ipgGp9vcCQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iatlCkBmrz6PlE3s' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/major-contacts/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@update',
        'controller' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@update',
        'namespace' => NULL,
        'prefix' => '{school}/major-contacts',
        'where' => 
        array (
        ),
        'as' => 'generated::iatlCkBmrz6PlE3s',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5UJdsXX6mnKOxehd' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '{school}/major-contacts/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@destroy',
        'controller' => 'App\\Http\\Controllers\\MajorStaffAssignmentController@destroy',
        'namespace' => NULL,
        'prefix' => '{school}/major-contacts',
        'where' => 
        array (
        ),
        'as' => 'generated::5UJdsXX6mnKOxehd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::paHNYRdjv3Y1YyHy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminUserController@index',
        'controller' => 'App\\Http\\Controllers\\AdminUserController@index',
        'namespace' => NULL,
        'prefix' => '{school}/admins',
        'where' => 
        array (
        ),
        'as' => 'generated::paHNYRdjv3Y1YyHy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qxdCJEaEkdrtnaXX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/admins/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminUserController@create',
        'controller' => 'App\\Http\\Controllers\\AdminUserController@create',
        'namespace' => NULL,
        'prefix' => '{school}/admins',
        'where' => 
        array (
        ),
        'as' => 'generated::qxdCJEaEkdrtnaXX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Tav74faj7OcIvh4w' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{school}/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminUserController@store',
        'controller' => 'App\\Http\\Controllers\\AdminUserController@store',
        'namespace' => NULL,
        'prefix' => '{school}/admins',
        'where' => 
        array (
        ),
        'as' => 'generated::Tav74faj7OcIvh4w',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OBJKK9FphvaD4lwd' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/admins/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'admin.self',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminUserController@show',
        'controller' => 'App\\Http\\Controllers\\AdminUserController@show',
        'namespace' => NULL,
        'prefix' => '{school}/admins',
        'where' => 
        array (
        ),
        'as' => 'generated::OBJKK9FphvaD4lwd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admins.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/admins/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'admin.self',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminUserController@edit',
        'controller' => 'App\\Http\\Controllers\\AdminUserController@edit',
        'namespace' => NULL,
        'prefix' => '{school}/admins',
        'where' => 
        array (
        ),
        'as' => 'admins.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tynTwhabGMxzJsMu' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/admins/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'admin.self',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminUserController@update',
        'controller' => 'App\\Http\\Controllers\\AdminUserController@update',
        'namespace' => NULL,
        'prefix' => '{school}/admins',
        'where' => 
        array (
        ),
        'as' => 'generated::tynTwhabGMxzJsMu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Zjkeo3WFFqwS2ZN4' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '{school}/admins/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
          3 => 'admin.self',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminUserController@destroy',
        'controller' => 'App\\Http\\Controllers\\AdminUserController@destroy',
        'namespace' => NULL,
        'prefix' => '{school}/admins',
        'where' => 
        array (
        ),
        'as' => 'generated::Zjkeo3WFFqwS2ZN4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Tb1dRmkypvKVLftc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/institutions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InstitutionController@index',
        'controller' => 'App\\Http\\Controllers\\InstitutionController@index',
        'namespace' => NULL,
        'prefix' => '{school}/institutions',
        'where' => 
        array (
        ),
        'as' => 'generated::Tb1dRmkypvKVLftc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pkZXQR82OJbyk9kr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/institutions/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InstitutionController@create',
        'controller' => 'App\\Http\\Controllers\\InstitutionController@create',
        'namespace' => NULL,
        'prefix' => '{school}/institutions',
        'where' => 
        array (
        ),
        'as' => 'generated::pkZXQR82OJbyk9kr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::j16457XM13gKRKDQ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{school}/institutions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InstitutionController@store',
        'controller' => 'App\\Http\\Controllers\\InstitutionController@store',
        'namespace' => NULL,
        'prefix' => '{school}/institutions',
        'where' => 
        array (
        ),
        'as' => 'generated::j16457XM13gKRKDQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wlvj9I5rbnk1CZTa' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/institutions/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InstitutionController@show',
        'controller' => 'App\\Http\\Controllers\\InstitutionController@show',
        'namespace' => NULL,
        'prefix' => '{school}/institutions',
        'where' => 
        array (
        ),
        'as' => 'generated::wlvj9I5rbnk1CZTa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PU8DBXJh1fWZ7D08' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/institutions/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InstitutionController@edit',
        'controller' => 'App\\Http\\Controllers\\InstitutionController@edit',
        'namespace' => NULL,
        'prefix' => '{school}/institutions',
        'where' => 
        array (
        ),
        'as' => 'generated::PU8DBXJh1fWZ7D08',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rEpwe6F5mdUfIHxB' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/institutions/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InstitutionController@update',
        'controller' => 'App\\Http\\Controllers\\InstitutionController@update',
        'namespace' => NULL,
        'prefix' => '{school}/institutions',
        'where' => 
        array (
        ),
        'as' => 'generated::rEpwe6F5mdUfIHxB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aoKI29BJ27heAGqi' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '{school}/institutions/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InstitutionController@destroy',
        'controller' => 'App\\Http\\Controllers\\InstitutionController@destroy',
        'namespace' => NULL,
        'prefix' => '{school}/institutions',
        'where' => 
        array (
        ),
        'as' => 'generated::aoKI29BJ27heAGqi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0w835yLKnSy20ItC' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@index',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@index',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::0w835yLKnSy20ItC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::n4SPBR1UsrdR9Alv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/applications/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@create',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@create',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::n4SPBR1UsrdR9Alv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tlGjEOu3okKInj8p' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{school}/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@store',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@store',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::tlGjEOu3okKInj8p',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::alNGc2ZZ26A5HDK2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/applications/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@show',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@show',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::alNGc2ZZ26A5HDK2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pRzV0w8aCGInnV4I' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/applications/{id}/pdf',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@pdf',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@pdf',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::pRzV0w8aCGInnV4I',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::alniPnHXvTStfjwY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/applications/{id}/pdf/print',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@pdfPrint',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@pdfPrint',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::alniPnHXvTStfjwY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::r18ZfyZ37hpI5KdU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/applications/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@edit',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@edit',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::r18ZfyZ37hpI5KdU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::y5CXukxt5FUOoWzL' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/applications/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@update',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@update',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::y5CXukxt5FUOoWzL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tYZY0iV6qea0GzZq' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '{school}/applications/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\ApplicationController@destroy',
        'controller' => 'App\\Http\\Controllers\\ApplicationController@destroy',
        'namespace' => NULL,
        'prefix' => '{school}/applications',
        'where' => 
        array (
        ),
        'as' => 'generated::tYZY0iV6qea0GzZq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::e28e6PRqV0dWQfYT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/internships',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InternshipController@index',
        'controller' => 'App\\Http\\Controllers\\InternshipController@index',
        'namespace' => NULL,
        'prefix' => '{school}/internships',
        'where' => 
        array (
        ),
        'as' => 'generated::e28e6PRqV0dWQfYT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::owo3Huw7uwjzH4x0' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/internships/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InternshipController@create',
        'controller' => 'App\\Http\\Controllers\\InternshipController@create',
        'namespace' => NULL,
        'prefix' => '{school}/internships',
        'where' => 
        array (
        ),
        'as' => 'generated::owo3Huw7uwjzH4x0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iBk0JGMBhdWIDXiA' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{school}/internships',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InternshipController@store',
        'controller' => 'App\\Http\\Controllers\\InternshipController@store',
        'namespace' => NULL,
        'prefix' => '{school}/internships',
        'where' => 
        array (
        ),
        'as' => 'generated::iBk0JGMBhdWIDXiA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1bRVSC5dy3gyXPGr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/internships/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InternshipController@show',
        'controller' => 'App\\Http\\Controllers\\InternshipController@show',
        'namespace' => NULL,
        'prefix' => '{school}/internships',
        'where' => 
        array (
        ),
        'as' => 'generated::1bRVSC5dy3gyXPGr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LcFntHoQ53321Dl7' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/internships/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InternshipController@edit',
        'controller' => 'App\\Http\\Controllers\\InternshipController@edit',
        'namespace' => NULL,
        'prefix' => '{school}/internships',
        'where' => 
        array (
        ),
        'as' => 'generated::LcFntHoQ53321Dl7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BahTPqxsMDYZUMmo' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/internships/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InternshipController@update',
        'controller' => 'App\\Http\\Controllers\\InternshipController@update',
        'namespace' => NULL,
        'prefix' => '{school}/internships',
        'where' => 
        array (
        ),
        'as' => 'generated::BahTPqxsMDYZUMmo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::at79SlFzRcFovzpR' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '{school}/internships/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\InternshipController@destroy',
        'controller' => 'App\\Http\\Controllers\\InternshipController@destroy',
        'namespace' => NULL,
        'prefix' => '{school}/internships',
        'where' => 
        array (
        ),
        'as' => 'generated::at79SlFzRcFovzpR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PtY9UGw44UZA8hQT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/monitorings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MonitoringLogController@index',
        'controller' => 'App\\Http\\Controllers\\MonitoringLogController@index',
        'namespace' => NULL,
        'prefix' => '{school}/monitorings',
        'where' => 
        array (
        ),
        'as' => 'generated::PtY9UGw44UZA8hQT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zkvVAZeRLIvc0jUu' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/monitorings/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MonitoringLogController@create',
        'controller' => 'App\\Http\\Controllers\\MonitoringLogController@create',
        'namespace' => NULL,
        'prefix' => '{school}/monitorings',
        'where' => 
        array (
        ),
        'as' => 'generated::zkvVAZeRLIvc0jUu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6cPZIej6Hxi16OYf' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{school}/monitorings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MonitoringLogController@store',
        'controller' => 'App\\Http\\Controllers\\MonitoringLogController@store',
        'namespace' => NULL,
        'prefix' => '{school}/monitorings',
        'where' => 
        array (
        ),
        'as' => 'generated::6cPZIej6Hxi16OYf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FOoXn9XhI6KczXuj' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/monitorings/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MonitoringLogController@show',
        'controller' => 'App\\Http\\Controllers\\MonitoringLogController@show',
        'namespace' => NULL,
        'prefix' => '{school}/monitorings',
        'where' => 
        array (
        ),
        'as' => 'generated::FOoXn9XhI6KczXuj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rP89QbP9GUbzkXsu' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/monitorings/{id}/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MonitoringLogController@edit',
        'controller' => 'App\\Http\\Controllers\\MonitoringLogController@edit',
        'namespace' => NULL,
        'prefix' => '{school}/monitorings',
        'where' => 
        array (
        ),
        'as' => 'generated::rP89QbP9GUbzkXsu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XKrYNcSWO0X0lubG' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => '{school}/monitorings/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MonitoringLogController@update',
        'controller' => 'App\\Http\\Controllers\\MonitoringLogController@update',
        'namespace' => NULL,
        'prefix' => '{school}/monitorings',
        'where' => 
        array (
        ),
        'as' => 'generated::XKrYNcSWO0X0lubG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::m77of9XJUxCke7Uc' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '{school}/monitorings/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MonitoringLogController@destroy',
        'controller' => 'App\\Http\\Controllers\\MonitoringLogController@destroy',
        'namespace' => NULL,
        'prefix' => '{school}/monitorings',
        'where' => 
        array (
        ),
        'as' => 'generated::m77of9XJUxCke7Uc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0G4XspaSQEkYOp6Z' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/meta/monitor-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MetaController@monitorTypes',
        'controller' => 'App\\Http\\Controllers\\MetaController@monitorTypes',
        'namespace' => NULL,
        'prefix' => '{school}/meta',
        'where' => 
        array (
        ),
        'as' => 'generated::0G4XspaSQEkYOp6Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3bX02FoQ4MeXaGYu' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{school}/meta/supervisors',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.session',
          2 => 'school',
        ),
        'uses' => 'App\\Http\\Controllers\\MetaController@supervisors',
        'controller' => 'App\\Http\\Controllers\\MetaController@supervisors',
        'namespace' => NULL,
        'prefix' => '{school}/meta',
        'where' => 
        array (
        ),
        'as' => 'generated::3bX02FoQ4MeXaGYu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:33:"/var/www/html/storage/app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:323:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ServeFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"00000000000003640000000000000000";}}',
        'as' => 'storage.local',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
