<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP VUE.js EXAMPLE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <style type="text/css">
    	html, body {
		  height: 100%;
		}

		#app {
		  display: flex;
		  flex-direction: column;
		  height: 100%;
		}

		.row{
			width: 100%;
		}
		.content {
		  flex: 1 0 auto;
		  /* Prevent Chrome, Opera, and Safari from letting these items shrink to smaller than their content's default minimum size. */
		  padding: 20px;
		}

		.footer {
		  flex-shrink: 0;
		  /* Prevent Chrome, Opera, and Safari from letting these items shrink to smaller than their content's default minimum size. */
		  padding: 20px;
		}

		* {
		  box-sizing: border-box;
		}

		body {
		  margin: 0;
		  font: 16px Sans-Serif;
		  background-color: #c9c9c9;
		}

		footer {
		  background: #222;
		  color: white;
		  text-align: center;
		}

		.navbar.navbar-expand-lg.navbar-dark {
		    background-color: #222;
		}

		.tbody-custom {
		    background-color: #FFF;
		}

		.modal{
			top: 99px;
			left:0;
			right:0;
			bottom:0;
			position: fixed;
			background: #222;
			margin: 0 auto;
			display: block;
			padding: 0;
			height: 420px;
			box-shadow: 0 0 130px 28px #00000073;
		}

		.modal-head{
			background: #222;
			color: #FFF;
			padding: 5px;
			font-size: 17px;
			line-height: 32px;
		}

		.p-left {
		    margin-bottom: 0rem;
		}

		hr {
			border-top: 2px solid rgba(171, 168, 168, 0.1);
		}
		.doctable {
			display: none;
		}
		.table {
			margin-left: auto;
			margin-right: auto;
			width: 90%;
		}
    </style>
</head>
<body>
	<div id="app">
		<nav class="navbar navbar-expand-lg navbar-dark">
			<a class="navbar-brand" href="#">SAMPLE PROJECT</a>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item active">
					<a class="nav-link" href="">Home <span class="sr-only">(current)</span></a>
				</li>
			</ul>
		</nav>
		<div class="content">
			<div class="row">
				<div class="alert alert-danger col-md-6" id="alertMessage" role="alert" v-if="errorMessage">
					{{ errorMessage }}
				</div>
				<div class="alert alert-success col-md-6" id="alertMessage" role="alert" v-if="successMessage">
					{{ successMessage }}
				</div>
			</div>
			<div class="row">
				<div class="col-md-4"><h2>Please select a category</h2></div>
				<div class="col-md-6">
					<select v-model="catSelect" @change="getDocuments($event)" name="category" id="category" class="form-control" tabindex="1">
						<option disabled value="" selected="selected">Please select one</option>
			        	<option v-for="cat in catOptions" v-bind:value="cat.id">
					    	{{ cat.category }}
					  	</option>
				    </select>
				</div>
				<div class="col-md-2 doctable">
					<button id="addmodbut" class="btn btn-link" @click="showingaddModal = true;">Add Document</button>
				</div>
			</div>
			<div class="row doctable" style="padding-top: 35px;">
				<div class="row">
					<h2></h2>
				</div>
				<div class="row" style="padding-top: 20px;">
					<table class="table table-striped">
						<thead class="thead-dark">
							<tr>
								<th>#</th>
								<th>Document</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody class="tbody-custom">
							<tr v-for="(doc, index) in documents" :key='doc.id'>
								<td>{{doMath(index)}}</td>
								<td>{{doc.name}}</td>
								<td><button @click="showingeditModal = true; selectDoc(doc);" class="btn btn-warning">Edit</button></td>
								<td><button @click="showingdeleteModal = true; selectDoc(doc);" class="btn btn-danger">Delete</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<!-- add modal -->
		<div class="modal col-md-6" id="addmodal" v-if="showingaddModal">
				<div class="modal-head">
					<p class="p-left p-2">Add Document</p>
					<hr/>
					<div class="modal-body">
							<div class="col-md-12">
								<label for="name">Document Name</label>
								<input type="text" id="name" class="form-control" v-model="newDoc.docName">
							</div>
						<hr/>
							<button type="button" class="btn btn-success"  @click="showingaddModal = false; addDocument();">Save changes</button>
							<button type="button" class="btn btn-danger"   @click="showingaddModal = false;">Close</button>
					</div>
				</div>
			</div>
	<!-- add modal -->
	<!-- edit modal -->
		<div class="modal col-md-6" id="editmodal" v-if="showingeditModal">
			<div class="modal-head">
				<p class="p-left p-2">Edit Document</p>
				<hr/>
				<div class="modal-body">
						<div class="col-md-12">
							<label for="name">Document Name</label>
							<input type="text" id="name" class="form-control" v-model="clickedDoc.docName">
						</div>
					<hr/>
						<button type="button" class="btn btn-success"  @click="showingeditModal = false; updateDoc();">Save changes</button>
						<button type="button" class="btn btn-danger"   @click="showingeditModal = false;">Close</button>
				</div>
			</div>
		</div>
	<!-- edit modal -->
	<!-- delete data -->
		<div class="modal col-md-6" id="deletemodal" v-if="showingdeleteModal">
			<div class="modal-head">
				<p class="p-left p-2">Delete Document</p>
				<hr/>
				<div class="modal-body">
						<center>
							<p>Are you sure you want to delete?</p>
							<h3>{{clickedDoc.name}}</h3>
						</center>
					<hr/>
						<button type="button" class="btn btn-danger"  @click="showingdeleteModal = false; deleteDoc();">Yes</button>
						<button type="button" class="btn btn-warning"   @click="showingdeleteModal = false;">No</button>
				</div>
			</div>
		</div>
	<!-- delete data -->
		<footer class="footer">
			Designed by Pratik Jhaveri for Richmond Assests and Holding
		</footer>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
	<script src="js/app.js"></script>
</body>
</html>