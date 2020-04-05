<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Logging in and out
//get('/', 'Auth\AuthController@getLogin');
header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

// Route::get('/', function () {
//     return view('frontend.home.home');
// });




get('/auth/login', 	'Auth\AuthController@getLogin'); 
get('/admin', 		'Auth\AuthController@getLogin');
post('/auth/login', 'Auth\AuthController@postLogin');
get('/auth/logout', 'Auth\AuthController@getLogout');

/* Used for Unsubscribe  */
Route::get('/user/unsubscribe', 'Frontend\UserController@getUnsubscribeUser')->name("userunsubscribe");
Route::post('/user/unsubscribe', 'Frontend\UserController@postUnsubscribeUser');

/********************************** Home page starts ******************************************/

Route::get('/', 'Frontend\HomeController@index');

/********************************** Home page ends ********************************************/


/********************************** Home page starts ******************************************/

Route::get('/comming-soon', 'Frontend\HomeController@commingsoon');

/********************************** Home page ends ********************************************/


/* For paypal sdk */
Route::post('payment', array(
    'as' => 'payment',
    'uses' => 'CentralController@postPayment',
));

// when the payment is done, this will redirect us to our page
Route::any('payment/status','CentralController@getPaymentStatus')->name("PaymentStatus");

/* Vendor list with search and sorting start */
Route::post('/storesearchautocomplete', 'Frontend\HomeController@postStoreSearchAutocomplete');
Route::get('/all-stores', 'Frontend\HomeController@getAllstores');
Route::get('/all-stores/details/{id}/{name}', 'Frontend\HomeController@getStoreDetails');
Route::post('/searchbyletter', 'Frontend\HomeController@getSearchByLetter');
Route::post('/searchbyname', 'Frontend\HomeController@getSearchByName');
Route::get('/vendorlist/{id}', 'Frontend\HomeController@getVendorlist');

/* Vendor list with search and sorting end */

resource('/signup', 'Frontend\HomeController@getSignupUser');
resource('/r', 'Frontend\HomeController@getSignupUser');
//resource('/login/{buy_url}', 'Frontend\HomeController@getLogin');
resource('/login', 'Frontend\HomeController@getLogin');


/************************** FB LOGIN START****************************/

resource('/fblogin', 'Frontend\HomeController@getFbLogin');

/************************** FB LOGIN END****************************/

/************************** GOOGLE LOGIN START****************************/

resource('/account/google', 'Frontend\HomeController@getGoogleLogin');

/************************** GOOGLE LOGIN END****************************/

resource('/customsession', 'Frontend\BaseController@setcustomsession');

//resource('/forgot-password', 'Frontend\HomeController@getForgotPassword');
##############################################################################
Route::group( ['before' => 'auth' ], function()
{
	/*Route::get('/admin/user/paypal/return-url/{id}', 'Admin\WithdrawalController@getPaypalReturnData');
	Route::get('/admin/user/paypal/notify-url/{id}', 'Admin\WithdrawalController@getPaypalNotifyData');*/
});

