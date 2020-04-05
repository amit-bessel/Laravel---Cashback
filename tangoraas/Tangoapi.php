<?php

require_once __DIR__."/vendor/autoload.php";

//class TangoTest {

	function getcatalog($tangoapiplatformkey,$tangoapiplatformname){

		//echo 'amit';exit;
		//customer identifier - account-one

		$platformName = $tangoapiplatformname; // RaaS v2 API Platform Name
		$platformKey = $tangoapiplatformkey; // RaaS v2 API Platform Key

		$client = new RaasLib\RaasClient($platformName, $platformKey);

		//$accounts = $client->getAccounts();

		//$result = $accounts->getAllAccounts();

		$catalog = $client->getCatalog();

		$result = $catalog->getCatalog();

		return $result;

		//echo "<pre>";print_r($result);
	}

	function getcreateorder($utid,$amount,$email,$firstname,$lastname,$status,$dbuseremail,$tangoapiplatformkey,$tangoapiplatformname,$tangoapiaccountidentifier,$tangoapicustomeridentifier){

		

		$platformName =$tangoapiplatformname; // RaaS v2 API Platform Name
		$platformKey = $tangoapiplatformkey; // RaaS v2 API Platform Key

		$client = new RaasLib\RaasClient($platformName, $platformKey);
		$orders = $client->getOrders();
		//$body = new RaasLib\Models\CreateOrderRequestModel();

		$body = new RaasLib\Models\CreateOrderRequestModel();

		/*$result = $orders->createOrder($body);
		print_r($result);exit();*/



		//echo "<pre>";
		//print_r($body);
		//$accounts = $client->getAccounts();

		//$result = $accounts->getAllAccounts();
		 //$name1=new RaasLib\Models\NameEmailModel("gopi1.dhara@gmail.com","gopi","dhara");



		 $name2=new RaasLib\Models\NameEmailModel($email,$firstname,$lastname);
		//$body = new RaasLib\Models\CreateOrderRequestModel("gopiasdfg4",5.0,"gopiasdfg4",false,"U935268","amitda","Gift Order Details","5455","You have ordered a gift card",$name1,$name2,"Receive order");
		//$json=$body->jsonSerialize();
		
		//return $body;
		$body->accountIdentifier=$tangoapiaccountidentifier;
		//$body->amount="5";
		$body->amount=$amount;
		$body->customerIdentifier=$tangoapicustomeridentifier;
		$body->sendEmail=$status;
		//$body->utid="U426872";
		$body->utid=$utid;

		$body->campaign="amitda";
		$body->emailSubject="Gift Order Details";
		$body->externalRefID=rand().time();
		$encodedemail=base64_encode($dbuseremail);
		$body->message="You have ordered a gift card.<br/><br/>If you donot want to receive this type of message  <a href='".url()."/user/unsubscribe?useremail=".$encodedemail."&type=Purchase-confirmation-of-gift-card'>Click on this link to Unsubscribe</a>";
		//$ar=array("email"=>"gopi1.dhara@gmail.com","firstName"=>"gopi","lastName"=>"dhara");
		$body->recipient=$name2;
		//$body->recipient->firstName="Gopi";
		// //$body->recipient->lastName="Dhara";
		$body->sender=$name2;
		// //$body->sender->firstName="Gopi";
		// //$body->sender->lastName="Dhara";

		// //$body->sender="Amitda";
		$body->notes="Receive order";

		//$rs=json_encode($body);
		//return $rs;

		$result = $orders->createOrder($body);
		
		return $result;


	}

	//testme();

	

//}

