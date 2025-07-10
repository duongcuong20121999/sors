<?php

return [
    [
        "name" => "Quản trị hệ thống",
        "code" => "QTHT",
        "permissions" => [
            "dashboard",
            "dashboard.show",
            "dashboard.handle.citizen-service",
            "dashboard.update.citizen-service",
            "dashboard.destroy.process",
            "dashboard.citizen-service.update-status",

            "service-configurations.index",
            "service-configurations.create",
            "service-configurations.store",
            "service-configurations.edit",
            "service-configurations.update",
            "service-configurations.destroy",

            "posts.index",
            "posts.create",
            "posts.store",
            "posts.edit",
            "posts.update",
            "posts.destroy",

            "accounts-manager.index",
            "accounts-manager.create",
            "accounts-manager.store",
            "accounts-manager.edit",
            "accounts-manager.update",
            "accounts-manager.destroy",

            "user-roles-manager.index",
            "user-roles-manager.create",
            "user-roles-manager.store",
            "user-roles-manager.edit",
            "user-roles-manager.update",
            "user-roles-manager.destroy",

            "request-history.index",
            "user-logs.index",

            'settings.index',
            'settings.store',

            'service-kiosk-manager.index',
            'service-kiosk-manager.show',
            'service-kiosk-manager.get-number'
        ],
        "url" => "dashboard",
        "priority" => 1
    ],
    [
        "name" => "Xử lý hồ sơ 1 cửa",
        "code" => "XLHS",
        "permissions" => [
            "dashboard",
            "dashboard.show",
            "dashboard.handle.citizen-service",
            "dashboard.update.citizen-service",
            "dashboard.destroy.process",
            "dashboard.citizen-service.update-status",
            "request-history.index",

            
            'service-kiosk-manager.index',
            'service-kiosk-manager.show',
            'service-kiosk-manager.get-number'
        ],
        "url" => "dashboard",
        "priority" => 2
    ],
    [
        "name" => "Sửa bài viết",
        "code" => "SBV",
        "permissions" => [
            "posts.index",
            "posts.edit",
            "posts.update",
        ],
        "url" => "posts.index",
        "priority" => 3
    ],
    [
        "name" => "Xem lịch sử người dùng",
        "code" => "XLS",
        "permissions" => [
            "user-logs.index"
        ],
        "url" => "user-logs.index",
        "priority" => 4
        
    ],
    [
        "name" => "Soạn thảo bài viết",
        "code" => "STBV",
        "permissions" => [
            "posts.index",
            "posts.store",
            "posts.create",
            "posts.edit",
        ],
        "url" => "posts.index",
        "priority" => 5
        
    ],
];