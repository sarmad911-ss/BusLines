<?php
                                                    /** User auth */
Route::any("/login", "AuthController@loginView")->name("loginView");
Route::any("/forgot-request/{key}", "AuthController@forgotRequestView")->name("forgotRequestView");
Route::any("/logout", "AuthController@logout")->name("logout");

                                                    /** User space */
Route::group(['middleware' => 'CheckUser'], function () {
    Route::get("/", "MainController@indexPage")->name("indexPage");
    Route::group(['prefix' => 'settings'], function () {
        Route::get("/", "SettingsController@listView")->name("settingsListView");
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get("/list", "UsersController@listView")->name("listUsersView");
        Route::get("/{user?}", "UsersController@storeView")->name("storeUserView");
    });
    Route::group(['prefix' => 'bus-depot'], function () {
        Route::get("", 'BusDepotController@depotView')->name("depotView");
        Route::get("store/{bus?}", 'BusDepotController@storeView')->name("storeDepotView");
        Route::get("profile/{bus?}", 'BusDepotController@profileView')->name("profileBusView");
    });
    Route::group(['prefix' => 'trips'], function () {
        Route::get("", 'TripController@listView')->name("listTripView");
        Route::get("store/{trip?}", 'TripController@storeView')->name("storeTripView");
        Route::get("profile/{trip}", 'TripController@profileView')->name("profileTripView");
//        Route::get("{trip}/documents", 'TripController@documentsView')->name("documentsTripView");
//        Route::get("{trip}/finances", 'TripController@financesView')->name("financesTripView");
    });

    Route::group(['prefix' => 'finances'], function () {
        Route::get("","FinancesController@listView")->name("financesListView");
    });

    Route::group(['prefix' => 'calculations'],function(){
        Route::get("", "CalculationsController@listView")->name("listCalculationsView");
    });

});
Route::get("/roadmap", "UtilityController@roadmapView")->name("roadmapPage");

                                                    /** API */
Route::group(['prefix'=>'api'],function(){
	Route::post("routes","UtilsController@getRouteUrls")->name("getRoutesList");
	Route::group(['prefix'=>'settings'],function(){
		Route::get("/list",'SettingsController@listData')->name("listSettingsData");
		Route::post("/store",'SettingsController@storeAction')->name("storeSettingsAction");
		Route::post("/upload-logo",'SettingsController@uploadLogoAction')->name("uploadLogoAction");
	});
	Route::group(["prefix"=>'users'],function(){
		Route::get("/list","UsersController@listData")->name("listUserData");
		Route::get("/get","UsersController@singleData")->name("singleUserData");
		Route::post("/store","UsersController@storeAction")->name("storeUserAction");
		Route::post("/remove/","UsersController@removeAction")->name("removeUserAction");
		Route::post("/check-email","UsersController@checkEmailAction")->name("checkEmailAction");
		Route::get("/roles","UsersController@getRolesData")->name("getUserRolesData");
		Route::get("clients","UsersController@listClientsData")->name("listClientsData");
		Route::get("owners","UsersController@listOwnersData")->name("listOwnersData");
	});
	Route::group(['prefix'=>'clients'],function(){
        Route::get("/list","ClientsController@listData")->name("listClientsData");
        Route::post("/store","ClientsController@storeAction")->name("storeClientAction");
        Route::get("/store","ClientsController@storeData")->name("storeClientData");
    });
	Route::post("/login", "AuthController@loginAction")->name("loginAction");
	Route::post("/forgot-password", "AuthController@forgotPasswordAction")->name("forgotPasswordAction");
	Route::post("/change-password", "AuthController@changePasswordAction")->name("changePasswordAction");
	Route::group(['prefix'=>'bus-depot'],function(){
        Route::get("list",'BusDepotController@listData')->name("listBusData");
        Route::get("list/select",'BusDepotController@listSelectData')->name("listBusSelectData");
        Route::get("profile",'BusDepotController@profileData')->name("profileBusData");
        Route::get("store",'BusDepotController@storeData')->name("storeBusData");
        Route::post("store",'BusDepotController@storeAction')->name("storeBusAction");
        Route::any("delete",'BusDepotController@deleteAction')->name("deleteBusAction");
        Route::any("how-many",'BusDepotController@howManyData')->name("howManyData");
        Route::any("types",'BusDepotController@listTypesData')->name("listBusTypesData");
        Route::any("euro-norms",'BusDepotController@listEuroNormsData')->name("listBusEuroNormsData");
        Route::group(['prefix'=>'file'],function(){
            Route::get("test",'BusDepotController@fileTest')->name("fileTest");
            Route::post("store",'BusDepotController@storeFileAction')->name("storeBusFileAction");
            Route::any("delete",'BusDepotController@deleteFileAction')->name("deleteBusFileAction");
        });
    });
    Route::group(['prefix' => 'trips'], function () {
        Route::get("list", 'TripController@listData')->name("listTripData");
        Route::get("profile", 'TripController@profileData')->name("profileTripData");
        Route::get("store",'TripController@storeData')->name("storeTripData");
        Route::post("store", 'TripController@storeAction')->name("storeTripAction");
        Route::any("delete", 'TripController@deleteAction')->name("deleteTripAction");
        Route::get("types", 'TripController@listTypeData')->name("listTripTypeData");
        Route::get("statuses", 'TripController@listStatusData')->name("listTripStatusData");
        Route::get("services", 'TripController@listServiceData')->name("listServiceData");
        Route::get("conditions", 'TripController@listConditionData')->name("listConditionData");
        Route::group(['prefix'=>'file'],function(){
            Route::post("store",'TripController@storeFileAction')->name("storeTripFileAction");
            Route::any("delete",'TripController@deleteFileAction')->name("deleteTripFileAction");
            Route::any("ab",'TripController@storeABAction')->name("storeABAction");
            Route::any("fg",'TripController@storeFGAction')->name("storeFGAction");
            Route::any("pdf",'TripController@storePdfAction')->name("storePdfAction");
            Route::any("send",'TripController@sendMailAction')->name("sendMailAction");
        });
    });
    Route::group(['prefix' => 'finances'], function () {
        Route::get("list","FinancesController@listData")->name("listFinancesData");
        Route::get("store", 'FinancesController@storeData')->name("storeFinancesData");
        Route::post("store", 'FinancesController@storeAction')->name("storeFinancesAction");
        Route::any("delete", 'FinancesController@deleteAction')->name("deleteFinancesAction");
        Route::get("purposes", 'FinancesController@listPurposeData')->name("listPurposeData");
        Route::group(['prefix'=>'file'],function(){
            Route::any("delete", 'FinancesController@deleteFileAction')->name("deleteFinanceFileAction");
            Route::any("rg",'FinancesController@storeRGAction')->name("storeRGAction");
        });
    });
    Route::group(['prefix' => 'calculations'],function(){
        Route::get("list","CalculationsController@listData")->name("listCalculationsData");
        Route::get("store","CalculationsController@storeData")->name("storeCalculationData");
        Route::post("store","CalculationsController@storeAction")->name("storeCalculationAction");
    });
});

Route::any("/browser-abort", "MainController@abortBrowser")->name("abortBrowser");
Route::get("/test-doc","MainController@docGenerateAction")->name("testDoc");
