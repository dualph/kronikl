<?php

return [
    /**
     * Specify the models to watch by providing their classes below.
     *
     * For instance, if you want to watch the User.php model for changes, we put in
     * App\Models\User::class. Since it comes with a default Laravel deployment, we put
     * that below for you. Feel free to remove it if you do not need to record changes for
     * the User model.
     */
    "models" => [
        \App\Models\User::class,
    ],

    /**
     * Specify which fields (columns) to discard in the log for data changes.
     *
     * For security purposes, "passsword" and "remember_token" are included below.
     * Since we are already recording event changes with timestamps, we also included
     * created_at, updated_at, deleted_at and banned_at by default.
     */
    "discards" => [
        "password",
        "remember_token",
        "created_at",
        "updated_at",
        "deleted_at",
        "banned_at"
    ],

    /**
     * Customize messages stored below.
     */
    "messages" => [
        "restore_softdeleted" => "Data restored."
    ],

    /**
     * Select which actions to log.
     *
     * CRUD operations and restoring soft-deleted records are turned on by default.
     */
    "watchers" => [
        "create" => true,
        "update" => true,
        "delete" => true,
        "restore" => true
    ]
];
