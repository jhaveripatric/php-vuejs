<?php

	// Content Type JSON
	header("Content-type: application/json");

	// Database connection
	$conn = new mysqli("localhost", "root", "", "vuejs");
	if ($conn->connect_error) {
		die("Database connection failed!");
	}
	$res = array('error' => false);


	// Read data from database
	$action = 'getCategories';

	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}

	if ($action == 'getCategories') {
		$result = $conn->query("SELECT * FROM `categories`");
		$cat  = array();

		while ($row = $result->fetch_assoc()) {
			// print_r($row);
			array_push($cat, $row);
		}
		$res['category'] = $cat;
	}

	if ($action == 'getDocuments') {
		$cat_id = $_GET['cat_id'];

		$result = $conn->query("SELECT * FROM `documents` WHERE `category_id` = '$cat_id'");

		$doc  = array();

		while ($row = $result->fetch_assoc()) {
			// print_r($row);
			array_push($doc, $row);
		}
		$res['document'] = $doc;
	}

	if ($action == 'addDocument') {
		$dname = $_POST['docName'];
		$cat_id = $_POST['catid'];

		$result = $conn->query("INSERT INTO `documents`(`category_id`, `name`) VALUES ('$cat_id','$dname')");

		if ($result) {
			$res['message'] = "Document added successfully";
		} else {
			$res['error']   = true;
			$res['message'] = "Document insert failed!";
		}
	}

	if ($action == 'updateDoc') {
		$name = $_POST['docName'];
		$doc_id = $_POST['id'];
		$result = $conn->query("UPDATE `documents` SET `name`='$name' WHERE `id` = '$doc_id'");

		if ($result) {
			$res['message'] = "Document updated successfully";
		} else {
			$res['error']   = true;
			$res['message'] = "Document update failed!";
		}
	}

	if ($action == 'deleteDoc') {
		$doc_id = $_POST['id'];
		$result = $conn->query("DELETE FROM `documents` WHERE `id` = '$doc_id'");

		if ($result) {
			$res['message'] = "Document deleted successfully";
		} else {
			$res['error']   = true;
			$res['message'] = "Document delete failed!";
		}
	}	


	// Close database connection
	$conn->close();

	// print json encoded data
	echo json_encode($res);
	die();

?>