$router->group([
  'namespace' => 'Admin',
  'middleware' => 'auth',
], function () {
	
    Route::post('admin/adminuserpreviousemail/check', 'BaseController@postAdminuserCheck');
    Route::post('admin/siteuserpreviousemail/check', 'BaseController@postSiteuserCheck');
    resource('admin/home', 'HomeController');
    resource('admin/admin-profile', 'HomeController@getProfile');
    resource('admin/change-password', 'HomeController@changePass');

    /*************************Cms management start*****************************************/

    Route::group(['middleware' => 'Subadminpermission:contentlist'], function()
	{
	resource('admin/cms', 'CmspageController');
	});

	
	/*************************Cms management end*****************************************/

    /*************************Site settings management start*****************************************/

	Route::group(['middleware' => 'Subadminpermission:sitesettings'], function()
	{
	resource('admin/sitesetting', 'SitesettingController');
	});

	Route::group(['middleware' => 'Subadminpermission:sitesettings'], function()
	{
	resource('admin/homesetting', 'HomesettingController');
	});

    
	/*************************Site settings management end*****************************************/

	/**********************************************************
			BANNER MANAGEMENT START       
	***********************************************************/

	Route::get('admin/banner/banner-list/', 'BannerController@getbannerList')->middleware(['Subadminpermission:BannerList']);
	Route::post('admin/banner/ajax-banner-list/', 'BannerController@getAjaxBannerList')->middleware(['Subadminpermission:BannerList']);
	Route::get('admin/banner/add-banner', 'BannerController@getAddBanner')->middleware(['Subadminpermission:BannerList']);
	Route::post('admin/banner/add-banner', 'BannerController@postAddBanner')->middleware(['Subadminpermission:BannerList']);
	Route::get('admin/banner/edit-banner/{id}', 'BannerController@getEditBanner')->middleware(['Subadminpermission:BannerList']);
	Route::post('admin/banner/edit-banner', 'BannerController@postEditBanner')->middleware(['Subadminpermission:BannerList']);
	Route::get('admin/banner/delete-banner/{id}', 'BannerController@getDeleteBanner')->middleware(['Subadminpermission:BannerList']);

	/**********************************************************
			BANNER MANAGEMENT END       
	***********************************************************/

    /*****************************FAQ MANAGEMENT START*********************************/

    Route::group(['middleware' => 'Subadminpermission:faq'], function()
	{
	resource('admin/faq', 'FaqController');
	});

	Route::group(['middleware' => 'Subadminpermission:faq'], function()
	{
	resource('admin/faqcategory', 'FaqCategoryController');
	});

     /*****************************FAQ MANAGEMENT END*********************************/

//     Route::get('admin/faq',[
//    'middleware' => 'Subadminpermission:editor',
//    //'uses' => 'TestController@index',
// ]);
	
	resource('admin/faqsubcategory', 'FaqSubCategoryController');
	
	Route::get('/admin/change-cmspage-status/', 'CmspageController@change_cmspage_status');
	Route::get('/admin/check-added-lmd-hotels-for-home/', 'SitesettingController@check_added_lmd_hotels_for_home_page');
	
	Route::get('/admin/multiple-record-operations/', 'HomeController@multiple_record_operations');

	/**************************** Tarnction Page Management ***************************/
	Route::get('/admin/user/transactions', 'TranctionController@getAllTranctionsList');
	Route::get('//admin/user/tranctions/{id}', 'TranctionController@getTranctionsDetails');
	/**************************** Tarnction Page Management ***************************/

	/**************************** Withdrawal Page Management ***************************/
	Route::get('/admin/user/withdrawal', 'WithdrawalController@getAllWithdrawlist');
	Route::get('/admin/user/withdrawal-details/{id}', 'WithdrawalController@getWithdrawsDetails');
	Route::get('/admin/user/withdrawls-requests', 'WithdrawalController@getWithdrawlRequestList');
	Route::get('/admin/user/pay/{id}', 'WithdrawalController@getAdminCardDetails');
	Route::post('/admin/user/stripe/pay/', 'WithdrawalController@postStripePay');
	Route::post('/admin/user/paypal/return-url/{id}', 'WithdrawalController@getPaypalReturnData');
	Route::post('/admin/user/paypal/notify-url/{id}', 'WithdrawalController@getPaypalNotifyData');
	/**************************** Withdrawal Page Management ***************************/
	
	############################Admin user management start###############################

	############################Admin user roll management start###############################

	Route::get('/admin/user/role/{id}', 'UserController@getRole')->middleware(['Subadminpermission:Rolemanagement']);

	Route::post('/admin/user/editrole/{id}', 'UserController@getEditRole')->middleware(['Subadminpermission:Rolemanagement']);

	############################Admin user roll management end###############################

	Route::post('/admin/user/ajax-patients', 'UserController@ajaxPatientsList')->middleware(['Subadminpermission:AdminUserList']);
	
	Route::get('/admin/user/view/{id}', 'UserController@getView')->middleware(['Subadminpermission:AdminUserList']);

	Route::get('/admin/user/add', 'UserController@getAdd')->middleware(['Subadminpermission:AdminUserList']);

	Route::get('/admin/user/list', 'UserController@getList')->middleware(['Subadminpermission:AdminUserList']);

	Route::get('/admin/user/edit/{id}', 'UserController@getEdit')->middleware(['Subadminpermission:AdminUserList']);
	Route::post('/admin/user/edit/{id}', 'UserController@postEdit')->middleware(['Subadminpermission:AdminUserList']);
	Route::get('/admin/user/remove/{id}', 'UserController@getRemove')->middleware(['Subadminpermission:AdminUserList']);
 	Route::controller('/admin/user', 'UserController');

 	############################Admin user management end###############################

	/*****************************USER REFER DETAILS MANAGEMENT START*************************/
	Route::get('/admin/userreferdetails/list', 'UserReferDetailsController@getList')->middleware(['Subadminpermission:ReferralList']);

	Route::post('/admin/userreferdetails/ajax-patients', 'UserReferDetailsController@ajaxPatientsList')->middleware(['Subadminpermission:	
ReferralList']);

	Route::controller('/admin/userreferdetails', 'UserReferDetailsController');

	/*****************************USER REFER DETAILS MANAGEMENT END*************************/

	/*****************************USER TICKET DETAILS MANAGEMENT*************************/

	Route::any('/admin/ticket_list', 'UserTicketDetailsController@ticket_list')->name('TicketList')->middleware(['Subadminpermission:TicketList']);

	Route::any('/admin/ticket_list_closed', 'UserTicketDetailsController@closed_ticket_list')->name('ClosedTicketList')->middleware(['Subadminpermission:TicketList']);

	Route::any('/admin/ticket_issue_list', 'UserTicketDetailsController@ticket_issue_list')->name('TicketIssueList')->middleware(['Subadminpermission:TicketList']);

	Route::get('/admin/ticket_issue_edit/{id}', 'UserTicketDetailsController@ticket_issue_edit')->middleware(['Subadminpermission:TicketList']);

	Route::post('/admin/ticket_issue_edit', 'UserTicketDetailsController@postIssueupdate')->middleware(['Subadminpermission:TicketList']);

	Route::post('admin/ticket_issue_delete','UserTicketDetailsController@postIssuedelete')->middleware(['Subadminpermission:TicketList']);
	Route::get('/admin/ticket_issue/add-issue','UserTicketDetailsController@getIssueadd')->middleware(['Subadminpermission:TicketList']);
	Route::post('/admin/ticket_issue/add-issue','UserTicketDetailsController@postIssueadd')->middleware(['Subadminpermission:TicketList']);


	Route::any('/admin/reply_ticket/{ticket_id}', 'UserTicketDetailsController@reply_ticket')->name('ReplyTicket')->middleware(['Subadminpermission:TicketList']);
	Route::any('/admin/add_reply_ticket', 'UserTicketDetailsController@add_reply_ticket')->name('AddReplyTicket')->middleware(['Subadminpermission:TicketList']);
	Route::any('/admin/ajax_active_issue_list', 'UserTicketDetailsController@ajax_active_issue_list')->name('AjaxPatientsList')->middleware(['Subadminpermission:TicketList']);
	Route::any('/admin/ajax_closed_issue_list', 'UserTicketDetailsController@ajax_closed_issue_list')->name('AjaxPatientsList')->middleware(['Subadminpermission:TicketList']);
	Route::any('/admin/change_ticket_status', 'UserTicketDetailsController@change_ticket_status')->name('ChangeTicketStatus')->middleware(['Subadminpermission:TicketList']);

	//Route::controller('/admin/userreferdetails', 'UserReferDetailsController');

 /********************** BANNER MANAGEMENT *********************/

    get('/admin/add-topbanner', 'TopbannerController@addBanner');

	post('/admin/post-topbanner', 'TopbannerController@postBanner');
	post('/admin/upload-image', 'TopbannerController@postUploadImage');
    get('/admin/topbanner-list', 'TopbannerController@topbannerList');
    get('/admin/edit-topbanner/{id}', 'TopbannerController@editBanner');
	post('/admin/update-topbanner', 'TopbannerController@updateBanner');
    get('/admin/delete-topbanner/{id}', 'TopbannerController@deleteBanner');
    post('/admin/topbanner-change-status', 'TopbannerController@topbannerChangeStatus');
    
    post('/admin/upload-image-crop', 'HomePageController@postUploadImage');
    post('/admin/upload-image-category', 'CategoryController@postUploadImage');
    post('/admin/upload-image-brand', 'BrandController@postUploadImage');
    post('/admin/upload-image-vendor', 'VendorController@postUploadImage');
	/********************** BANNER MANAGEMENT *********************/

  /************************ GIFT CARD MANAGEMENT ************************/
  Route::get('/admin/gift-card/list', 'GiftcardController@getList')->middleware(['Subadminpermission:GiftCards']);
  Route::post('/admin/gift-card-ajax', 'GiftcardController@ajaxGiftcard')->middleware(['Subadminpermission:GiftCards']);
  Route::post('/admin/gift-card/changegiftcardstatus','GiftcardController@ajaxGiftcardStatus')->middleware(['Subadminpermission:GiftCards']);

  /************************ GIFT CARD MANAGEMENT ************************/

  
/**************************** Site User Management Start ***************************/
	
	Route::get('/admin/siteuser/invite', 'SiteUserController@getInvite')->middleware(['Subadminpermission:InviteUser']);

	/***********************Invite friend list start***************************/
	Route::get('/admin/siteuser/invite-friend-list-notregistered', 'SiteUserController@getInviteFriendListNotRegistered')->middleware(['Subadminpermission:InviteList']);
	Route::post('/admin/siteuser/ajaxinvitefriendlistnotregistered', 'SiteUserController@postAjaxInviteFriendListNotRegistered')->middleware(['Subadminpermission:InviteList']);

	Route::get('/admin/siteuser/invite-friend-list-registered', 'SiteUserController@getInviteFriendListRegistered')->middleware(['Subadminpermission:InviteList']);
	Route::post('/admin/siteuser/ajaxinvitefriendlistregistered', 'SiteUserController@postAjaxInviteFriendListRegistered')->middleware(['Subadminpermission:InviteList']);
	/***********************Invite friend list end***************************/

	/**************Super affliate payout start**********************/

	Route::get('/admin/super_affliate_payout','SiteUserController@getSuperaffiliatepayout')->middleware(['Subadminpermission:SuperAffiliatePayout']);
	Route::post('/admin/super-affiliate-payout-transaction','SiteUserController@postSuperaffiliatepayout')->middleware(['Subadminpermission:SuperAffiliatePayout']);

	/**************Super affliate payout end**********************/

	/**************Site user individual transaction start*******************/

	Route::get('/admin/siteuser/transaction/{id}','SiteUserController@getUsertransaction');
	Route::post('/admin/user-transaction/{id}','SiteUserController@PostUsertransaction');

	/**************Site user individual transaction end*******************/

	/**************Site user all transaction start*******************/

	Route::get('/admin/siteuser/all-transaction','SiteUserController@getAllSiteUserTransaction');
	Route::post('/admin/allsiteuser-transaction','SiteUserController@postAllSiteUserTransaction');

	/**************Site user all transaction end*******************/

	Route::post('/admin/siteuser/inviteinsert', 'SiteUserController@postShareReferCodeInsert')->middleware(['Subadminpermission:InviteUser']);
	Route::post('/admin/siteuser/check', 'SiteUserController@postCheck')->middleware(['Subadminpermission:SiteUserList']);
	
	Route::post('/admin/siteuser/changesuperaffiliatestatus', 'SiteUserController@postChangeSuperaffiliatestatus')->middleware(['Subadminpermission:SiteUserList']);

   Route::get('/admin/siteuser/generateSuperaffiliateCode', 'SiteUserController@getGenerateSuperaffiliateCode')->middleware(['Subadminpermission:SiteUserList']);
   Route::post('/admin/siteuser/ajax-patients', 'SiteUserController@ajaxPatientsList')->middleware(['Subadminpermission:SiteUserList']);
   Route::get('/admin/siteuser/list', 'SiteUserController@getList')->middleware(['Subadminpermission:SiteUserList']);

   //get('/admin/siteuser/getStatus', 'SiteUserController@getStatus');
   Route::get('/admin/siteuser/status', 'SiteUserController@getStatus')->middleware(['Subadminpermission:SiteUserList']);
   Route::get('/admin/siteuser/remove/{id}', 'SiteUserController@getRemove')->middleware(['Subadminpermission:SiteUserList']);
   Route::get('/admin/siteuser/view/{id}', 'SiteUserController@getviewuser')->middleware(['Subadminpermission:SiteUserList']);

   Route::group(['middleware' => 'Subadminpermission:SiteUserList'], function()
	{
	resource('admin/siteuser', 'SiteUserController');
	});


   

   /**************************** Site User Management End ***************************/

   /*********************************** User withdrawl management start ***********************************/
    Route::post('/admin/userwithdrawl/ajax-withdrawl-success', 'UserwithdrawlController@ajaxWithdrawlSuccess')->middleware(['Subadminpermission:Withdrawl']);
    Route::post('/admin/userwithdrawl/ajax-withdrawl-request', 'UserwithdrawlController@ajaxWithdrawlRequest')->middleware(['Subadminpermission:Withdrawl']);
   	Route::get('/admin/userwithdrawl/request', 'UserwithdrawlController@getWithdrawlRequest')->middleware(['Subadminpermission:Withdrawl']);
   	Route::get('/admin/userwithdrawl/success', 'UserwithdrawlController@getWithdrawlSuccess')->middleware(['Subadminpermission:Withdrawl']);
	Route::controller('/admin/userwithdrawl', 'UserwithdrawlController');

	/*********************************** User withdrawl management end ***********************************/

    /**************************** Case Material Management ***************************/

	Route::controller('/admin/user', 'UserController');


	/****************************Vendors Management start ***************************/

	Route::get('/admin/vendor/remove/{id}', 'VendorsController@getRemove')->middleware(['Subadminpermission:VendorsList']);

	Route::get('/admin/vendor/changepopularstatus', 'VendorsController@getPopularStatus')->middleware(['Subadminpermission:VendorsList']);

	Route::post('/admin/vendor/ajax-vendors', 'VendorsController@ajaxVendorsList')->middleware(['Subadminpermission:VendorsList']);

	Route::get('/admin/vendor/list', 'VendorsController@getList')->middleware(['Subadminpermission:VendorsList']);

	Route::get('/admin/vendor/edit/{id}', 'VendorsController@getEdit')->middleware(['Subadminpermission:VendorsList']);

	Route::post('/admin/vendor/edit/{id}', 'VendorsController@postEdit')->middleware(['Subadminpermission:VendorsList']);

	Route::controller('/admin/vendor', 'VendorsController');

	/****************************Vendors Management end ***************************/

	//Route::controller('/admin/tangoapi', 'TangoapiController');


	/**************************** Case Material Management ***************************/

	/**************************** City Management ***************************/
	Route::controller('/admin/city', 'CityController');
	/**************************** City Management ***************************/

	/**************************** Category Management ***************************/
	Route::controller('/admin/category', 'CategoryController');
	/**************************** Category Management ***************************/

	/**************************** Product Management ***************************/
	Route::controller('/admin/products', 'ProductController');
	/**************************** Products Management ***************************/

	/**************************** Brand Management ***************************/
	Route::controller('/admin/brands', 'BrandController');

	/**************************** Brand Management ***************************/

	/**************************** Vendor Management ***************************/
	Route::controller('/admin/vendors', 'VendorController');
	/**************************** Vendor Management ***************************/

	/**************************** Vendor Management ***************************/
	Route::controller('/admin/datafeeds', 'DataFeedController');
	/**************************** Vendor Management ***************************/

	/**************************** Store Management ***************************/
	Route::controller('/admin/stores', 'StoreController');
	/**************************** Store Management ***************************/

	/**************************** Home Page Management ***************************/
	Route::controller('/admin/homepage', 'HomePageController');
	/**************************** Home Page Management ***************************/

	############################Admin card Routes Starts###############################
	Route::controller('/admin/cards', 'CardController');
	############################Admin card Routes Ends#################################
});

Route::group(
    array('prefix' => 'admin'), 
    function() {
        Route::get('forgotpassword', 'Admin\HomeController@forgotPassword');
        Route::post('forgotpasswordcheck', 'Admin\HomeController@forgotpasswordcheck');
        Route::get('resetpassword/{id}', 'Admin\HomeController@resetpassword');
        Route::post('updatepassword/{id}', 'Admin\HomeController@updatePassword');
		
	}
);

/*******	Route for cron functions 	*************/
//Route::controller('/cron','Frontend\CronController');
Route::get('/search/search-product', 'Frontend\HomeController@getSearchProduct');
//Route::get('/', 'Frontend\HomeController@index');

//Route::get('/', 'Frontend\TestController@index');




Route::group(
    array('prefix' => 'cron'), 
    function() {
        Route::controller('/', 'Frontend\CronController');
    }
);

Route:: get('/mongouser', 'Frontend\MongouserController@getIndex');
Route:: get('/mongouser/datainsert', 'Frontend\MongouserController@getDataInsert');

################# Brand section ############################
Route:: get('/brand', 'Frontend\BrandController@getCatalog');
Route:: get('/brand/details/{id}', 'Frontend\BrandController@getBrandDetails');
Route:: post('/brand/order', 'Frontend\BrandController@postCreateorder');
################# CJ Api Test function ############################

Route::get('/test1', 'Frontend\TestCronController@getProductsCustomList');
Route::get('/test2', 'Frontend\TestCronController@abc');
Route::post('/test1', 'Frontend\TestCronController@getProductsCustomList');
Route::get('/conveter', 'Frontend\TestCronController@currencyConveterApi');
Route::get('/back-conveter', 'Frontend\TestCronController@processConveterApi');
Route::get('/report-api', 'Frontend\CronController@getReportApiForLinkShare');

################# vendor controller ###############################
Route::get('/stores/click/{id}', 'Frontend\PartnerController@getCashbackDetails');
Route::get('/stores/{id}', 'Frontend\PartnerController@getPartnerDetails');
Route::get('/stores', 'Frontend\PartnerController@postList');
Route::post('/partners', 'Frontend\PartnerController@postList');
Route::get('/search-vendor', 'Frontend\PartnerController@searchVendor');
Route::post('/search-vendor', 'Frontend\PartnerController@searchVendor');
Route::get('/sort-vendor-by-cashback', 'Frontend\PartnerController@sortVendorByCashback');
Route::post('/sort-vendor-by-cashback', 'Frontend\PartnerController@sortVendorByCashback');
Route::get('/sort-vendor-by-category', 'Frontend\PartnerController@searchVendorByCategory');
Route::post('/sort-vendor-by-category', 'Frontend\PartnerController@searchVendorByCategory');
Route::post('/vendor/session/', 'Frontend\PartnerController@postSetSessionVendorId');
Route::post('/vendor-id/forgot/session/', 'Frontend\PartnerController@postForgotSessionVendorId');
################# vendor controller ###############################

################# Link Share Api Test function ############################
Route::get('/linkshare', 'Frontend\CronController@getFtpLinkshareDatafeed');
Route::post('/linkshare', 'Frontend\CronController@getFtpLinkshareDatafeed');

################# CJ Api Vendor function ############################

################# Link Share Api Test function ############################
Route::get('/hitbackground', 'Frontend\UserCronController@getHitBackgroud');

Route::get('/cjallvendor', 'Frontend\UserCronController@getCJVendorList');

Route::get('/cjvendor', 'Frontend\CronController@getCJVendorList');
Route::post('/cjvendor', 'Frontend\CronController@getCJVendorList');
Route::get('/cjvendorbghit', 'Frontend\CronController@getCjvendorbghit');

################# Link Share Delta File Cron Url ############################
Route::get('/update-linkshare-products', 'Frontend\FinalCronController@getFtpLinkshareDeltaDatafeed');
Route::post('/update-linkshare-products', 'Frontend\FinalCronController@getFtpLinkshareDeltaDatafeed');
Route::get('/update-linkshare-products/background', 'Frontend\FinalCronController@getHitLSDataFeedBG');

Route::get('/update-cj-products', 'Frontend\FinalCronController@getFtpCjDataFeed');
Route::post('/update-cj-products', 'Frontend\FinalCronController@getFtpCjDataFeed');
Route::get('/update-cj-products/background', 'Frontend\FinalCronController@getBgHitCjDataFeed');

################# Link Share Api vendor function ############################
Route::get('/lsvendor', 'Frontend\CronController@getLinkshareVendorList');
Route::post('/lsvendor', 'Frontend\CronController@getLinkshareVendorList');

################# Link Share Api Test function ############################
Route::get('/hitfunction', 'Frontend\CronController@getHitBackgroud');
Route::post('/hitfunction', 'Frontend\CronController@getHitBackgroud');

/*******   Frontend check email availability **********/
//Route::post('/check-email-availability', 'Frontend\HomeController@getCheckEmailAvailability');

Route::post('/check-email-availability', 'Frontend\BaseController@postSiteuserCheck');

post('/user/signup-user', 'Frontend\HomeController@postAddUser');
post('/newslatter-user', 'Frontend\HomeController@postAddNewslatterUser');

Route::get('/user/active_account/{id}', 'Frontend\HomeController@active_account');

resource('/signin-user', 'Frontend\HomeController@signinUser');

Route::get('/forgot-password', 'Frontend\HomeController@forgot_password');
Route::post('/forgot-password', 'Frontend\HomeController@forgot_password');

Route::post('/reset-password/{reset_password_key}', 'Frontend\HomeController@reset_password');
Route::get('/reset-password/{reset_password_key}', 'Frontend\HomeController@reset_password');


################ USER DASHBAORD START ####################################

Route::get('/user/dashboard', 'Frontend\UserController@getDashboard');
Route::get('/user/mydashboard', 'Frontend\UserController@getMyDashboard');
Route::post('/user/user-totalearn', 'Frontend\UserController@getUserEarn');

################ USER DASHBAORD END ####################################

################ USER MY PROFILE START ####################################

Route::get('/user/my-profile', 'Frontend\HomeController@my_profile');

################ USER MY PROFILE END ####################################

################ Cashback balance start####################################

################ Manual withdraw start####################################

Route::get('/user/cashbackbalance', 'Frontend\UserController@getCashbackBalance');

################ Manual withdraw end####################################

################ Auto withdraw start####################################

Route::get('/user/auto-purchase-giftcard', 'Frontend\UserController@getAutoPurchaseGiftCard');
Route::post('/user/auto-purchase-giftcard', 'Frontend\UserController@postAutoPurchaseGiftCard');
Route::post('/user/auto-purchase-giftcard-facevalue', 'Frontend\UserController@postAutoPurchaseGiftCardFacevalue');
################ Auto withdraw end####################################

################ Cashback balance end####################################

################ Cashback withdraw ####################################

Route::post('/user/withdrawcashbackamount', 'Frontend\UserController@postWithdrawCashbackAmount');

################ Transaction credit and debit start ####################################

Route::get('/user/viewcreditsdebits', 'Frontend\UserController@viewCreditsDebits');

################ Transaction credit and debit end ####################################

################ Gift Card  start####################################
Route::get('/user/sellgiftcard', 'Frontend\UserController@getSellGiftCard');
Route::get('/user/viewallgiftcard', 'Frontend\UserController@getViewallgiftcard');
Route::get('/user/giftcarddelete/{id}', 'Frontend\UserController@getGiftCardDelete');

################ Gift Card  end####################################

################ Refer Section start ####################################

Route::get('/user/referdetails', 'Frontend\UserController@getViewReferDetails');
Route::get('/user/referuserdetails/{id}', 'Frontend\UserController@getViewUserDetails');

################ Refer Section end ####################################

################ Share Refer Code start ####################################
Route::get('/user/sharecode', 'Frontend\UserController@shareReferCode');
Route::post('/user/sharecodeinsert', 'Frontend\UserController@shareReferCodeInsert');
Route::get('/user/sharelinkinfb', 'Frontend\UserController@shareLinkinFb');

################ Share Refer Code end ####################################

################ Invite friend list start ####################################

Route::get('/user/invitefriendslist', 'Frontend\UserController@viewInviteFriends');
Route::post('/user/invitefriend', 'Frontend\UserController@inviteFriend');

############################# Change refer code #################################

Route::post('/change-refer-code', 'Frontend\UserController@updateReferCode');
Route::post('/send-refer-code-bulk','Frontend\UserController@sendReferCode');
Route::get('/refer-bulk/{referby}/{refercode}/{email}','Frontend\UserController@sendInvite');


################ Invite friend list end ####################################


Route::get('/user/transactions', 'Frontend\HomeController@transaction');
Route::get('/user/cashback', 'Frontend\HomeController@cashback');


Route::get('/user/logout', 'Frontend\HomeController@logout');

################ USER EDIT PAYPAL START ####################################
Route::post('/user/edit-paypalid', 'Frontend\HomeController@editPaypalId');
################ USER EDIT PAYPAL END ####################################

################ USER CHANGE PASSWORD START ####################################

Route::get('/user/change-password', 'Frontend\HomeController@changePassword');
Route::post('/user/change-password', 'Frontend\HomeController@changePassword');

################ USER CHANGE PASSWORD END ####################################

################ USER PROFILE IMAGE REMOVE START ####################################

Route::post('/user/removeprofileimage', 'Frontend\HomeController@removeProfileImage');

################ USER PROFILE IMAGE REMOVE END ####################################

################ USER CHANGE NOTIFICATION START ####################################

Route::post('/user/changeemailnotification', 'Frontend\HomeController@changeEmailNotification');

################ USER CHANGE NOTIFICATION END ####################################

################ USER EDIT PROFILE START ####################################

Route::get('/user/edit-profile', 'Frontend\HomeController@editProfile');
Route::post('/user/edit-profile', 'Frontend\HomeController@editProfile');

################ USER EDIT PROFILE END ####################################

Route::get('/product/click/{id}', 'Frontend\ProductController@getProductCashback');
Route::get('/products/{id}/{pro_name}', 'Frontend\ProductController@viewProducts');
Route::get('/product-list', 'Frontend\ProductController@getProductList');
Route::get('/search/products', 'Frontend\ProductController@viewsearchProducts');
Route::get('/search-product-list', 'Frontend\ProductController@getSearchProductList');
Route::get('/product_details/{id}/{pro_name}', 'Frontend\ProductController@viewProductDetails');
Route::post('/product/session/', 'Frontend\ProductController@postSetSessionProductId');
Route::post('/product-id/forgot/session/', 'Frontend\ProductController@postForgotSessionProductId');

Route::get('/add-to-wishlist/{id}', 'Frontend\WishlistController@addToWishlist');
Route::get('/my-wishlist', 'Frontend\WishlistController@myWishlist');
Route::get('/remove-product/{id}', 'Frontend\WishlistController@removeProduct');
Route::get('/get-my-wishlist', 'Frontend\WishlistController@getMyWishlist');
Route::get('/search-product', 'Frontend\WishlistController@getSearchProduct');
Route::get('/products-search-list', 'Frontend\WishlistController@getSearchList');
Route::get('/brands-wish-list', 'Frontend\WishlistController@getwishlistBrand');
Route::get('/buy-now/{url}', 'Frontend\WishlistController@getBuyNow');

Route::get('/designer-list', 'Frontend\DesignerController@designerList');
Route::get('/designer-details/{id}/{design_name}', 'Frontend\DesignerController@designerBrandList');
Route::get('/brand-product-list', 'Frontend\DesignerController@getProductList');


/**************************** Bank Account ***************************/
Route::get('/user/my-bank-transfer', 'Frontend\BankAccountController@postBalanceTransferVaiStripe');
Route::controller('/user/my-account-details', 'Frontend\BankAccountController');
#Route::get('/my-account-details', 'Frontend\DesignerController@getProductList');
/**************************** Bank Account ***************************/

/**************************** Frontend Controller ***************************/
Route::get('/user/transactions', 'Frontend\UserController@getOrderHistory');
Route::get('/order-list', 'Frontend\UserController@getOrdertList');
Route::get('/withdrawls-list', 'Frontend\UserController@getWithdrawlList');
/**************************** Frontend Controller ***************************/

/***********************faq management start*********************************/

Route:: get('/faqs', 'Frontend\FaqController@faqList');

/***********************faq management end*********************************/


/**************************** Frontend Controller Tickets start***************************/
Route:: any('/ticket/add_emotional_state', 'Frontend\TicketController@ticket_emotional_state')->name('TicketEmotionalState');/*For Add Tickets */

Route:: any('/ticket/add', 'Frontend\TicketController@addticket')->name('AddTicket');/*For Add Tickets */

Route:: any('/ticket/view/{ticket_id?}/{val?}', 'Frontend\TicketController@viewticket')->name('ViewTicket');/*View Tickets */
Route:: any('/ticket/user_add_reply_ticket', 'Frontend\TicketController@user_add_reply_ticket')->name('UserAddReplyTicket');/*Search Tickets */
Route:: any('/ticket/serach_ticket', 'Frontend\TicketController@serach_ticket')->name('SerachTicket');/*Search Tickets */

Route:: any('/ticket/close_ticket', 'Frontend\TicketController@close_ticket')->name('CloseTicket');/*Search Tickets */

Route:: any('/ticket/filter_ticket', 'Frontend\TicketController@filter_ticket')->name('FilterTicket');/*Search Tickets */

/**************************** Frontend Controller Tickets end***************************/

get('/contact-us', 'Frontend\HomeController@getContactUs');
post('/submit-contact-us', 'Frontend\HomeController@postContactUs');

Route::get('/product-xml-report', 'Frontend\XmlController@generateProdcutXmlReport');

/* CMS PAGE */
Route:: get('/{param}','Frontend\HomeController@getCmsPageContent');   /* For Show content */
Route:: get('/cms/how-it-works','Frontend\HomeController@getHowItWorks');   /* For Show content */
Route:: post('/subcribe-email','Frontend\HomeController@postEmailSubcribe');   /* For Show content */
/*--------------------------------------------------*/


$router->group([
  'middleware' => 'http_authenticate',
], function () {
	Route::get('api/user/profile-info', 'Service\UserController@getUserDetails');
	Route::get('api/user/purchase-info', 'Service\UserController@getPurchaseHistory');
	Route::get('api/user/wishlist-info', 'Service\UserController@getUserWishlist');
	Route::get('api/user/cashback-amount', 'Service\UserController@getUserCashbackAmount');
	Route::get('api/vendor/vendor-info', 'Service\UserController@getVendorDetails');
	}
);