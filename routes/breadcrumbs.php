<?php

/** Home  */
Breadcrumbs::for('indexPage', function ($trail) {
    $trail->push('Home', route('indexPage'));
});

/** Profile  */
Breadcrumbs::for('profilePage', function ($trail) {
    $trail->push('Profile', route('profilePage'));
});